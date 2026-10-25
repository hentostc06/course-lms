<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight, Clock3 } from '@lucide/vue';
import { formatDuration, formatIdr } from '@/lib/catalog';
import type { CatalogCourse } from '@/types/catalog';

defineProps<{ course: CatalogCourse }>();
</script>

<template>
    <article
        class="group flex h-full flex-col border-b border-market-border pb-6"
    >
        <Link
            :href="`/courses/${course.slug}`"
            class="relative mb-4 block aspect-video overflow-hidden rounded-market-md bg-market-sage"
        >
            <img
                v-if="course.thumbnailUrl"
                :src="course.thumbnailUrl"
                :alt="`Sampul kursus ${course.title}`"
                class="size-full object-cover transition duration-180 group-hover:scale-[1.02] motion-reduce:transition-none"
                loading="lazy"
            />
            <div v-else class="flex size-full items-end p-5">
                <span
                    class="max-w-[15rem] text-lg leading-snug font-semibold text-market-primary"
                    >{{ course.category }}</span
                >
            </div>
            <span
                class="absolute top-3 left-3 rounded-market-sm bg-market-surface px-2.5 py-1 text-xs font-semibold text-market-ink shadow-sm"
            >
                {{ course.level }}
            </span>
        </Link>

        <div class="flex flex-1 flex-col">
            <p
                class="mb-2 text-xs font-semibold tracking-wide text-market-primary"
            >
                {{ course.category }}
            </p>
            <h2 class="text-lg leading-snug font-semibold tracking-[-0.015em]">
                <Link
                    :href="`/courses/${course.slug}`"
                    class="hover:text-market-primary"
                >
                    {{ course.title }}
                </Link>
            </h2>
            <p class="mt-2 line-clamp-2 text-sm leading-6 text-market-muted">
                {{ course.summary }}
            </p>
            <p class="mt-3 text-sm text-market-muted">
                Oleh
                <span class="font-medium text-market-ink">{{
                    course.instructor
                }}</span>
            </p>

            <div class="mt-auto flex items-end justify-between gap-4 pt-5">
                <div>
                    <p class="text-lg font-bold tracking-[-0.02em]">
                        {{ formatIdr(course.priceIdr) }}
                    </p>
                    <p
                        class="mt-1 flex items-center gap-1.5 text-xs text-market-muted"
                    >
                        <Clock3 :size="14" aria-hidden="true" />
                        {{ formatDuration(course.durationMinutes) }}
                    </p>
                </div>
                <Link
                    :href="`/courses/${course.slug}`"
                    class="grid size-11 place-items-center rounded-full border border-market-border transition-colors duration-150 group-hover:border-market-primary group-hover:bg-market-primary group-hover:text-white motion-reduce:transition-none"
                    :aria-label="`Lihat detail ${course.title}`"
                >
                    <ArrowUpRight :size="18" aria-hidden="true" />
                </Link>
            </div>
        </div>
    </article>
</template>
