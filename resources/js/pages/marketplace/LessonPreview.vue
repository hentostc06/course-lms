<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MarketplaceLayout from '@/layouts/MarketplaceLayout.vue';

defineProps<{
    course: { slug: string; title: string };
    lesson: { title: string; content: string };
}>();
</script>

<template>
    <Head :title="`Pratinjau — ${lesson.title}`" />
    <MarketplaceLayout>
        <main class="mx-auto max-w-4xl px-5 py-12 sm:px-8">
            <Link
                :href="`/courses/${course.slug}`"
                class="text-sm font-semibold text-market-primary underline underline-offset-4"
                >← Kembali ke detail kursus</Link
            >
            <p
                class="mt-10 text-sm font-bold tracking-wider text-market-primary uppercase"
            >
                Pratinjau materi
            </p>
            <p class="mt-3 text-sm text-market-muted">{{ course.title }}</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight">
                {{ lesson.title }}
            </h1>
            <article
                class="mt-8 border border-market-border bg-market-surface p-6 leading-8 break-words whitespace-pre-wrap sm:p-10"
            >
                {{ lesson.content }}
            </article>
            <Link
                :href="`/courses/${course.slug}`"
                class="mt-8 inline-flex min-h-11 items-center bg-market-ink px-5 text-sm font-bold text-white"
                >Lihat detail kursus</Link
            >
        </main>
    </MarketplaceLayout>
</template>
