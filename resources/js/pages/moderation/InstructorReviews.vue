<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import MarketplaceLayout from '@/layouts/MarketplaceLayout.vue';

defineProps<{
    instructors: Array<{
        id: number;
        name: string;
        email: string;
        headline: string;
        bio: string;
    }>;
}>();
const processing = ref<number | null>(null);
function approve(id: number): void {
    processing.value = id;
    router.post(
        `/moderation/instructors/${id}/approve`,
        {},
        {
            preserveScroll: true,
            onFinish: () => {
                processing.value = null;
            },
        },
    );
}
</script>

<template>
    <Head title="Verifikasi instruktur" />
    <MarketplaceLayout>
        <section class="mx-auto max-w-market px-4 py-10 sm:px-6 lg:px-8">
            <nav class="mb-5 text-sm text-market-muted">
                <Link
                    href="/moderation/courses"
                    class="underline underline-offset-4"
                    >Review kursus</Link
                >
                / Verifikasi instruktur
            </nav>
            <p
                class="mb-2 text-xs font-bold tracking-wider text-market-primary uppercase"
            >
                Verifikasi
            </p>
            <h1 class="text-3xl font-bold tracking-[-0.04em]">
                Pengajuan instruktur
            </h1>
            <p class="mt-3 text-sm text-market-muted">
                Periksa identitas profesional dan kompetensi sebelum memberi hak
                membuat kursus.
            </p>
            <p class="mt-6 text-sm font-semibold">
                {{ instructors.length }} pengajuan menunggu
            </p>
            <div
                v-if="instructors.length === 0"
                class="mt-6 rounded-market-md border border-dashed border-market-border p-12 text-center text-market-muted"
            >
                Semua pengajuan sudah diperiksa.
            </div>
            <article
                v-for="item in instructors"
                :key="item.id"
                class="mt-5 rounded-market-md border border-market-border bg-market-surface p-6"
            >
                <h2 class="text-xl font-semibold">{{ item.name }}</h2>
                <p class="mt-1 text-sm text-market-muted">{{ item.email }}</p>
                <p class="mt-4 font-semibold">{{ item.headline }}</p>
                <p class="mt-3 text-sm leading-7 whitespace-pre-line">
                    {{ item.bio }}
                </p>
                <button
                    type="button"
                    :disabled="processing !== null"
                    class="mt-5 min-h-11 rounded-market-sm bg-market-primary px-5 text-sm font-semibold text-white disabled:opacity-50"
                    @click="approve(item.id)"
                >
                    Setujui instruktur
                </button>
            </article>
        </section>
    </MarketplaceLayout>
</template>
