<script setup lang="ts">
// COURSE_HERO_ORIGINAL_V1
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ArrowDownRight, ArrowUpRight } from '@lucide/vue';

const heroShift = ref(0);

let pendingFrame = 0;

/**
 * Parallax ringan menggunakan requestAnimationFrame.
 * Tidak mengubah layout dan otomatis nonaktif
 * ketika pengguna memilih reduced motion.
 */
function onScroll(): void {

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    if (pendingFrame) return;

    pendingFrame = window.requestAnimationFrame(() => {
        heroShift.value = Math.min(110, window.scrollY * 0.18);

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
</script>

<template>
<section
        
        class="market-hero relative isolate overflow-hidden bg-market-primary text-market-ink"
    >
        <div
            class="relative mx-auto grid min-h-[640px] max-w-market items-center gap-7 px-5 pt-16 pb-12 sm:px-8 lg:grid-cols-[.95fr_1.05fr] lg:px-10 lg:py-12"
        >
            <!-- TYPOGRAPHY -->
            <div class="relative z-10 max-w-[690px]">
                <p
                    class="mb-8 flex items-center gap-3 text-xs font-bold tracking-[.15em] uppercase"
                >
                    <span class="inline-block h-[2px] w-9 bg-market-ink"></span>

                    Education, reimagined
                </p>

                <h1
                    class="font-display text-[clamp(3.25rem,6.2vw,7.5rem)] leading-[.93] font-black tracking-[-.085em] uppercase"
                >
                    LESS OF<br />
                    THE SAME.<br />

                    <span class="text-[#F4F1E9]">
                        MORE OF<br />
                        WHAT MATTERS.
                    </span>
                </h1>

                <p
                    class="mt-8 max-w-[470px] text-base leading-7 font-medium sm:text-lg"
                >
                    Bukan sekadar menonton materi. Pelajari keterampilan
                    praktis, ikuti alur yang jelas, dan lihat progresmu sendiri.
                </p>

                <div class="mt-9 flex flex-wrap gap-3">
                    <a
                        href="#jelajahi-kursus"
                        class="inline-flex min-h-12 items-center gap-3 bg-market-ink px-6 text-sm font-bold text-white transition-transform hover:-translate-y-1 motion-reduce:transform-none"
                    >
                        Jelajahi kursus

                        <ArrowUpRight :size="18" aria-hidden="true" />
                    </a>

                    <Link
                        href="/register"
                        class="inline-flex min-h-12 items-center border border-market-ink px-6 text-sm font-bold transition-colors hover:bg-market-ink hover:text-white"
                    >
                        Mulai belajar
                    </Link>
                </div>
            </div>

            <!-- HUMAN PHOTO + MASKING -->
            <div
                class="hero-art relative min-h-[360px] w-full self-stretch sm:min-h-[440px] lg:min-h-[580px]"
                aria-label="Visual editorial pembelajaran"
            >
                <span
                    class="pointer-events-none absolute top-[9%] right-[5%] aspect-square w-[78%] rounded-full border-[30px] border-[#F4F1E9]/50 sm:border-[55px]"
                    aria-hidden="true"
                ></span>

                <div
                    class="portrait-mask absolute inset-0 overflow-hidden"
                    aria-hidden="true"
                >
                    <img
                        src="https://images.unsplash.com/photo-1646105659698-1389145bf6a0?auto=format&amp;fit=crop&amp;w=1200&amp;q=85"
                        alt=""
                        fetchpriority="high"
                        class="absolute inset-0 h-[112%] w-full object-cover object-[center_37%] will-change-transform"
                        :style="{
                            transform: `translate3d(0, ${heroShift}px, 0) scale(1.03)`,
                        }"
                    />
                </div>

                <div
                    class="pointer-events-none absolute right-[2%] bottom-[8%] border-l-4 border-market-ink bg-market-canvas px-4 py-3 text-[11px] font-bold tracking-[.1em] uppercase shadow-[7px_7px_0_#171716] sm:px-5 sm:py-4"
                >
                    Belajar dengan<br />
                    cara sendiri — 2026
                </div>
            </div>
        </div>

        <div
            class="relative z-10 flex items-center justify-between border-t border-market-ink/25 px-5 py-4 text-[11px] font-bold tracking-[.15em] uppercase sm:px-8 lg:px-10"
        >
            <span>COURSE / MARKETPLACE</span>

            <a href="#jelajahi-kursus" class="inline-flex items-center gap-2">
                Scroll untuk menjelajah

                <ArrowDownRight :size="17" aria-hidden="true" />
            </a>
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
</style>
