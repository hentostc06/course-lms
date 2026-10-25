# 15 — Implementasi pada Salinan `course.zip` (2026-10-25)

## Sudah tersedia di source awal

- Laravel 13.38.0 + Inertia + Vue 3 + Filament 5.
- Model `Role`, `InstructorProfile`, `Category`, `Course`, `CourseSection`, dan `Lesson`.
- Katalog publik, filter, detail kursus, policy dasar, factories serta tes katalog.

## Ditambahkan pada milestone ini

- Pengajuan instruktur dan layar status `GET/POST /instructor/onboarding`.
- Verifikasi instruktur oleh admin, dengan `instructor_review_events`.
- Manajemen kategori awal oleh admin.
- Studio instruktur: daftar kursus, create/edit draft, tambah section dan lesson.
- Workflow submit review; admin approve/reject dengan catatan dan `course_review_events`.
- Pembatasan akses backend per instruktur dan proteksi panel Filament untuk role `admin`.
- Halaman Vue responsif yang memakai palet/tokens desain marketplace yang sudah ada.
- Feature tests untuk role isolation, draft workflow, moderasi, verifikasi instruktur, audit trail.

## Sudah diverifikasi secara lokal

- Sintaks PHP file yang dibuat/diubah: `php -l` lulus.
- Vue TypeScript: `vue-tsc --noEmit` lulus.
- PHPUnit belum dapat dijalankan pada container ini: PHP CLI tidak menyediakan ekstensi `dom`, `mbstring`, `xmlwriter`, dan `pdo_sqlite`.
- Build Vite juga belum bisa diverifikasi: plugin Wayfinder membutuhkan eksekusi Artisan dengan ekstensi PHP tersebut.
- **Belum menjalankan migrasi**, belum mengubah `.env`, belum mengubah PostgreSQL/SQLite, belum deploy ke server.

## Belum selesai (jangan klaim siap produksi)

- Video upload Cloudflare Stream/R2, lesson player, lesson editing/reorder, enrollment, kuis, sertifikat.
- Payment, split commission, payout, refund, reconciliation, live class.
- Filament moderation resource masih berupa halaman moderasi Laravel terproteksi role admin; UI tersebut belum dipindah ke resource Filament.
- Instruktur suspended/rejected case, editable sections/lessons, dan handling audit history lanjutan.
- Uji PHPUnit, build bundle, UAT, load/security testing di server pengembangan.

## Catatan keamanan dan deployment

- Aplikasi pada arsip asli masih menggunakan SQLite berdasarkan audit yang diberikan sebelumnya. Template config PostgreSQL tidak sama dengan koneksi PostgreSQL aktif.
- Jalankan migration **hanya pada database pengembangan yang sudah dibackup dan disetujui**.
- Jangan pernah mengekstrak archive ke server production tanpa diff/backup.
- File `.env`, database SQLite, vendor, node_modules dan cache runtime tidak disertakan dalam paket source code hasil edit.
