<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import InstructorLayout from '@/layouts/InstructorLayout.vue';

type Lesson = {
    id: number;
    title: string;
    type: string;
    durationSeconds: number;
    isPreview: boolean;
    content: string | null;
};
type Section = { id: number; title: string; lessons: Lesson[] };
const props = defineProps<{
    course: {
        slug: string;
        title: string;
        status: string;
        sections: Section[];
    };
    lessonTypes: string[];
}>();
const section = useForm({ title: '' });
const activeSection = ref<number | null>(null);
const lesson = useForm({
    title: '',
    type: 'article',
    content: '',
    duration_seconds: 0,
    is_preview: false,
});
const editLessonId = ref<number | null>(null);
const editing = useForm({
    title: '',
    type: 'article',
    content: '',
    duration_seconds: 0,
    is_preview: false,
});
function startEdit(entry: Lesson): void {
    editLessonId.value = entry.id;
    editing.title = entry.title;
    editing.type = 'article';
    editing.content = entry.content ?? '';
    editing.duration_seconds = entry.durationSeconds;
    editing.is_preview = entry.isPreview;
    editing.clearErrors();
}
function saveEdit(sectionId: number, lessonId: number): void {
    editing.patch(
        `/instructor/courses/${props.course.slug}/curriculum/sections/${sectionId}/lessons/${lessonId}`,
        {
            preserveScroll: true,
            onSuccess: () => {
                editLessonId.value = null;
                editing.reset();
            },
        },
    );
}
function removeLesson(sectionId: number, lessonId: number): void {
    if (
        !window.confirm(
            'Hapus materi ini dari draf? Tindakan tidak dapat dibatalkan.',
        )
    )
        return;
    router.delete(
        `/instructor/courses/${props.course.slug}/curriculum/sections/${sectionId}/lessons/${lessonId}`,
        { preserveScroll: true },
    );
}
const label: Record<string, string> = {
    video: 'Video',
    article: 'Artikel',
    document: 'Dokumen',
};
function addSection(): void {
    section.post(
        `/instructor/courses/${props.course.slug}/curriculum/sections`,
        { preserveScroll: true, onSuccess: () => section.reset() },
    );
}
function addLesson(id: number): void {
    lesson.post(
        `/instructor/courses/${props.course.slug}/curriculum/sections/${id}/lessons`,
        {
            preserveScroll: true,
            onSuccess: () => {
                lesson.reset();
                activeSection.value = null;
            },
        },
    );
}
function submitCourse(): void {
    if (
        !window.confirm(
            'Ajukan kursus ke admin? Setelah dikirim, draf tidak dapat diedit sampai proses review selesai.',
        )
    )
        return;
    router.post(`/instructor/courses/${props.course.slug}/submit`);
}
</script>

<template>
    <Head :title="`Kurikulum — ${course.title}`" />
    <InstructorLayout>
        <div class="mx-auto max-w-4xl">
            <div
                class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
            >
                <div>
                    <p
                        class="mb-2 text-xs font-bold tracking-wider text-market-primary uppercase"
                    >
                        Susun materi
                    </p>
                    <h2 class="text-3xl font-bold tracking-[-0.04em]">
                        {{ course.title }}
                    </h2>
                    <p class="mt-2 text-sm text-market-muted">
                        Urutkan pembelajaran berdasarkan kompetensi yang harus
                        dicapai.
                    </p>
                </div>
                <Link
                    :href="`/instructor/courses/${course.slug}/edit`"
                    class="inline-flex min-h-11 items-center justify-center rounded-market-sm border border-market-border px-4 text-sm font-semibold"
                    >← Detail kursus</Link
                >
            </div>
            <section
                class="mb-8 rounded-market-md border border-market-border bg-market-surface p-6"
            >
                <h3 class="mb-4 text-lg font-semibold">
                    Tambah bagian kurikulum
                </h3>
                <form
                    class="flex flex-col gap-3 sm:flex-row"
                    @submit.prevent="addSection"
                >
                    <div class="flex-1">
                        <label for="section-title" class="sr-only"
                            >Judul bagian</label
                        ><input
                            id="section-title"
                            v-model="section.title"
                            minlength="3"
                            maxlength="180"
                            required
                            class="min-h-12 w-full rounded-market-sm border border-market-border px-4"
                            placeholder="Mis. Dasar-dasar audit internal"
                        />
                        <p
                            v-if="section.errors.title"
                            class="mt-1 text-sm text-red-700"
                        >
                            {{ section.errors.title }}
                        </p>
                    </div>
                    <button
                        :disabled="section.processing"
                        class="min-h-12 rounded-market-sm bg-market-primary px-5 font-semibold text-white disabled:opacity-50"
                    >
                        Tambah bagian
                    </button>
                </form>
            </section>
            <div
                v-if="course.sections.length === 0"
                class="rounded-market-md border border-dashed border-market-border p-10 text-center text-market-muted"
            >
                Belum ada bagian kurikulum. Mulai dengan satu topik yang jelas.
            </div>
            <div
                v-for="(item, index) in course.sections"
                :key="item.id"
                class="mb-5 rounded-market-md border border-market-border bg-market-surface p-6"
            >
                <p
                    class="text-xs font-bold tracking-wider text-market-primary uppercase"
                >
                    Bagian {{ String(index + 1).padStart(2, '0') }}
                </p>
                <h3 class="mt-2 text-xl font-semibold">{{ item.title }}</h3>
                <ul class="mt-5 divide-y divide-market-border">
                    <li
                        v-for="entry in item.lessons"
                        :key="entry.id"
                        class="flex flex-wrap items-center justify-between gap-3 py-3 text-sm"
                    >
                        <span class="font-medium">{{ entry.title }}</span>
                        <span class="text-market-muted"
                            >{{ label[entry.type] ?? entry.type }} ·
                            {{ Math.ceil(entry.durationSeconds / 60) }} menit
                            <span v-if="entry.isPreview">· Preview</span></span
                        >
                        <div class="flex gap-4">
                            <button
                                type="button"
                                class="font-semibold text-market-primary underline underline-offset-4"
                                @click="startEdit(entry)"
                            >
                                Edit materi
                            </button>
                            <button
                                type="button"
                                class="font-semibold text-red-700 underline underline-offset-4"
                                @click="removeLesson(item.id, entry.id)"
                            >
                                Hapus
                            </button>
                        </div>
                        <form
                            v-if="editLessonId === entry.id"
                            class="grid w-full gap-3 rounded-market-sm border border-market-border p-4"
                            @submit.prevent="saveEdit(item.id, entry.id)"
                        >
                            <label class="font-semibold"
                                >Judul materi<input
                                    v-model="editing.title"
                                    required
                                    minlength="3"
                                    maxlength="180"
                                    class="mt-1 w-full rounded-market-sm border border-market-border p-3"
                            /></label>
                            <label class="font-semibold"
                                >Isi artikel<textarea
                                    v-model="editing.content"
                                    required
                                    rows="7"
                                    class="mt-1 w-full rounded-market-sm border border-market-border p-3"
                                />
                            </label>
                            <label class="font-semibold"
                                >Durasi (detik)<input
                                    v-model.number="editing.duration_seconds"
                                    type="number"
                                    min="0"
                                    max="86400"
                                    required
                                    class="mt-1 w-full rounded-market-sm border border-market-border p-3"
                            /></label>
                            <label class="flex items-center gap-2"
                                ><input
                                    v-model="editing.is_preview"
                                    type="checkbox"
                                />
                                Preview</label
                            >
                            <p
                                v-for="(error, key) in editing.errors"
                                :key="key"
                                class="text-red-700"
                            >
                                {{ error }}
                            </p>
                            <div class="flex gap-3">
                                <button
                                    :disabled="editing.processing"
                                    class="rounded-market-sm bg-market-ink px-5 py-3 font-semibold text-white disabled:opacity-50"
                                >
                                    Simpan perubahan</button
                                ><button
                                    type="button"
                                    class="rounded-market-sm border border-market-border px-5 py-3"
                                    @click="editLessonId = null"
                                >
                                    Batal
                                </button>
                            </div>
                        </form>
                    </li>
                </ul>
                <button
                    type="button"
                    class="mt-4 text-sm font-semibold text-market-primary underline underline-offset-4"
                    @click="
                        activeSection =
                            activeSection === item.id ? null : item.id
                    "
                >
                    {{
                        activeSection === item.id
                            ? 'Tutup form'
                            : '+ Tambah materi'
                    }}
                </button>
                <form
                    v-if="activeSection === item.id"
                    class="mt-5 grid gap-4 border-t border-market-border pt-5 sm:grid-cols-2"
                    @submit.prevent="addLesson(item.id)"
                >
                    <div class="sm:col-span-2">
                        <label
                            :for="`lesson-title-${item.id}`"
                            class="mb-1 block text-sm font-semibold"
                            >Judul materi</label
                        ><input
                            :id="`lesson-title-${item.id}`"
                            v-model="lesson.title"
                            minlength="3"
                            maxlength="180"
                            required
                            class="w-full rounded-market-sm border border-market-border p-3"
                        />
                    </div>
                    <div>
                        <label
                            :for="`lesson-type-${item.id}`"
                            class="mb-1 block text-sm font-semibold"
                            >Jenis materi</label
                        ><select
                            :id="`lesson-type-${item.id}`"
                            v-model="lesson.type"
                            class="w-full rounded-market-sm border border-market-border p-3"
                        >
                            <option
                                v-for="type in lessonTypes"
                                :key="type"
                                :value="type"
                            >
                                {{ label[type] ?? type }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            :for="`lesson-duration-${item.id}`"
                            class="mb-1 block text-sm font-semibold"
                            >Durasi (detik)</label
                        ><input
                            :id="`lesson-duration-${item.id}`"
                            v-model.number="lesson.duration_seconds"
                            type="number"
                            min="0"
                            max="86400"
                            required
                            class="w-full rounded-market-sm border border-market-border p-3"
                        />
                    </div>
                    <div class="sm:col-span-2">
                        <label
                            :for="`lesson-content-${item.id}`"
                            class="mb-1 block text-sm font-semibold"
                            >Isi artikel pembelajaran</label
                        ><textarea
                            :id="`lesson-content-${item.id}`"
                            v-model="lesson.content"
                            required
                            rows="6"
                            class="w-full rounded-market-sm border border-market-border p-3"
                        />
                        <p class="mt-1 text-xs text-market-muted">
                            Untuk saat ini hanya artikel teks yang bisa
                            dipelajari siswa. Video/dokumen menunggu integrasi
                            penyimpanan privat dan player.
                        </p>
                    </div>
                    <label class="flex items-center gap-2 text-sm"
                        ><input v-model="lesson.is_preview" type="checkbox" />
                        Tampilkan sebagai preview</label
                    >
                    <div
                        class="sm:col-span-2"
                        v-if="Object.keys(lesson.errors).length"
                    >
                        <p
                            v-for="(error, key) in lesson.errors"
                            :key="key"
                            class="text-sm text-red-700"
                        >
                            {{ error }}
                        </p>
                    </div>
                    <div class="sm:col-span-2">
                        <button
                            :disabled="lesson.processing"
                            type="submit"
                            class="min-h-11 rounded-market-sm bg-market-primary px-5 font-semibold text-white"
                        >
                            Simpan materi
                        </button>
                    </div>
                </form>
            </div>
            <div
                v-if="['draft', 'rejected'].includes(course.status)"
                class="mt-8 border-t border-market-border pt-6"
            >
                <p class="mb-4 text-sm text-market-muted">
                    Pastikan informasi dan urutan materi sudah benar. Setelah
                    diajukan, perubahan hanya dapat dilakukan jika admin meminta
                    revisi.
                </p>
                <button
                    type="button"
                    class="min-h-12 rounded-market-sm bg-market-ink px-6 font-semibold text-white disabled:opacity-50"
                    :disabled="
                        course.sections.every((item) => !item.lessons.length)
                    "
                    @click="submitCourse"
                >
                    Ajukan untuk review →
                </button>
            </div>
        </div>
    </InstructorLayout>
</template>
