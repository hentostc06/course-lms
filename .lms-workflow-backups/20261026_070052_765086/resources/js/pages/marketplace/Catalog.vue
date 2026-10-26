<script setup lang="ts">
import {
    ref as filterRef,
    nextTick as filterNextTick,
    onMounted as filterMounted,
    onUnmounted as filterUnmounted,
} from 'vue';
import {
    computed,
    onMounted,
    onUnmounted,
    ref,
} from 'vue';

import {
    Head,
    Link,
    router,
    usePage,
} from '@inertiajs/vue3';

import {
    ArrowDownRight,
    ArrowUpRight,
    SlidersHorizontal,
} from '@lucide/vue';

import CourseCard from '@/components/course/CourseCard.vue';
import CourseFilters from '@/components/course/CourseFilters.vue';

import type {
    CatalogFilters,
    FilterOption,
    PaginatedCourses,
} from '@/types/catalog';

const props = defineProps<{
    courses: PaginatedCourses;
    categories: Array<{ name: string; slug: string; imageUrl?: string | null }>;
    filters: CatalogFilters;
    levels: FilterOption[];
}>();

const page = usePage();

const isHome = computed(
    () => page.url.split('?')[0] === '/',
);

const hasActiveFilters = computed(() =>
    Boolean(
        props.filters.q ||
        props.filters.category ||
        props.filters.level ||
        props.filters.price,
    ),
);

const heroShift = ref(0);

let pendingFrame = 0;

/**
 * Parallax ringan menggunakan requestAnimationFrame.
 * Tidak mengubah layout dan otomatis nonaktif
 * ketika pengguna memilih reduced motion.
 */
function onScroll(): void {
    if (!isHome.value) return;

    if (
        window.matchMedia(
            '(prefers-reduced-motion: reduce)',
        ).matches
    ) {
        return;
    }

    if (pendingFrame) return;

    pendingFrame = window.requestAnimationFrame(() => {
        heroShift.value = Math.min(
            110,
            window.scrollY * 0.18,
        );

        pendingFrame = 0;
    });
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, {
        passive: true,
    });
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);

    if (pendingFrame) {
        window.cancelAnimationFrame(pendingFrame);
    }
});

function applyFilters(filters: CatalogFilters): void {
    router.get(
        '/courses',
        {
            q: filters.q,
            category: filters.category,
            level: filters.level,
            price: filters.price,
            sort: filters.sort,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
}

function resetFilters(): void {
    router.get(
        '/courses',
        {},
        {
            preserveScroll: true,
            replace: true,
        },
    );
}

// Filter drawer: tampilan saja, query katalog tetap digunakan.
const catalogDrawerOpen = filterRef(false);
const catalogFilterButton = filterRef<HTMLButtonElement | null>(null);
const catalogFilterPanel = filterRef<HTMLElement | null>(null);
let catalogPreviousOverflow: string | null = null;

function openCatalogDrawer(): void {
    if (catalogDrawerOpen.value) return;

    catalogPreviousOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';
    catalogDrawerOpen.value = true;

    filterNextTick(() => catalogFilterPanel.value?.focus());
}

function closeCatalogDrawer(): void {
    if (!catalogDrawerOpen.value) return;

    catalogDrawerOpen.value = false;
    document.body.style.overflow = catalogPreviousOverflow ?? '';
    catalogPreviousOverflow = null;

    filterNextTick(() => catalogFilterButton.value?.focus());
}

function catalogDrawerKeys(event: KeyboardEvent): void {
    if (!catalogDrawerOpen.value) return;

    if (event.key === 'Escape') {
        event.preventDefault();
        closeCatalogDrawer();
        return;
    }

    if (event.key === 'Tab' && catalogFilterPanel.value) {
        const focusable = Array.from(
            catalogFilterPanel.value.querySelectorAll<HTMLElement>(
                'button:not([disabled]), input:not([disabled]), select:not([disabled]), a[href]'
            )
        );

        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (
            event.shiftKey &&
            (
                document.activeElement === first ||
                document.activeElement === catalogFilterPanel.value
            )
        ) {
            event.preventDefault();
            last.focus();
        } else if (
            !event.shiftKey &&
            document.activeElement === last
        ) {
            event.preventDefault();
            first.focus();
        }
    }
}

function applyCatalogDrawer(filters: CatalogFilters): void {
    closeCatalogDrawer();
    applyFilters(filters);
}

function resetCatalogDrawer(): void {
    closeCatalogDrawer();
    resetFilters();
}

filterMounted(() => {
    document.addEventListener('keydown', catalogDrawerKeys);
});

filterUnmounted(() => {
    document.removeEventListener('keydown', catalogDrawerKeys);

    if (catalogDrawerOpen.value) {
        document.body.style.overflow = catalogPreviousOverflow ?? '';
    }
});


// COURSE_LMS_QUICK_LEVEL_V2
const courseLevelShortcuts = [
    { label: 'Semua tingkat', value: '' },
    { label: 'Pemula', value: 'beginner' },
    { label: 'Menengah', value: 'intermediate' },
    { label: 'Tingkat lanjut', value: 'advanced' },
];

function selectCourseLevel(level: string): void {
    if (props.filters.level === level) return;

    applyFilters({
        ...props.filters,
        level,
    });
}

</script>

<template>
    <Head
        :title="isHome
            ? 'Belajar yang berbeda'
            : 'Katalog kursus'"
    >
        <meta
            name="description"
            content="Kursus profesional, silabus transparan, dan keterampilan yang dapat diterapkan."
        />
    </Head>

    <!-- HERO KHUSUS BERANDA -->
    <section
        v-if="isHome"
        class="market-hero relative isolate
               overflow-hidden bg-market-primary
               text-market-ink"
    >
        <div
            class="relative mx-auto grid min-h-[640px]
                   max-w-market items-center gap-7
                   px-5 pb-12 pt-16 sm:px-8
                   lg:grid-cols-[.95fr_1.05fr]
                   lg:px-10 lg:py-12"
        >
            <!-- TYPOGRAPHY -->
            <div
                class="relative z-10 max-w-[690px]"
            >
                <p
                    class="mb-8 flex items-center gap-3
                           text-xs font-bold uppercase
                           tracking-[.15em]"
                >
                    <span
                        class="inline-block h-[2px] w-9
                               bg-market-ink"
                    ></span>

                    Education, reimagined
                </p>

                <h1
                    class="font-display
                           text-[clamp(3.25rem,6.2vw,7.5rem)]
                           font-black uppercase leading-[.93]
                           tracking-[-.085em]"
                >
                    LESS OF<br />
                    THE SAME.<br />

                    <span class="text-[#F4F1E9]">
                        MORE OF<br />
                        WHAT MATTERS.
                    </span>
                </h1>

                <p
                    class="mt-8 max-w-[470px] text-base
                           font-medium leading-7 sm:text-lg"
                >
                    Bukan sekadar menonton materi.
                    Pelajari keterampilan praktis,
                    ikuti alur yang jelas, dan lihat
                    progresmu sendiri.
                </p>

                <div
                    class="mt-9 flex flex-wrap gap-3"
                >
                    <a
                        href="#jelajahi-kursus"
                        class="inline-flex min-h-12 items-center gap-3
                               bg-market-ink px-6 text-sm font-bold
                               text-white transition-transform
                               hover:-translate-y-1
                               motion-reduce:transform-none"
                    >
                        Jelajahi kursus

                        <ArrowUpRight
                            :size="18"
                            aria-hidden="true"
                        />
                    </a>

                    <Link
                        href="/register"
                        class="inline-flex min-h-12 items-center
                               border border-market-ink px-6
                               text-sm font-bold transition-colors
                               hover:bg-market-ink hover:text-white"
                    >
                        Mulai belajar
                    </Link>
                </div>
            </div>

            <!-- HUMAN PHOTO + MASKING -->
            <div
                class="hero-art relative min-h-[360px]
                       w-full self-stretch
                       sm:min-h-[440px] lg:min-h-[580px]"
                aria-label="Visual editorial pembelajaran"
            >
                <span
                    class="pointer-events-none absolute
                           right-[5%] top-[9%] aspect-square
                           w-[78%] rounded-full border-[30px]
                           border-[#F4F1E9]/50
                           sm:border-[55px]"
                    aria-hidden="true"
                ></span>

                <div
                    class="portrait-mask absolute inset-0
                           overflow-hidden"
                    aria-hidden="true"
                >
                    <img
                        src="https://images.unsplash.com/photo-1646105659698-1389145bf6a0?auto=format&amp;fit=crop&amp;w=1200&amp;q=85"
                        alt=""
                        fetchpriority="high"
                        class="absolute inset-0 h-[112%] w-full
                               object-cover object-[center_37%]
                               will-change-transform"
                        :style="{
                            transform: `translate3d(0, ${heroShift}px, 0) scale(1.03)`
                        }"
                    />
                </div>

                <div
                    class="pointer-events-none absolute
                           bottom-[8%] right-[2%]
                           border-l-4 border-market-ink
                           bg-market-canvas px-4 py-3
                           text-[11px] font-bold uppercase
                           tracking-[.1em]
                           shadow-[7px_7px_0_#171716]
                           sm:px-5 sm:py-4"
                >
                    Belajar dengan<br />
                    cara sendiri — 2026
                </div>
            </div>
        </div>

        <div
            class="relative z-10 flex items-center
                   justify-between border-t
                   border-market-ink/25 px-5 py-4
                   text-[11px] font-bold uppercase
                   tracking-[.15em] sm:px-8 lg:px-10"
        >
            <span>COURSE / MARKETPLACE</span>

            <a
                href="#jelajahi-kursus"
                class="inline-flex items-center gap-2"
            >
                Scroll untuk menjelajah

                <ArrowDownRight
                    :size="17"
                    aria-hidden="true"
                />
            </a>
        </div>
    </section>

    <!-- HEADER KATALOG -->
    <section
        v-else
        class="border-b border-market-border
               bg-market-canvas"
    >
        <div
            class="mx-auto max-w-market px-5 py-12
                   sm:px-8 lg:px-10 lg:py-16"
        >
            <p
                class="mb-3 text-xs font-bold uppercase
                       tracking-[.15em] text-market-primary"
            >
                Katalog / pembelajaran
            </p>

            <h1
                class="max-w-4xl font-display text-5xl
                       font-extrabold uppercase leading-[.98]
                       tracking-[-.065em] sm:text-6xl"
            >
                Temukan kelas yang tepat.
            </h1>

            <p
                class="mt-5 max-w-2xl text-base
                       leading-7 text-market-muted"
            >
                Bandingkan topik, tingkat, durasi,
                serta isi materi sebelum memutuskan.
            </p>
        </div>
    </section>

    <!-- Homepage category navigation -->
    <section
        v-if="isHome && categories.length"
        id="kategori-populer"
        class="border-b border-market-border bg-market-surface"
    >
        <div class="mx-auto max-w-market px-5 py-10 sm:px-8 lg:px-10">
            <div class="mb-5 flex items-center justify-between gap-5">
                <h2 class="font-display text-2xl font-extrabold tracking-tight sm:text-3xl">
                    Jelajahi kategori
                </h2>
                <Link href="/courses" class="text-sm font-bold underline underline-offset-4">
                    Semua kursus
                </Link>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <Link
                    v-for="(category, index) in categories"
                    :key="category.slug"
                    :href="`/courses?category=${encodeURIComponent(category.slug)}`"
                    class="relative isolate overflow-hidden flex min-h-28 flex-col justify-between border border-market-border bg-market-canvas p-4 transition-colors hover:border-market-ink hover:bg-market-sage"
                >
                    <img
                        v-if="category.imageUrl"
                        :src="category.imageUrl"
                        alt=""
                        loading="lazy"
                        class="pointer-events-none absolute inset-0 -z-10 size-full object-cover"
                    />
                    <span
                        v-if="category.imageUrl"
                        aria-hidden="true"
                        class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-r from-[#F6F1E8]/95 via-[#F6F1E8]/80 to-[#F6F1E8]/25"
                    ></span>

                    <span class="text-xs font-semibold text-market-primary">
                        {{ String(index + 1).padStart(2, '0') }}
                    </span>
                    <span class="font-display text-lg font-bold leading-tight">
                        {{ category.name }}
                    </span>
                </Link>
            </div>
        </div>
    </section>

    <!-- KATALOG TETAP DINAMIS -->
    <section
        id="jelajahi-kursus"
        class="mx-auto max-w-market px-5 py-12
               sm:px-8 lg:px-10 lg:py-16"
    >
        <div
            class="mb-9 flex flex-wrap items-end
                   justify-between gap-4 border-b
                   border-market-border pb-5"
        >
            <div>
                <p
                    class="mb-2 text-xs font-bold uppercase
                           tracking-[.15em] text-market-primary"
                >
                    01 / Eksplorasi
                </p>

                <h2
                    class="font-display text-3xl font-extrabold
                           tracking-[-.06em] sm:text-4xl"
                >
                    Katalog Kursus<span class="text-market-primary">.</span>
                </h2>
            </div>

            <span
                class="text-sm font-semibold text-market-muted"
            >
                {{ courses.total }} kursus tersedia
            </span>
        </div>

        
        <!-- COURSE FILTER DRAWER -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <button
                ref="catalogFilterButton"
                type="button"
                class="inline-flex min-h-11 items-center gap-3 rounded-market-sm border border-market-border bg-market-surface px-4 py-2 text-sm font-bold text-market-ink transition-colors hover:border-market-primary hover:text-market-primary"
                aria-controls="catalog-filter-panel"
                :aria-expanded="catalogDrawerOpen"
                @click="openCatalogDrawer"
            >
                <SlidersHorizontal :size="19" aria-hidden="true" />
                Filter &amp; urutkan

                <span
                    v-if="hasActiveFilters"
                    class="size-2 rounded-full bg-market-primary"
                    aria-label="Filter aktif"
                ></span>
            </button>

            <!-- COURSE_LMS_QUICK_LEVEL_V2 -->
            <div
                class="flex min-w-0 flex-wrap items-center gap-2"
                role="group"
                aria-label="Pilih tingkat kursus"
            >
                <button
                    v-for="option in courseLevelShortcuts"
                    :key="option.value"
                    type="button"
                    :aria-pressed="filters.level === option.value"
                    class="inline-flex min-h-11 items-center justify-center rounded-market-sm border px-4 py-2 text-sm font-semibold transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-market-primary"
                    :class="
                        filters.level === option.value
                            ? 'border-market-ink bg-market-ink text-white'
                            : 'border-market-border bg-market-surface text-market-ink hover:border-market-primary hover:text-market-primary'
                    "
                    @click="selectCourseLevel(option.value)"
                >
                    {{ option.label }}
                </button>
            </div>


            <span class="text-xs text-market-muted">
                {{ courses.total }} kursus
            </span>
        </div>

        <!-- DARK OVERLAY -->
        <Transition name="catalog-filter-backdrop">
            <button
                v-if="catalogDrawerOpen"
                type="button"
                class="fixed inset-0 z-[80] bg-black/55"
                aria-label="Tutup panel filter"
                @click="closeCatalogDrawer"
            />
        </Transition>

        <!-- LEFT SLIDING DRAWER -->
        <Transition name="catalog-filter-slide">
            <aside
                v-if="catalogDrawerOpen"
                id="catalog-filter-panel"
                ref="catalogFilterPanel"
                tabindex="-1"
                role="dialog"
                aria-modal="true"
                aria-labelledby="catalog-filter-title"
                class="fixed inset-y-0 left-0 z-[81] w-[min(90vw,390px)] overflow-y-auto overscroll-contain bg-market-canvas px-5 pb-10 pt-6 shadow-2xl focus:outline-none sm:px-7"
            >
                <div class="mb-8 flex items-center justify-between border-b border-market-border pb-5">
                    <h2
                        id="catalog-filter-title"
                        class="font-display text-xl font-bold"
                    >
                        Filter kursus
                    </h2>

                    <button
                        type="button"
                        class="inline-flex size-10 items-center justify-center border border-market-border text-lg hover:bg-market-sage"
                        aria-label="Tutup filter"
                        @click="closeCatalogDrawer"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <CourseFilters
                    :filters="filters"
                    :categories="categories"
                    :levels="levels"
                    id-prefix="catalog-drawer"
                    @apply="applyCatalogDrawer"
                    @reset="resetCatalogDrawer"
                />
            </aside>
        </Transition>



        <div class="grid gap-10 grid-cols-1">
            
<!-- COURSE RESULTS -->
            <div class="min-w-0 w-full">
                <div
                    v-if="courses.data.length"
                    class="grid gap-x-6 gap-y-9
                           sm:grid-cols-2 xl:grid-cols-3"
                >
                    <CourseCard
                        v-for="course in courses.data"
                        :key="course.slug"
                        :course="course"
                    />
                </div>

                <div
                    v-else
                    class="flex min-h-[300px] flex-col
                           items-start justify-center
                           border-y border-market-border
                           px-4 py-10 sm:px-12"
                >
                    <span
                        class="mb-4 text-[11px] font-bold
                               uppercase tracking-[.14em]
                               text-market-primary"
                    >
                        Belum ada publikasi
                    </span>

                    <h3
                        class="font-display text-2xl font-bold
                               tracking-[-.04em] sm:text-3xl"
                    >
                        {{
                            hasActiveFilters
                                ? 'Belum ada yang cocok.'
                                : 'Kursus sedang disiapkan.'
                        }}
                    </h3>

                    <p
                        class="mt-3 max-w-md text-sm
                               leading-7 text-market-muted"
                    >
                        {{
                            hasActiveFilters
                                ? 'Coba ubah filter untuk melihat kursus lain.'
                                : 'Begitu instruktur menerbitkan kelas, semuanya akan muncul di sini.'
                        }}
                    </p>

                    <button
                        v-if="hasActiveFilters"
                        type="button"
                        class="mt-6 inline-flex min-h-11
                               items-center gap-2 bg-market-ink
                               px-5 text-sm font-semibold
                               text-white transition-colors
                               hover:bg-market-primary"
                        @click="resetFilters"
                    >
                        Reset filter

                        <ArrowUpRight :size="17" />
                    </button>

                    <Link
                        v-else
                        href="/register"
                        class="mt-6 inline-flex min-h-11
                               items-center gap-2 border
                               border-market-ink px-5
                               text-sm font-semibold
                               hover:bg-market-ink
                               hover:text-white"
                    >
                        Bergabung sekarang

                        <ArrowUpRight :size="17" />
                    </Link>
                </div>

                <!-- PAGINATION -->
                <nav
                    v-if="courses.last_page > 1"
                    class="mt-10 flex items-center
                           justify-between gap-3 border-t
                           border-market-border pt-6 text-sm"
                    aria-label="Paginasi katalog"
                >
                    <Link
                        v-if="courses.prev_page_url"
                        :href="courses.prev_page_url"
                        class="border border-market-border
                               px-4 py-3 font-semibold"
                        preserve-scroll
                    >
                        Sebelumnya
                    </Link>

                    <span
                        v-else
                        class="px-4 py-3 opacity-40"
                    >
                        Sebelumnya
                    </span>

                    <span class="text-market-muted">
                        {{ courses.current_page }}
                        /
                        {{ courses.last_page }}
                    </span>

                    <Link
                        v-if="courses.next_page_url"
                        :href="courses.next_page_url"
                        class="border border-market-border
                               px-4 py-3 font-semibold"
                        preserve-scroll
                    >
                        Berikutnya
                    </Link>

                    <span
                        v-else
                        class="px-4 py-3 opacity-40"
                    >
                        Berikutnya
                    </span>
                </nav>
            </div>
        </div>
    </section>
</template>

<style scoped>
/*
 * Masking menggunakan CSS murni.
 * Foto di dalamnya dapat diganti tanpa
 * mengubah struktur hero.
 */

.portrait-mask {
    -webkit-mask-image: radial-gradient(
        ellipse 55% 49% at 54% 54%,
        #000 98%,
        transparent 100%
    );

    mask-image: radial-gradient(
        ellipse 55% 49% at 54% 54%,
        #000 98%,
        transparent 100%
    );

    -webkit-mask-repeat: no-repeat;
    mask-repeat: no-repeat;
}

@media (min-width: 1024px) {
    .hero-art {
        margin-right: -3%;
    }
}

@media (max-width: 767px) {
    .portrait-mask {
        -webkit-mask-image: radial-gradient(
            ellipse 53% 46% at 51% 50%,
            #000 98%,
            transparent 100%
        );

        mask-image: radial-gradient(
            ellipse 53% 46% at 51% 50%,
            #000 98%,
            transparent 100%
        );
    }
}

@media (prefers-reduced-motion: reduce) {
    .hero-art img {
        transform: none !important;
        will-change: auto;
    }
}

/* Left drawer animation */
.catalog-filter-slide-enter-active,
.catalog-filter-slide-leave-active {
    transition: transform 250ms cubic-bezier(.2, .8, .2, 1);
}

.catalog-filter-slide-enter-from,
.catalog-filter-slide-leave-to {
    transform: translateX(-105%);
}

/* Background overlay animation */
.catalog-filter-backdrop-enter-active,
.catalog-filter-backdrop-leave-active {
    transition: opacity 250ms ease;
}

.catalog-filter-backdrop-enter-from,
.catalog-filter-backdrop-leave-to {
    opacity: 0;
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .catalog-filter-slide-enter-active,
    .catalog-filter-slide-leave-active,
    .catalog-filter-backdrop-enter-active,
    .catalog-filter-backdrop-leave-active {
        transition: none;
    }
}

</style>
