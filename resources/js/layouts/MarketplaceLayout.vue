<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowUpRight, Search } from '@lucide/vue';

const page = usePage();
</script>

<template>
    <div
        class="marketplace-shell min-h-screen bg-market-canvas text-market-ink"
    >
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:bg-market-ink focus:px-5 focus:py-3 focus:text-white"
        >
            Lewati ke konten
        </a>

        <header
            class="relative z-30 border-b border-market-border bg-market-canvas"
        >
            <div
                class="mx-auto flex min-h-[76px] max-w-market items-center gap-5 px-5 sm:px-8 lg:px-10"
            >
                <Link
                    href="/"
                    aria-label="Course, beranda"
                    class="font-display inline-flex shrink-0 items-center gap-2.5 text-[21px] font-extrabold tracking-[-0.075em]"
                >
                    <span
                        class="grid h-9 w-9 place-items-center bg-market-ink text-market-canvas"
                    >
                        C
                    </span>

                    <span>
                        COURSE<span class="text-market-primary">.</span>
                    </span>
                </Link>

                <form
                    action="/courses"
                    method="get"
                    class="relative mx-3 hidden max-w-[440px] min-w-0 flex-1 lg:block"
                >
                    <label for="market-search" class="sr-only">
                        Cari kursus
                    </label>

                    <Search
                        class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-market-muted"
                        :size="17"
                        aria-hidden="true"
                    />

                    <input
                        id="market-search"
                        name="q"
                        type="search"
                        placeholder="Cari pelatihan atau keahlian"
                        class="h-11 w-full border border-market-border bg-market-surface pr-3 pl-10 text-sm text-market-ink transition-colors outline-none focus:border-market-primary"
                    />
                </form>

                <nav
                    class="ml-auto flex items-center gap-1 sm:gap-3"
                    aria-label="Navigasi utama"
                >
                    <Link
                        href="/courses"
                        class="inline-flex min-h-11 items-center px-2 text-sm font-semibold hover:text-market-primary"
                    >
                        Katalog
                    </Link>

                    <template v-if="page.props.auth?.user">
                        <Link
                            href="/my-learning"
                            class="inline-flex min-h-11 items-center px-2 text-sm font-semibold hover:text-market-primary"
                        >
                            Kelas saya
                        </Link>
                        <a
                            href="/dashboard"
                            class="inline-flex min-h-11 items-center px-2 text-sm font-semibold hover:text-market-primary"
                        >
                            Dashboard
                        </a>

                        <a
                            href="/admin"
                            title="Khusus akun dengan role admin"
                            class="hidden min-h-11 items-center px-2 text-sm font-semibold hover:text-market-primary sm:inline-flex"
                        >
                            Admin
                        </a>
                    </template>

                    <template v-else>
                        <Link
                            href="/login"
                            class="inline-flex min-h-11 items-center px-2 text-sm font-semibold hover:text-market-primary"
                        >
                            Masuk
                        </Link>

                        <Link
                            href="/register"
                            class="inline-flex min-h-11 items-center gap-1.5 bg-market-ink px-4 text-sm font-semibold text-white transition-colors hover:bg-market-primary"
                        >
                            Daftar
                            <ArrowUpRight :size="15" aria-hidden="true" />
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <main id="main-content">
            <slot />
        </main>

        <footer class="border-t border-market-border bg-market-canvas">
            <div
                class="mx-auto flex max-w-market flex-col gap-5 px-5 py-9 sm:flex-row sm:items-end sm:justify-between sm:px-8 lg:px-10"
            >
                <div>
                    <p
                        class="font-display text-2xl font-extrabold tracking-[-.06em]"
                    >
                        COURSE<span class="text-market-primary">.</span>
                    </p>

                    <p class="mt-1 text-sm text-market-muted">
                        Belajar dengan tujuan yang jelas.
                    </p>
                </div>

                <div
                    class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-semibold tracking-[.08em] text-market-muted uppercase"
                >
                    <Link href="/courses" class="hover:text-market-primary">
                        Katalog kursus
                    </Link>

                    <Link href="/login" class="hover:text-market-primary">
                        Masuk
                    </Link>

                    <a href="/admin" class="hover:text-market-primary">
                        Panel admin
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>
