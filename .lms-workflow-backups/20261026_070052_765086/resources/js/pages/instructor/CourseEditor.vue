<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InstructorLayout from '@/layouts/InstructorLayout.vue';

type CourseDraft = {
    slug: string; title: string; category_id: number; summary: string; description: string;
    price_idr: number; level: string; language: string; outcomes: string[];
    requirements: string[]; audience: string[]; thumbnail_url: string | null; status: string; review_feedback: string | null;
};
const props = defineProps<{
    course: CourseDraft | null;
    categories: Array<{ id: number; name: string }>;
    levels: Array<{ value: string; label: string }>;
}>();
const form = useForm({
    title: props.course?.title ?? '',
    category_id: props.course?.category_id ?? 0,
    summary: props.course?.summary ?? '',
    description: props.course?.description ?? '',
    price_idr: props.course?.price_idr ?? 0,
    level: props.course?.level ?? 'beginner',
    language: props.course?.language ?? 'id',
    outcomes: props.course?.outcomes ?? [],
    requirements: props.course?.requirements ?? [],
    audience: props.course?.audience ?? [],
    thumbnail_url: props.course?.thumbnail_url ?? '',
});
const outcomesText = ref(form.outcomes.join('\n'));
const requirementsText = ref(form.requirements.join('\n'));
const audienceText = ref(form.audience.join('\n'));
const statusLabel = computed(() => props.course?.status === 'rejected' ? 'Perlu revisi' : 'Draf kursus');
const list = (value: string): string[] => value.split('\n').map(s => s.trim()).filter(Boolean);
function save(): void {
    form.outcomes = list(outcomesText.value);
    form.requirements = list(requirementsText.value);
    form.audience = list(audienceText.value);
    if (props.course) form.patch(`/instructor/courses/${props.course.slug}/edit`);
    else form.post('/instructor/courses');
}
</script>

<template>
    <Head :title="course ? 'Edit kursus' : 'Buat kursus'" />
    <InstructorLayout>
        <div class="mx-auto max-w-4xl">
            <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.1em] text-market-primary">{{ course ? statusLabel : 'Kursus baru' }}</p>
                    <h2 class="text-3xl font-bold tracking-[-0.04em]">{{ course ? 'Detail kursus' : 'Mulai dengan fondasi yang jelas.' }}</h2>
                    <p class="mt-2 text-sm text-market-muted">Rincian kursus yang dilihat peserta sebelum membeli. Pastikan informasi sesuai materi.</p>
                </div>
                <Link v-if="course" :href="`/instructor/courses/${course.slug}/curriculum`" class="inline-flex min-h-11 items-center justify-center rounded-market-sm border border-market-primary px-5 text-sm font-semibold text-market-primary">Kelola kurikulum →</Link>
            </div>
            <div v-if="course?.review_feedback" class="mb-6 rounded-market-md border border-amber-300 bg-amber-50 p-5 text-sm text-amber-950" role="alert">
                <p class="font-bold">Catatan moderator</p><p class="mt-2 whitespace-pre-line">{{ course.review_feedback }}</p>
            </div>
            <form class="space-y-7" @submit.prevent="save">
                <section class="rounded-market-md border border-market-border bg-market-surface p-6 sm:p-8">
                    <h3 class="mb-6 text-lg font-semibold">01 / Informasi utama</h3>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2"><label for="title" class="mb-2 block text-sm font-semibold">Judul kursus</label><input id="title" v-model="form.title" required minlength="8" maxlength="180" class="w-full rounded-market-sm border border-market-border p-3" placeholder="Contoh: Audit Internal ISO 9001 dari Dasar"/><p v-if="form.errors.title" class="mt-1 text-sm text-red-700">{{ form.errors.title }}</p></div>
                        <div><label for="category" class="mb-2 block text-sm font-semibold">Kategori</label><select id="category" v-model.number="form.category_id" required class="w-full rounded-market-sm border border-market-border p-3"><option :value="0" disabled>Pilih kategori</option><option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option></select><p v-if="form.errors.category_id" class="mt-1 text-sm text-red-700">{{ form.errors.category_id }}</p></div>
                        <div><label for="price" class="mb-2 block text-sm font-semibold">Harga (IDR)</label><input id="price" v-model.number="form.price_idr" type="number" min="0" max="50000000" required class="w-full rounded-market-sm border border-market-border p-3"/><p v-if="form.errors.price_idr" class="mt-1 text-sm text-red-700">{{ form.errors.price_idr }}</p></div>
                        <div><label for="level" class="mb-2 block text-sm font-semibold">Tingkat</label><select id="level" v-model="form.level" class="w-full rounded-market-sm border border-market-border p-3"><option v-for="level in levels" :key="level.value" :value="level.value">{{ level.label }}</option></select></div>
                        <div><label for="language" class="mb-2 block text-sm font-semibold">Bahasa</label><select id="language" v-model="form.language" class="w-full rounded-market-sm border border-market-border p-3"><option value="id">Bahasa Indonesia</option><option value="en">English</option></select></div>
                        <div class="sm:col-span-2"><label for="summary" class="mb-2 block text-sm font-semibold">Ringkasan singkat</label><textarea id="summary" v-model="form.summary" required minlength="20" maxlength="320" rows="3" class="w-full rounded-market-sm border border-market-border p-3"/><p v-if="form.errors.summary" class="mt-1 text-sm text-red-700">{{ form.errors.summary }}</p></div>
                        <div class="sm:col-span-2"><label for="description" class="mb-2 block text-sm font-semibold">Deskripsi lengkap</label><textarea id="description" v-model="form.description" required minlength="50" maxlength="20000" rows="7" class="w-full rounded-market-sm border border-market-border p-3"/><p v-if="form.errors.description" class="mt-1 text-sm text-red-700">{{ form.errors.description }}</p></div>
                        <div class="sm:col-span-2"><label for="thumbnail" class="mb-2 block text-sm font-semibold">URL thumbnail (sementara)</label><input id="thumbnail" v-model="form.thumbnail_url" type="url" class="w-full rounded-market-sm border border-market-border p-3" placeholder="https://..."/><p class="mt-1 text-xs text-market-muted">Fitur upload media privat akan dipasang pada milestone tersendiri.</p></div>
                    </div>
                </section>
                <section class="rounded-market-md border border-market-border bg-market-surface p-6 sm:p-8">
                    <h3 class="mb-6 text-lg font-semibold">02 / Hasil pembelajaran</h3>
                    <div class="space-y-5">
                        <div><label for="outcomes" class="mb-2 block text-sm font-semibold">Capaian pembelajaran (satu per baris)</label><textarea id="outcomes" v-model="outcomesText" rows="5" required class="w-full rounded-market-sm border border-market-border p-3" placeholder="Peserta mampu menyusun program audit internal..."/><p v-if="form.errors.outcomes" class="mt-1 text-sm text-red-700">{{ form.errors.outcomes }}</p></div>
                        <div><label for="requirements" class="mb-2 block text-sm font-semibold">Prasyarat</label><textarea id="requirements" v-model="requirementsText" rows="3" class="w-full rounded-market-sm border border-market-border p-3"/></div>
                        <div><label for="audience" class="mb-2 block text-sm font-semibold">Target peserta</label><textarea id="audience" v-model="audienceText" rows="3" class="w-full rounded-market-sm border border-market-border p-3"/></div>
                    </div>
                </section>
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"><Link href="/instructor" class="inline-flex min-h-12 items-center justify-center rounded-market-sm border border-market-border px-5 font-semibold">Kembali</Link><button type="submit" :disabled="form.processing" class="min-h-12 rounded-market-sm bg-market-primary px-6 font-semibold text-white disabled:opacity-50">{{ form.processing ? 'Menyimpan...' : 'Simpan draf kursus' }}</button></div>
            </form>
        </div>
    </InstructorLayout>
</template>
