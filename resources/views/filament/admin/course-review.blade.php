<div class="space-y-6">
    <x-filament::section :heading="$course->title" :description="$course->summary">
        <p class="whitespace-pre-wrap">{{ $course->description }}</p>
        <h3 class="mt-4 font-semibold">Hasil belajar</h3>
        <ul class="list-disc pl-5">@foreach($course->outcomes ?? [] as $outcome)<li>{{ $outcome }}</li>@endforeach</ul>
        <h3 class="mt-4 font-semibold">Prasyarat</h3>
        <ul class="list-disc pl-5">@forelse($course->requirements ?? [] as $requirement)<li>{{ $requirement }}</li>@empty<li>Tidak dicantumkan.</li>@endforelse</ul>
        @if($course->review_feedback)<p class="mt-4">Catatan terakhir: {{ $course->review_feedback }}</p>@endif
    </x-filament::section>
    @forelse($course->sections as $section)
        <x-filament::section :heading="$section->title">
            @forelse($section->lessons as $lesson)
                <article class="mb-4">
                    <h4 class="font-semibold">{{ $lesson->title }} · {{ $lesson->type->value }}</h4>
                    <p class="text-sm">{{ $lesson->duration_seconds }} detik · {{ $lesson->is_preview ? 'Pratinjau publik' : 'Materi privat' }}</p>
                    <p class="mt-2 whitespace-pre-wrap break-words">{{ $lesson->content ?: 'Konten belum dilengkapi.' }}</p>
                </article>
            @empty<p>Bagian belum berisi materi.</p>@endforelse
        </x-filament::section>
    @empty<p>Kurikulum belum tersedia.</p>@endforelse
</div>
