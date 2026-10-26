<script setup lang="ts">
import { reactive, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import MarketplaceLayout from '@/layouts/MarketplaceLayout.vue';

defineProps<{
    courses: Array<{
        slug: string;
        title: string;
        summary: string;
        instructor: string;
        category: string;
        updatedAt: string | null;
        sections: Array<{
            id: number;
            title: string;
            lessons: Array<{
                id: number;
                title: string;
                type: string;
                content: string | null;
            }>;
        }>;
    }>;
}>();
const reasons = reactive<Record<string, string>>({});
const processing = ref<string | null>(null);
function decide(slug: string, decision: 'approve' | 'reject'): void {
    if (decision === 'reject' && (reasons[slug]?.trim().length ?? 0) < 10)
        return;
    processing.value = slug;
    const data =
        decision === 'reject'
            ? { decision, reason: reasons[slug]?.trim() ?? '' }
            : { decision };
    router.post(`/moderation/courses/${slug}/decide`, data, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = null;
        },
    });
}
</script>

<template>
    <Head title="Review kursus" />
    <MarketplaceLayout>
        <main class="mx-auto max-w-market px-4 py-10 sm:px-6 lg:px-8">
            <nav class="mb-4 text-sm text-market-muted">
                <Link href="/dashboard" class="underline underline-offset-4"
                    >Dashboard</Link
                >
                / Moderasi ·
                <Link
                    href="/moderation/instructors"
                    class="font-semibold underline underline-offset-4"
                    >Verifikasi instruktur →</Link
                >
                ·
                <Link
                    href="/moderation/categories"
                    class="font-semibold underline underline-offset-4"
                    >Kategori kursus →</Link
                >
            </nav>
            <p
                class="mb-2 text-xs font-bold tracking-[0.1em] text-market-primary uppercase"
            >
                Quality assurance
            </p>
            <h1 class="text-3xl font-bold tracking-[-0.04em]">Review kursus</h1>
            <p class="mt-3 max-w-2xl text-market-muted">
                Kursus baru wajib memenuhi standar kejelasan hasil belajar,
                silabus, dan kelayakan publikasi.
            </p>
            <p class="mt-6 text-sm font-semibold">
                {{ courses.length }} kursus menunggu keputusan
            </p>
            <div
                v-if="courses.length === 0"
                class="mt-6 rounded-market-md border border-dashed border-market-border p-12 text-center text-market-muted"
            >
                Tidak ada kursus menunggu review.
            </div>
            <div
                v-for="course in courses"
                :key="course.slug"
                class="mt-5 rounded-market-md border border-market-border bg-market-surface p-6"
            >
                <p
                    class="text-xs font-semibold tracking-wider text-market-primary uppercase"
                >
                    {{ course.category }} · {{ course.instructor }}
                </p>
                <h2 class="mt-2 text-xl font-semibold">{{ course.title }}</h2>
                <p class="mt-3 text-sm leading-6 text-market-muted">
                    {{ course.summary }}
                </p>
                <details
                    class="mt-4 rounded-market-sm border border-market-border p-4"
                >
                    <summary class="cursor-pointer font-semibold">
                        Periksa seluruh materi ({{
                            course.sections.reduce(
                                (total, section) =>
                                    total + section.lessons.length,
                                0,
                            )
                        }}
                        materi)
                    </summary>
                    <div
                        v-for="section in course.sections"
                        :key="section.id"
                        class="mt-4 border-t border-market-border pt-3"
                    >
                        <h3 class="font-semibold">{{ section.title }}</h3>
                        <article
                            v-for="lesson in section.lessons"
                            :key="lesson.id"
                            class="mt-3"
                        >
                            <h4 class="text-sm font-bold">
                                {{ lesson.title }} · {{ lesson.type }}
                            </h4>
                            <p
                                class="mt-2 text-sm break-words whitespace-pre-wrap text-market-muted"
                            >
                                {{
                                    lesson.content?.trim() ||
                                    'Materi belum tersedia.'
                                }}
                            </p>
                        </article>
                    </div>
                </details>
                <div class="mt-5 max-w-2xl">
                    <label
                        :for="`reason-${course.slug}`"
                        class="mb-2 block text-sm font-semibold"
                        >Alasan penolakan (wajib jika ditolak)</label
                    >
                    <textarea
                        :id="`reason-${course.slug}`"
                        v-model="reasons[course.slug]"
                        rows="3"
                        maxlength="2000"
                        class="w-full rounded-market-sm border border-market-border p-3 text-sm"
                        placeholder="Jelaskan revisi yang harus dilakukan instruktur."
                    />
                </div>
                <div class="mt-4 flex flex-wrap gap-3">
                    <button
                        type="button"
                        :disabled="processing !== null"
                        class="min-h-11 rounded-market-sm bg-market-primary px-5 text-sm font-semibold text-white disabled:opacity-50"
                        @click="decide(course.slug, 'approve')"
                    >
                        Setujui kursus
                    </button>
                    <button
                        type="button"
                        :disabled="
                            processing !== null ||
                            (reasons[course.slug]?.trim().length ?? 0) < 10
                        "
                        class="min-h-11 rounded-market-sm border border-red-400 px-5 text-sm font-semibold text-red-700 disabled:opacity-40"
                        @click="decide(course.slug, 'reject')"
                    >
                        Tolak dan beri catatan
                    </button>
                </div>
            </div>
        </main>
    </MarketplaceLayout>
</template>
