@php
    $raw = trim((string) ($course->thumbnail_url ?? ''));

    $isLocal = str_starts_with($raw, '/')
        && ! str_starts_with($raw, '//');

    $validUrl = $isLocal
        || preg_match('~^https?://~i', $raw) === 1;

    $relative = $isLocal
        ? ltrim((string) parse_url($raw, PHP_URL_PATH), '/')
        : '';

    $exists = $validUrl && (
        ! $isLocal || (
            $relative !== ''
            && is_file(public_path($relative))
        )
    );
@endphp

<div class="mb-2 rounded-xl border border-gray-200 p-4 dark:border-gray-700">

    <p class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">
        Cover saat ini
    </p>

    @if ($exists)
        <img
            src="{{ $raw }}"
            alt="Cover kursus {{ $course->title }}"
            class="h-auto max-h-52 w-full rounded-lg object-contain"
            style="aspect-ratio: 16 / 9; background: #252422;"
        />

        <p class="mt-2 text-xs text-gray-500">
            Ini adalah cover yang tersimpan saat ini.
            Pilih file baru di bawah jika ingin menggantinya.
        </p>

    @elseif ($raw !== '')
        <div class="rounded-lg border border-amber-400/40 bg-amber-500/10 p-3 text-sm text-amber-700 dark:text-amber-300">
            Alamat cover sudah tercatat di database,
            tetapi file lokal tidak ditemukan atau alamat tidak valid.
            Silakan upload cover baru.
        </div>

    @else
        <p class="text-sm text-gray-500">
            Belum ada gambar cover untuk kursus ini.
        </p>
    @endif

</div>
