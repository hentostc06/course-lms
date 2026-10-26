<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ArrowLeft, CheckCircle2, Circle } from '@lucide/vue';

const props = defineProps<{
    course: {
        title: string;
        slug: string;
    };
    lesson: {
        id: number;
        title: string;
        type: string;
        content: string | null;
        available: boolean;
        completed: boolean;
    };
    sections: Array<{
        id: number;
        title: string;
        lessons: Array<{
            id: number;
            title: string;
            type: string;
            completed: boolean;
        }>;
    }>;
}>();

const done = computed(() => props.lesson.completed);

function complete(): void {
    if (!props.lesson.available || done.value) return;

    router.put(
        `/learn/${props.course.slug}/${props.lesson.id}/progress`,
        {},
        { preserveScroll: true },
    );
}
</script>

<template>
    <Head :title="lesson.title" />

    <main class="mx-auto max-w-market px-5 py-8 sm:px-8 lg:px-10">
        <Link
            href="/my-learning"
            class="inline-flex min-h-11 items-center gap-2 text-sm font-bold hover:text-market-primary"
        >
            <ArrowLeft :size="18" /> Kelas saya
        </Link>

        <div class="mt-5 grid gap-8 lg:grid-cols-[minmax(0,1fr)_330px]">
            <article
                class="min-w-0 border border-market-border bg-market-surface p-6 sm:p-10"
            >
                <p class="text-sm font-bold text-market-primary">
                    {{ course.title }}
                </p>
                <h1
                    class="font-display mt-3 text-3xl leading-tight font-black tracking-tight"
                >
                    {{ lesson.title }}
                </h1>

                <div
                    v-if="lesson.available"
                    class="mt-8 max-w-3xl text-base leading-8 break-words whitespace-pre-wrap text-market-ink"
                >
                    {{ lesson.content }}
                </div>

                <div
                    v-else
                    class="mt-8 border border-market-border bg-market-sage p-6 text-market-muted"
                >
                    <p class="font-bold text-market-ink">
                        Materi
                        {{ lesson.type === 'video' ? 'video' : 'dokumen' }}
                        belum tersedia
                    </p>
                    <p class="mt-2 text-sm leading-6">
                        Konten media belum diunggah. Sistem tidak menghitungnya
                        sebagai materi selesai.
                    </p>
                </div>

                <button
                    v-if="lesson.available"
                    type="button"
                    :disabled="done"
                    class="mt-10 inline-flex min-h-12 items-center gap-2 bg-market-ink px-6 text-sm font-bold text-white disabled:opacity-60"
                    @click="complete"
                >
                    <CheckCircle2 :size="18" />
                    {{ done ? 'Sudah selesai' : 'Tandai artikel selesai' }}
                </button>
            </article>

            <aside
                class="border border-market-border bg-market-surface p-5 lg:self-start"
                aria-label="Daftar materi"
            >
                <h2 class="font-display mb-5 text-xl font-bold">Kurikulum</h2>

                <div
                    v-for="section in sections"
                    :key="section.id"
                    class="mb-5 border-t border-market-border pt-4"
                >
                    <h3 class="text-sm font-bold">{{ section.title }}</h3>

                    <Link
                        v-for="item in section.lessons"
                        :key="item.id"
                        :href="`/learn/${course.slug}/${item.id}`"
                        class="mt-2 flex min-h-11 items-center gap-3 px-2 py-2 text-sm hover:bg-market-sage"
                        :class="
                            item.id === lesson.id
                                ? 'bg-market-sage font-bold'
                                : ''
                        "
                    >
                        <CheckCircle2
                            v-if="item.completed"
                            :size="17"
                            class="shrink-0 text-market-primary"
                        />
                        <Circle
                            v-else
                            :size="17"
                            class="shrink-0 text-market-muted"
                        />
                        <span>{{ item.title }}</span>
                    </Link>
                </div>
            </aside>
        </div>
    </main>
</template>
