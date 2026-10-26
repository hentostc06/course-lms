<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, GraduationCap, LayoutGrid } from '@lucide/vue';
import MarketplaceLayout from '@/layouts/MarketplaceLayout.vue';
</script>

<template>
    <MarketplaceLayout>
        <section class="border-b border-market-border bg-market-surface">
            <div class="mx-auto flex max-w-market flex-col gap-5 px-4 py-7 sm:flex-row sm:items-end sm:justify-between sm:px-6 lg:px-8">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.12em] text-market-primary">Instructor workspace</p>
                    <h1 class="text-3xl font-bold tracking-[-0.04em] sm:text-4xl">Studio Instruktur</h1>
                    <p class="mt-2 max-w-xl text-sm leading-6 text-market-muted">Bangun kursus yang terstruktur, jelas, dan layak dipertanggungjawabkan.</p>
                </div>
                <nav class="flex flex-wrap gap-2 text-sm" aria-label="Navigasi instruktur">
                    <Link href="/courses" class="inline-flex min-h-11 items-center gap-2 rounded-market-sm border border-market-border px-4 hover:bg-market-sage"><ArrowLeft :size="15"/> Katalog</Link>
                    <Link href="/instructor" class="inline-flex min-h-11 items-center gap-2 rounded-market-sm border border-market-border px-4 hover:bg-market-sage"><LayoutGrid :size="15"/> Kursus saya</Link>
                    <Link href="/instructor/onboarding" class="inline-flex min-h-11 items-center gap-2 rounded-market-sm border border-market-border px-4 hover:bg-market-sage"><GraduationCap :size="15"/> Akun instruktur</Link>
                </nav>
            </div>
        </section>
        <div class="mx-auto max-w-market px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </div>
    </MarketplaceLayout>
</template>
