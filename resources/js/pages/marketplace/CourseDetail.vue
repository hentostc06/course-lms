<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { BookOpen, Check, Clock3, Globe2, LockKeyhole } from '@lucide/vue';
import { formatDuration, formatIdr, formatLessonDuration } from '@/lib/catalog';
import type { CourseDetail } from '@/types/catalog';

const props = defineProps<{
    course: CourseDetail;
    isAuthenticated: boolean;
    canEnroll: boolean;
    enrolled: boolean;
}>();

function enroll(): void {
    router.post(`/courses/${props.course.slug}/enroll`);
}

const lessonCount = computed(() =>
    props.course.sections.reduce(
        (total, section) => total + section.lessons.length,
        0,
    ),
);

const lessonType: Record<string, string> = {
    video: 'Video',
    article: 'Artikel',
    document: 'Dokumen',
};
</script>

<template>
    <Head :title="course.title">
        <meta name="description" :content="course.summary" />
    </Head>

    <section class="bg-market-ink text-white">
        <div class="mx-auto max-w-market px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
            <nav class="mb-6 text-sm text-white/65" aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-2">
                    <li>
                        <Link href="/courses" class="hover:text-white"
                            >Katalog</Link
                        >
                    </li>
                    <li aria-hidden="true">/</li>
                    <li>
                        <Link
                            :href="`/courses?category=${course.categorySlug}`"
                            class="hover:text-white"
                            >{{ course.category }}</Link
                        >
                    </li>
                </ol>
            </nav>

            <div class="grid gap-10 lg:grid-cols-12 lg:items-start">
                <div class="lg:col-span-8">
                    <p class="mb-3 text-sm font-semibold text-market-accent">
                        {{ course.category }} · {{ course.level }}
                    </p>
                    <h1
                        class="max-w-4xl text-3xl leading-tight font-bold tracking-[-0.04em] sm:text-5xl"
                    >
                        {{ course.title }}
                    </h1>
                    <p class="mt-5 max-w-3xl text-lg leading-8 text-white/75">
                        {{ course.summary }}
                    </p>
                    <p class="mt-6 text-sm text-white/70">
                        Disusun oleh
                        <strong class="text-white">{{
                            course.instructor
                        }}</strong>
                    </p>
                    <dl
                        class="mt-7 flex flex-wrap gap-x-6 gap-y-3 text-sm text-white/75"
                    >
                        <div class="flex items-center gap-2">
                            <Clock3 :size="17" aria-hidden="true" />
                            <dt class="sr-only">Durasi</dt>
                            <dd>
                                {{ formatDuration(course.durationMinutes) }}
                            </dd>
                        </div>
                        <div class="flex items-center gap-2">
                            <BookOpen :size="17" aria-hidden="true" />
                            <dt class="sr-only">Materi</dt>
                            <dd>{{ lessonCount }} materi</dd>
                        </div>
                        <div class="flex items-center gap-2">
                            <Globe2 :size="17" aria-hidden="true" />
                            <dt class="sr-only">Bahasa</dt>
                            <dd>{{ course.language }}</dd>
                        </div>
                    </dl>
                </div>

                <aside
                    class="lg:col-span-4 lg:row-span-2"
                    aria-label="Informasi pendaftaran"
                >
                    <div
                        class="rounded-market-md bg-market-surface p-5 text-market-ink shadow-[0_20px_50px_rgba(0,0,0,0.18)] lg:sticky lg:top-6"
                    >
                        <div
                            class="aspect-video overflow-hidden rounded-market-sm bg-market-sage"
                        >
                            <img
                                v-if="course.thumbnailUrl"
                                :src="course.thumbnailUrl"
                                :alt="`Sampul kursus ${course.title}`"
                                class="size-full object-cover"
                            />
                            <div
                                v-else
                                class="flex size-full items-end p-5 text-lg font-semibold text-market-primary"
                            >
                                {{ course.category }}
                            </div>
                        </div>
                        <p class="mt-5 text-2xl font-bold tracking-[-0.025em]">
                            {{ formatIdr(course.priceIdr) }}
                        </p>
                        <Link
                            v-if="enrolled"
                            href="/my-learning"
                            class="mt-4 flex min-h-12 w-full items-center justify-center rounded-market-sm bg-market-ink px-4 text-sm font-bold text-white hover:bg-market-primary"
                        >
                            Lanjutkan belajar
                        </Link>
                        <Link
                            v-else-if="
                                course.priceIdr === 0 && !isAuthenticated
                            "
                            :href="`/courses/${course.slug}/join`"
                            class="mt-4 flex min-h-12 w-full items-center justify-center rounded-market-sm bg-market-ink px-4 text-sm font-bold text-white hover:bg-market-primary"
                        >
                            Masuk untuk mengikuti kursus
                        </Link>
                        <button
                            v-else-if="course.priceIdr === 0 && canEnroll"
                            type="button"
                            class="mt-4 min-h-12 w-full rounded-market-sm bg-market-ink px-4 text-sm font-bold text-white hover:bg-market-primary"
                            @click="enroll"
                        >
                            Ikuti kursus gratis
                        </button>
                        <button
                            v-else
                            type="button"
                            disabled
                            class="mt-4 min-h-12 w-full cursor-not-allowed rounded-market-sm bg-market-muted px-4 text-sm font-semibold text-white opacity-80"
                        >
                            {{
                                course.priceIdr === 0
                                    ? 'Hanya akun siswa yang dapat mengikuti kursus'
                                    : 'Pembelian belum tersedia'
                            }}
                        </button>
                        <p
                            v-if="course.priceIdr > 0"
                            class="mt-3 flex gap-2 text-xs leading-5 text-market-muted"
                        >
                            <LockKeyhole
                                :size="15"
                                class="mt-0.5 shrink-0"
                                aria-hidden="true"
                            />
                            Pembelian akan tersedia setelah milestone commerce
                            selesai.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <div
        class="mx-auto grid max-w-market gap-12 px-4 py-10 sm:px-6 sm:py-14 lg:grid-cols-12 lg:px-8"
    >
        <div class="space-y-12 lg:col-span-8">
            <section
                class="rounded-market-md bg-market-sage p-6 sm:p-8"
                aria-labelledby="outcomes-heading"
            >
                <h2
                    id="outcomes-heading"
                    class="text-2xl font-bold tracking-[-0.025em]"
                >
                    Yang akan Anda pelajari
                </h2>
                <ul
                    v-if="course.outcomes.length"
                    class="mt-5 grid gap-4 sm:grid-cols-2"
                >
                    <li
                        v-for="outcome in course.outcomes"
                        :key="outcome"
                        class="flex gap-3 text-sm leading-6"
                    >
                        <Check
                            :size="18"
                            class="mt-0.5 shrink-0 text-market-primary"
                            aria-hidden="true"
                        />
                        <span>{{ outcome }}</span>
                    </li>
                </ul>
                <p v-else class="mt-4 text-sm text-market-muted">
                    Hasil belajar belum dirinci.
                </p>
            </section>

            <section aria-labelledby="description-heading">
                <h2
                    id="description-heading"
                    class="text-2xl font-bold tracking-[-0.025em]"
                >
                    Tentang kursus
                </h2>
                <p class="mt-4 leading-7 whitespace-pre-line text-market-muted">
                    {{ course.description }}
                </p>
            </section>

            <section aria-labelledby="curriculum-heading">
                <div
                    class="flex flex-wrap items-end justify-between gap-3 border-b-2 border-market-ink pb-4"
                >
                    <h2
                        id="curriculum-heading"
                        class="text-2xl font-bold tracking-[-0.025em]"
                    >
                        Kurikulum
                    </h2>
                    <p class="text-sm text-market-muted">
                        {{ course.sections.length }} bagian ·
                        {{ lessonCount }} materi
                    </p>
                </div>

                <div
                    v-if="course.sections.length"
                    class="divide-y divide-market-border border-b border-market-border"
                >
                    <details
                        v-for="(section, index) in course.sections"
                        :key="section.id"
                        :open="index === 0"
                        class="group"
                    >
                        <summary
                            class="flex min-h-16 cursor-pointer list-none items-center justify-between gap-4 py-4 font-semibold"
                        >
                            <span>{{ section.title }}</span>
                            <span class="text-sm font-normal text-market-muted"
                                >{{ section.lessons.length }} materi</span
                            >
                        </summary>
                        <div class="pb-5">
                            <p
                                v-if="section.description"
                                class="mb-4 text-sm leading-6 text-market-muted"
                            >
                                {{ section.description }}
                            </p>
                            <ol class="space-y-1">
                                <li
                                    v-for="lesson in section.lessons"
                                    :key="lesson.id"
                                    class="flex min-h-11 items-center gap-3 rounded-market-sm px-3 py-2 text-sm even:bg-market-canvas"
                                >
                                    <span
                                        class="size-1.5 shrink-0 rounded-full bg-market-primary"
                                        aria-hidden="true"
                                    />
                                    <span class="min-w-0 flex-1">{{
                                        lesson.title
                                    }}</span>
                                    <Link
                                        v-if="lesson.isPreview"
                                        :href="`/courses/${course.slug}/preview/${lesson.id}`"
                                        class="text-xs font-semibold text-market-primary underline underline-offset-4"
                                        >Baca pratinjau</Link
                                    >
                                    <span
                                        class="hidden text-xs text-market-muted sm:inline"
                                        >{{ lessonType[lesson.type] }} ·
                                        {{
                                            formatLessonDuration(
                                                lesson.durationSeconds,
                                            )
                                        }}</span
                                    >
                                </li>
                            </ol>
                        </div>
                    </details>
                </div>
                <p
                    v-else
                    class="border-b border-market-border py-8 text-sm text-market-muted"
                >
                    Kurikulum belum tersedia.
                </p>
            </section>

            <section
                v-if="course.requirements.length"
                aria-labelledby="requirements-heading"
            >
                <h2
                    id="requirements-heading"
                    class="text-2xl font-bold tracking-[-0.025em]"
                >
                    Persyaratan
                </h2>
                <ul class="mt-4 list-disc space-y-2 pl-5 text-market-muted">
                    <li
                        v-for="requirement in course.requirements"
                        :key="requirement"
                    >
                        {{ requirement }}
                    </li>
                </ul>
            </section>

            <section
                v-if="course.audience.length"
                aria-labelledby="audience-heading"
            >
                <h2
                    id="audience-heading"
                    class="text-2xl font-bold tracking-[-0.025em]"
                >
                    Kursus ini untuk siapa?
                </h2>
                <ul class="mt-4 list-disc space-y-2 pl-5 text-market-muted">
                    <li v-for="audience in course.audience" :key="audience">
                        {{ audience }}
                    </li>
                </ul>
            </section>
        </div>

        <aside class="lg:col-span-4" aria-labelledby="instructor-heading">
            <div class="border-t-2 border-market-ink pt-5">
                <p class="text-sm font-semibold text-market-primary">
                    Instruktur
                </p>
                <h2 id="instructor-heading" class="mt-2 text-xl font-bold">
                    {{ course.instructorProfile.name }}
                </h2>
                <p
                    v-if="course.instructorProfile.headline"
                    class="mt-1 text-sm font-medium text-market-muted"
                >
                    {{ course.instructorProfile.headline }}
                </p>
                <p
                    v-if="course.instructorProfile.bio"
                    class="mt-4 text-sm leading-6 text-market-muted"
                >
                    {{ course.instructorProfile.bio }}
                </p>
                <p v-else class="mt-4 text-sm leading-6 text-market-muted">
                    Profil instruktur belum dilengkapi.
                </p>
            </div>

            <div
                class="mt-8 border-t border-market-border pt-5 text-sm leading-6 text-market-muted"
            >
                <p class="font-semibold text-market-ink">Informasi transaksi</p>
                <p class="mt-2">
                    Kebijakan pembelian dan pengembalian akan ditampilkan
                    sebelum checkout tersedia.
                </p>
            </div>
        </aside>
    </div>
</template>
