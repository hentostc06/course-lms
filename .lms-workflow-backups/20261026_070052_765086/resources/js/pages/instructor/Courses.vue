<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowUpRight, BookOpen, Plus } from '@lucide/vue';
import InstructorLayout from '@/layouts/InstructorLayout.vue';
import { formatIdr } from '@/lib/catalog';

defineProps<{
    isApproved: boolean;
    courses: Array<{ slug: string; title: string; status: string; priceIdr: number; updatedAt: string | null }>;
}>();
const statusLabels: Record<string, string> = {
    draft: 'Draf', pending_review: 'Menunggu review', published: 'Terbit', rejected: 'Perlu revisi', archived: 'Diarsipkan',
};
</script>

<template>
    <Head title="Kursus saya" />
    <InstructorLayout>
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Kursus saya</h2>
                <p class="mt-2 text-sm text-market-muted">Susun konten, kelola status publikasi, dan tinjau materi.</p>
            </div>
            <Link v-if="isApproved" href="/instructor/courses/create" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-market-sm bg-market-primary px-5 text-sm font-semibold text-white hover:bg-market-primary-hover"><Plus :size="17" /> Buat kursus</Link>
        </div>
        <div v-if="!isApproved" class="rounded-market-md border border-market-border bg-market-sage p-6 text-sm leading-7">
            Akun instruktur harus diverifikasi sebelum bisa membuat kursus.
            <Link class="ml-1 font-bold underline underline-offset-4" href="/instructor/onboarding">Lihat status pengajuan</Link>
        </div>
        <div v-if="courses.length === 0" class="mt-6 rounded-market-md border border-dashed border-market-border bg-market-surface px-6 py-16 text-center">
            <BookOpen :size="32" class="mx-auto mb-4 text-market-primary" />
            <h3 class="text-xl font-semibold">Belum ada kursus</h3>
            <p class="mx-auto mt-2 max-w-md text-sm text-market-muted">Mulai dari kompetensi yang ingin dicapai peserta, kemudian susun silabus dan materi secara bertahap.</p>
        </div>
        <div v-else class="overflow-hidden rounded-market-md border border-market-border bg-market-surface">
            <div v-for="course in courses" :key="course.slug" class="flex flex-col gap-4 border-b border-market-border p-5 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.08em] text-market-primary">{{ statusLabels[course.status] ?? course.status }}</p>
                    <h3 class="font-semibold">{{ course.title }}</h3>
                    <p class="mt-2 text-xs text-market-muted">{{ formatIdr(course.priceIdr) }} · Diperbarui {{ course.updatedAt ?? '—' }}</p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-2 text-sm">
                    <Link v-if="['draft', 'rejected'].includes(course.status)" :href="`/instructor/courses/${course.slug}/edit`" class="inline-flex min-h-11 items-center gap-2 rounded-market-sm border border-market-border px-4 font-semibold hover:bg-market-sage">Edit kursus <ArrowUpRight :size="15" /></Link>
                    <Link v-if="course.status === 'published'" :href="`/courses/${course.slug}`" class="inline-flex min-h-11 items-center gap-2 rounded-market-sm border border-market-border px-4 font-semibold">Lihat publik →</Link>
                </div>
            </div>
        </div>
    </InstructorLayout>
</template>
