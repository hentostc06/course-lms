<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

final class ShowcaseDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'development', 'testing'])
            || getenv('LMS_DEMO_CONFIRM') !== 'SEED_LOCAL_ONLY') {
            throw new RuntimeException(
                'Seeder hanya untuk database development dengan konfirmasi.'
            );
        }

        $password = getenv('LMS_DEMO_PASSWORD');

        if (! is_string($password) || strlen($password) < 12) {
            throw new RuntimeException('Password demo minimal 12 karakter.');
        }

        foreach ([
            'users', 'roles', 'role_user', 'instructor_profiles',
            'categories', 'courses', 'course_sections', 'lessons'
        ] as $table) {
            if (! Schema::hasTable($table)) {
                throw new RuntimeException(
                    "Tabel {$table} belum tersedia. Periksa migrate:status."
                );
            }
        }

        $data = json_decode(
            file_get_contents(__DIR__.'/showcase-demo-data.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        DB::transaction(function () use ($data, $password): void {
            $now = now();
            $roles = [];

            foreach (['super_admin', 'admin', 'instructor', 'student'] as $role) {
                $id = DB::table('roles')->where('name', $role)->value('id');

                if (! $id) {
                    throw new RuntimeException("Role {$role} tidak ditemukan.");
                }

                $roles[$role] = $id;
            }

            // USERS: tidak reset password akun yang sudah ada.

            $users = [];

            foreach ($data['users'] as $row) {
                $existing = DB::table('users')
                    ->where('email', $row['email'])->first();

                if ($existing && $existing->name !== $row['name']) {
                    throw new RuntimeException(
                        "Email demo digunakan akun berbeda: {$row['email']}"
                    );
                }

                $id = $existing?->id ?? DB::table('users')->insertGetId([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make($password),
                    'email_verified_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('role_user')->insertOrIgnore([
                    'role_id' => $roles[$row['role']],
                    'user_id' => $id,
                ]);

                $users[$row['email']] = $id;
            }

            // INSTRUCTOR PROFILES

            $profiles = [];

            foreach ($data['instructors'] as $row) {
                $owner = $users[$row['email']];

                $existing = DB::table('instructor_profiles')
                    ->where('slug', $row['slug'])->first();

                if ($existing && (int) $existing->user_id !== (int) $owner) {
                    throw new RuntimeException('Konflik profil '.$row['slug']);
                }

                $id = $existing?->id
                    ?? DB::table('instructor_profiles')->insertGetId([
                        'user_id' => $owner,
                        'slug' => $row['slug'],
                        'headline' => $row['headline'],
                        'bio' => $row['bio'],
                        'status' => $row['status'],
                        'approved_at' => $row['status'] === 'approved'
                            ? $now : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                $profiles[$row['email']] = $id;
            }

            // KATEGORI

            $categoryIds = [];

            foreach ($data['categories'] as $row) {
                $existing = DB::table('categories')
                    ->where('slug', $row['slug'])->first();

                if ($existing && $existing->name !== $row['name']) {
                    throw new RuntimeException('Konflik kategori '.$row['slug']);
                }

                $id = $existing?->id
                    ?? DB::table('categories')->insertGetId([
                        'name' => $row['name'],
                        'slug' => $row['slug'],
                        'description' => $row['description'],
                        'is_active' => true,
                        'sort_order' => $row['sort_order'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                $categoryIds[$row['slug']] = $id;
            }

            // COURSES + SECTIONS + LESSONS

            foreach ($data['courses'] as $row) {
                $instructorId = $profiles[$row['instructor_email']];

                $existing = DB::table('courses')
                    ->where('slug', $row['slug'])->first();

                if ($existing && (
                    (int) $existing->instructor_id !== (int) $instructorId
                    || $existing->deleted_at !== null
                )) {
                    throw new RuntimeException(
                        'Konflik kepemilikan kursus '.$row['slug']
                    );
                }

                $id = $existing?->id ?? DB::table('courses')->insertGetId([
                    'instructor_id' => $instructorId,
                    'category_id' => $categoryIds[$row['category_slug']],
                    'title' => $row['title'],
                    'slug' => $row['slug'],
                    'summary' => 'Kelas praktik '.$row['title']
                        .' dengan materi yang disusun bertahap.',
                    'description' => 'Kursus demonstrasi LMS. '
                        .'Materi artikel tersedia sebagai contoh; video '
                        .'dan pembayaran belum diaktifkan.',
                    'outcomes' => json_encode([
                        'Memahami konsep inti',
                        'Menyelesaikan latihan mandiri',
                        'Menilai hasil belajar',
                    ], JSON_THROW_ON_ERROR),
                    'requirements' => json_encode([
                        'Laptop atau komputer',
                        'Koneksi internet',
                    ], JSON_THROW_ON_ERROR),
                    'audience' => json_encode([
                        'Pelajar dan profesional sesuai tingkat kursus',
                    ], JSON_THROW_ON_ERROR),
                    'price_idr' => $row['price_idr'],
                    'level' => $row['level'],
                    'language' => 'id',
                    'status' => $row['status'],
                    'thumbnail_url' => $row['thumbnail_url'],
                    'total_duration_minutes' => $row['total_duration_minutes'],
                    'published_at' => $row['status'] === 'published'
                        ? $now->copy()->subDays($row['days_old']) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                foreach ([
                    'Mulai dari dasar',
                    'Konsep dan penerapan',
                    'Latihan dan evaluasi',
                ] as $i => $sectionTitle) {
                    $order = $i + 1;

                    $section = DB::table('course_sections')
                        ->where('course_id', $id)
                        ->where('sort_order', $order)
                        ->first();

                    $sectionId = $section?->id
                        ?? DB::table('course_sections')->insertGetId([
                            'course_id' => $id,
                            'title' => $sectionTitle,
                            'description' => 'Bagian '.$order.' — '.$row['title'],
                            'sort_order' => $order,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                    $lessons = [
                        [
                            'Artikel: '.$sectionTitle,
                            'article',
                            'Materi '.$sectionTitle.' untuk '.$row['title']
                                .".\n\nTujuan: pahami konsep, buat ringkasan, "
                                .'dan coba praktik sederhana.',
                            720,
                            true,
                        ],
                        [
                            'Video demonstrasi (segera hadir)',
                            'video',
                            null,
                            1200,
                            false,
                        ],
                        [
                            'Latihan: '.$sectionTitle,
                            'article',
                            'Latihan mandiri untuk '.$row['title']
                                .".\n\n1. Catat tiga konsep penting."
                                ."\n2. Praktikkan satu langkah."
                                ."\n3. Evaluasi hasilnya.",
                            600,
                            false,
                        ],
                    ];

                    foreach ($lessons as $j => [
                        $lessonTitle, $type, $content, $seconds, $preview
                    ]) {
                        $lessonOrder = $j + 1;

                        $exists = DB::table('lessons')
                            ->where('course_section_id', $sectionId)
                            ->where('sort_order', $lessonOrder)
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        DB::table('lessons')->insert([
                            'course_section_id' => $sectionId,
                            'title' => $lessonTitle,
                            'summary' => 'Materi contoh '.$row['title'],
                            'type' => $type,
                            'content' => $content,
                            'duration_seconds' => $seconds,
                            'is_preview' => $preview,
                            'sort_order' => $lessonOrder,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }
        }, 3);

        $published = DB::table('courses')
            ->join(
                'instructor_profiles as p',
                'p.id',
                '=',
                'courses.instructor_id'
            )
            ->where('courses.status', 'published')
            ->where('p.status', 'approved')
            ->whereNotNull('courses.published_at')
            ->where('courses.published_at', '<=', now())
            ->whereNull('courses.deleted_at')
            ->count();

        $this->command?->info(
            'Seeder selesai. Total kursus published dari instruktur approved: '
            .$published
        );
    }
}
