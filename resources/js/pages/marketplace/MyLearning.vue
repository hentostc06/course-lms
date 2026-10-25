<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowUpRight, BookOpen } from '@lucide/vue';

defineProps<{
    courses: Array<{
        slug: string;
        title: string;
        summary: string;
        thumbnailUrl: string | null;
        total: number;
        completed: number;
        firstLessonId: number | null;
    }>;
}>();
</script>

<template>
    <Head title="Kelas saya" />

    <main class="mx-auto max-w-market px-5 py-12 sm:px-8 lg:px-10 lg:py-16">
        <div class="mb-9 border-b border-market-border pb-7">
            <p class="text-xs font-bold uppercase tracking-widest text-market-primary">
                Ruang belajar
            </p>
            <h1 class="font-display mt-3 text-4xl font-black tracking-tight sm:text-5xl">
                Kelas saya
            </h1>
            <p class="mt-3 text-market-muted">
                Materi yang telah kamu ikuti dan progres artikel yang sudah diselesaikan.
            </p>
        </div>

        <div v-if="courses.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <article
                v-for="course in courses"
                :key="course.slug"
                class="overflow-hidden border border-market-border bg-market-surface"
            >
                <div class="aspect-video bg-market-sage">
                    <img
                        v-if="course.thumbnailUrl"
                        :src="course.thumbnailUrl"
                        :alt="course.title"
                        class="size-full object-cover"
                    />
                    <div v-else class="flex size-full items-center justify-center">
                        <BookOpen :size="40" />
                    </div>
                </div>

                <div class="p-6">
                    <h2 class="font-display text-xl font-bold leading-tight">
                        {{ course.title }}
                    </h2>
                    <p class="mt-2 line-clamp-2 text-sm text-market-muted">
                        {{ course.summary }}
                    </p>

                    <p class="mt-5 text-sm font-semibold">
                        {{ course.completed }} dari {{ course.total }} artikel selesai
                    </p>

                    <div
                        class="mt-2 h-2 overflow-hidden bg-market-border"
                        role="progressbar"
                        :aria-valuenow="course.completed"
                        :aria-valuemax="course.total || 1"
                        aria-valuemin="0"
                    >
                        <div
                            class="h-full bg-market-primary"
                            :style="{
                                width: `${course.total
                                    ? 100 * course.completed / course.total
                                    : 0}%`,
                            }"
                        />
                    </div>

                    <Link
                        v-if="course.firstLessonId"
                        :href="`/learn/${course.slug}/${course.firstLessonId}`"
                        class="mt-6 inline-flex min-h-11 items-center gap-2 bg-market-ink px-5 text-sm font-bold text-white"
                    >
                        Lanjut belajar
                        <ArrowUpRight :size="17" />
                    </Link>

                    <Link
                        v-else
                        :href="`/courses/${course.slug}`"
                        class="mt-6 inline-flex min-h-11 items-center gap-2 border border-market-ink px-5 text-sm font-bold"
                    >
                        Lihat kurikulum
                    </Link>
                </div>
            </article>
        </div>

        <div
            v-else
            class="border border-market-border bg-market-surface px-7 py-14 text-center"
        >
            <BookOpen :size="36" class="mx-auto text-market-primary" />
            <h2 class="font-display mt-5 text-2xl font-bold">
                Belum mengikuti kursus
            </h2>
            <p class="mt-2 text-market-muted">
                Mulai dengan memilih kursus gratis yang sudah diterbitkan.
            </p>
            <Link
                href="/courses?price=free"
                class="mt-6 inline-flex min-h-11 items-center bg-market-ink px-6 text-sm font-bold text-white"
            >
                Jelajahi kursus gratis
            </Link>
        </div>
    </main>
</template>
