<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Catalog\Enums\CourseLevel;
use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Enums\LessonType;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\CourseSection;
use App\Domain\Catalog\Models\Lesson;
use App\Domain\Identity\Models\Role;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

final class MarketplaceDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment([
            'local', 'development', 'testing'
        ])) {
            throw new RuntimeException(
                'Demo seeder hanya untuk development.'
            );
        }

        if (getenv('LMS_DEMO_SEED') !== 'CONFIRM_LOCAL_ONLY') {
            throw new RuntimeException(
                'Konfirmasi LMS_DEMO_SEED diperlukan.'
            );
        }

        $password = getenv('LMS_DEMO_PASSWORD');

        if (! is_string($password) || strlen($password) < 12) {
            throw new RuntimeException(
                'Password demo minimal 12 karakter.'
            );
        }

        $tables = [
            'users',
            'roles',
            'role_user',
            'instructor_profiles',
            'categories',
            'courses',
            'course_sections',
            'lessons'
        ];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                throw new RuntimeException(
                    "Migration tabel {$table} belum tersedia."
                );
            }
        }

        $raw = file_get_contents(
            __DIR__.'/marketplace-demo-data.json'
        );

        if ($raw === false) {
            throw new RuntimeException(
                'Dataset demo tidak ditemukan.'
            );
        }

        $data = json_decode(
            $raw,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        DB::transaction(function () use ($data, $password): void {

            // ROLES

            $roles = [];

            foreach ([
                'super_admin',
                'admin',
                'instructor',
                'student'
            ] as $name) {
                $roles[$name] = Role::query()->firstOrCreate([
                    'name' => $name
                ]);
            }

            // USERS

            $users = [];

            foreach ($data['users'] as $entry) {
                $email = $entry['email'];

                if (
                    ! str_ends_with($email, '@example.test')
                    || ! str_starts_with($entry['name'], '[DEMO] ')
                ) {
                    throw new RuntimeException(
                        'Identitas demo tidak valid.'
                    );
                }

                $user = User::query()
                    ->where('email', $email)
                    ->first();

                if ($user === null) {
                    $user = new User();
                    $user->name = $entry['name'];
                    $user->email = $email;
                    $user->password = Hash::make($password);
                    $user->email_verified_at = now();
                    $user->save();
                } elseif ($user->name !== $entry['name']) {
                    throw new RuntimeException(
                        "Email {$email} digunakan akun berbeda."
                    );
                }

                $user->roles()->syncWithoutDetaching([
                    $roles[$entry['role']]->id
                ]);

                $users[$email] = $user;
            }

            // INSTRUCTOR PROFILES

            $profiles = [];

            foreach ($data['instructors'] as $entry) {
                $user = $users[$entry['email']];

                $profile = InstructorProfile::query()
                    ->where('user_id', $user->id)
                    ->first();

                if (
                    $profile !== null
                    && $profile->slug !== $entry['slug']
                ) {
                    throw new RuntimeException(
                        'Konflik profil instruktur.'
                    );
                }

                if ($profile === null) {
                    $status = InstructorStatus::from(
                        $entry['status']
                    );

                    $profile = InstructorProfile::query()->create([
                        'user_id' => $user->id,
                        'slug' => $entry['slug'],
                        'headline' => $entry['headline'],
                        'bio' => $entry['bio'],
                        'status' => $status,
                        'approved_at' =>
                            $status === InstructorStatus::Approved
                            ? now()->subDays(28)
                            : null,
                    ]);
                }

                $profiles[$entry['email']] = $profile;
            }

            // CATEGORIES

            $categories = [];

            foreach ($data['categories'] as $entry) {
                $category = Category::query()
                    ->where('slug', $entry['slug'])
                    ->first();

                if (
                    $category !== null
                    && $category->name !== $entry['name']
                ) {
                    throw new RuntimeException(
                        'Konflik slug kategori.'
                    );
                }

                if ($category === null) {
                    $category = Category::query()->create([
                        'name' => $entry['name'],
                        'slug' => $entry['slug'],
                        'description' => $entry['description'],
                        'is_active' => true,
                        'sort_order' => $entry['sort_order'],
                    ]);
                }

                $categories[$entry['slug']] = $category;
            }

            // COURSES

            foreach ($data['courses'] as $entry) {
                $instructor = $profiles[
                    $entry['instructor_email']
                ];

                if (
                    $instructor->status
                    !== InstructorStatus::Approved
                ) {
                    throw new RuntimeException(
                        'Instruktur kursus belum approved.'
                    );
                }

                $existing = Course::withTrashed()
                    ->where('slug', $entry['slug'])
                    ->first();

                if (
                    $existing
                    && (
                        $existing->trashed()
                        || $existing->instructor_id
                           !== $instructor->id
                    )
                ) {
                    throw new RuntimeException(
                        'Konflik kursus: '.$entry['slug']
                    );
                }

                if ($existing) {
                    $course = $existing;
                } else {
                    $course = Course::query()->create([
                        'instructor_id' => $instructor->id,
                        'category_id' => $categories[
                            $entry['category_slug']
                        ]->id,
                        'title' => $entry['title'],
                        'slug' => $entry['slug'],
                        'summary' => $entry['summary'],
                        'description' =>
                            $entry['summary']
                            ."\n\nMateri ini merupakan data "
                            ."demonstrasi antarmuka LMS.",
                        'outcomes' => [
                            'Memahami konsep utama topik ini',
                            'Menerapkan konsep melalui latihan',
                            'Mengerjakan studi kasus sederhana',
                        ],
                        'requirements' => [
                            'Laptop dan koneksi internet',
                            'Kemauan belajar dan praktik',
                        ],
                        'audience' => [
                            'Pelajar, mahasiswa, dan profesional',
                        ],
                        'price_idr' => $entry['price_idr'],
                        'level' => CourseLevel::from(
                            $entry['level']
                        ),
                        'language' => 'id',
                        'status' => CourseStatus::from(
                            $entry['status']
                        ),
                        'thumbnail_url' =>
                            $entry['thumbnail_url'],
                        'total_duration_minutes' => 126,
                        'published_at' =>
                            $entry['status'] === 'published'
                            ? now()->subDays($entry['days_old'])
                            : null,
                    ]);
                }

                // COURSE SECTIONS AND LESSONS

                $sections = [
                    'Dasar dan orientasi',
                    'Latihan bertahap',
                    'Studi kasus dan rangkuman',
                ];

                foreach (
                    $sections as $sectionIndex => $sectionName
                ) {
                    $order = $sectionIndex + 1;

                    $section = CourseSection::query()
                        ->where('course_id', $course->id)
                        ->where('sort_order', $order)
                        ->first();

                    if ($section === null) {
                        $section = CourseSection::query()->create([
                            'course_id' => $course->id,
                            'title' =>
                                $sectionName.' — '.$entry['title'],
                            'description' =>
                                'Bagian '.$order
                                .' dari kurikulum demo.',
                            'sort_order' => $order,
                        ]);
                    }

                    $lessons = [
                        [
                            'Memahami tujuan dan konsep dasar',
                            LessonType::Article,
                            720,
                            true,
                        ],
                        [
                            'Demonstrasi penerapan konsep',
                            LessonType::Video,
                            1200,
                            false,
                        ],
                        [
                            'Checklist dan bahan latihan',
                            LessonType::Document,
                            600,
                            false,
                        ],
                    ];

                    foreach (
                        $lessons as $lessonIndex => [
                            $title, $type, $seconds, $preview
                        ]
                    ) {
                        $lessonOrder = $lessonIndex + 1;

                        $exists = Lesson::query()
                            ->where(
                                'course_section_id',
                                $section->id
                            )
                            ->where(
                                'sort_order',
                                $lessonOrder
                            )
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        Lesson::query()->create([
                            'course_section_id' => $section->id,
                            'title' =>
                                $title.' (Bagian '.$order.')',
                            'summary' =>
                                'Materi contoh untuk '
                                .$entry['title'],
                            'type' => $type,
                            'content' =>
                                $type === LessonType::Article
                                ? 'Materi demonstrasi. '
                                  .'Pelajari konsep dasar dan '
                                  .'kerjakan latihan sederhana.'
                                : null,
                            'duration_seconds' => $seconds,
                            'is_preview' => $preview,
                            'sort_order' => $lessonOrder,
                        ]);
                    }
                }
            }
        }, 3);

        $this->command?->info(
            'Demo selesai: 11 akun, 5 kategori, '
            .'14 kursus, 42 section, 126 lesson.'
        );

        $this->command?->warn(
            'Data sintetis. Belum termasuk kuis, '
            .'pembayaran, atau media video asli.'
        );
    }
}
