<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="auth-page">
        <main class="auth-content">
            <header class="auth-topline">
                <Link
                    :href="home()"
                    class="auth-brand"
                    aria-label="Course, kembali ke beranda"
                >
                    <span class="auth-brand-square" aria-hidden="true">
                        C<span>.</span>
                    </span>
                    <span>COURSE<span class="brand-slash">/</span></span>
                </Link>
                <span class="auth-edition">PLATFORM PEMBELAJARAN</span>
            </header>

            <section class="auth-center">
                <div class="auth-intro">
                    <span class="auth-overline">
                        <span class="auth-overline-bar"></span>
                        AKSES AKUN
                    </span>
                    <h1>{{ title }}</h1>
                    <p v-if="description">{{ description }}</p>
                </div>

                <slot />
            </section>

            <footer class="auth-bottom">
                <span>© {{ new Date().getFullYear() }} COURSE</span>
                <Link :href="home()">
                    KEMBALI KE BERANDA <span aria-hidden="true">↗</span>
                </Link>
            </footer>
        </main>

        <aside class="auth-editorial" aria-label="Tentang Course">
            <div class="editorial-head">
                <span>COURSE / JOURNAL</span>
                <span>AKUN PENGGUNA</span>
            </div>

            <div class="editorial-body">
                <p class="editorial-label">
                    <span></span>
                    PENDIDIKAN YANG BERMAKNA
                </p>

                <h2>
                    BELAJAR<br />
                    BUKAN<br />
                    SEKADAR<br />
                    <span>MENONTON.</span>
                </h2>

                <p>
                    Materi terarah. Progres terukur.<br />
                    Keahlian yang bisa diterapkan.
                </p>
            </div>

            <div class="editorial-footer">
                <span>CURIOUS MINDS, BETTER SKILLS.</span>
                <span>COURSE / 2026</span>
            </div>
        </aside>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800;900&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap');

.auth-page {
    --paper: #f4f1e9;
    --ink: #171716;
    --muted: #655f57;
    --line: #c9c4ba;
    --red: #d94a2f;

    display: grid;
    grid-template-columns: minmax(0, 52%) minmax(0, 48%);
    min-height: 100svh;
    background: var(--paper);
    color: var(--ink);
    font-family: 'IBM Plex Sans', Arial, sans-serif;
    font-size: 15px;
    line-height: 1.5;
}

.auth-content {
    min-width: 0;
    min-height: 100svh;
    display: flex;
    flex-direction: column;
    padding: 38px clamp(26px, 5vw, 82px) 27px;
}

.auth-topline,
.auth-bottom,
.editorial-head,
.editorial-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
}

.auth-brand {
    display: inline-flex;
    align-items: center;
    gap: 11px;
    color: var(--ink);
    text-decoration: none;
    font:
        800 19px/1 'Archivo',
        Arial,
        sans-serif;
    letter-spacing: -0.045em;
}

.auth-brand-square {
    width: 34px;
    height: 34px;
    background: var(--ink);
    color: var(--paper);
    display: grid;
    place-items: center;
    font-size: 19px;
    letter-spacing: -0.08em;
}

.auth-brand-square span,
.brand-slash {
    color: var(--red);
}

.brand-slash {
    margin-left: 2px;
}

.auth-edition,
.auth-bottom,
.editorial-head,
.editorial-footer,
.auth-overline,
.editorial-label {
    font:
        600 10px/1.5 'IBM Plex Sans',
        Arial,
        sans-serif;
    letter-spacing: 0.115em;
}

.auth-edition {
    color: var(--muted);
    text-align: right;
}

.auth-center {
    width: min(100%, 425px);
    margin: 42px auto;
    padding: 12px 0;
}

.auth-intro {
    margin-bottom: 31px;
}

.auth-overline {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 0 0 16px;
    color: var(--red);
}

.auth-overline-bar {
    display: block;
    width: 21px;
    height: 2px;
    background: var(--red);
}

.auth-intro h1 {
    margin: 0;
    font:
        700 clamp(31px, 3.25vw, 45px)/1.14 'Archivo',
        Arial,
        sans-serif;
    letter-spacing: -0.055em;
}

.auth-intro p {
    margin: 12px 0 0;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.65;
}

.auth-bottom {
    margin-top: auto;
    padding-top: 16px;
    border-top: 1px solid var(--line);
    color: var(--muted);
}

.auth-bottom a {
    color: var(--ink);
    text-decoration: none;
}

.auth-bottom a:hover {
    color: var(--red);
}

.auth-editorial {
    min-width: 0;
    min-height: 100svh;
    padding: 40px clamp(28px, 4vw, 68px) 29px;
    background: var(--ink);
    color: var(--paper);
    display: flex;
    flex-direction: column;
}

.editorial-head,
.editorial-footer {
    color: #b8b2a7;
}

.editorial-head {
    border-bottom: 1px solid #494641;
    padding-bottom: 18px;
}

.editorial-body {
    margin: auto 0;
    padding: 60px 0;
}

.editorial-label {
    color: #d7d1c5;
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 32px;
}

.editorial-label span {
    width: 26px;
    height: 2px;
    background: var(--red);
}

.editorial-body h2 {
    margin: 0;
    font:
        800 clamp(42px, 4.55vw, 83px)/0.99 'Archivo',
        Arial,
        sans-serif;
    letter-spacing: -0.075em;
}

.editorial-body h2 span {
    color: var(--red);
}

.editorial-body p {
    margin: 35px 0 0;
    padding-left: 18px;
    border-left: 2px solid var(--red);
    color: #cbc6bc;
    font-size: clamp(14px, 1.2vw, 17px);
    line-height: 1.7;
}

.editorial-footer {
    border-top: 1px solid #494641;
    padding-top: 18px;
}

.auth-page :deep(a:focus-visible),
.auth-page :deep(button:focus-visible),
.auth-page :deep(input:focus-visible) {
    outline: 3px solid var(--red);
    outline-offset: 3px;
}

@media (min-width: 1500px) {
    .auth-center {
        width: min(100%, 480px);
    }
}

@media (max-width: 1000px) {
    .auth-page {
        grid-template-columns: 55% 45%;
    }

    .auth-content {
        padding-inline: 34px;
    }

    .auth-editorial {
        padding-inline: 26px;
    }

    .editorial-body h2 {
        font-size: clamp(39px, 5vw, 57px);
    }
}

@media (max-width: 760px) {
    .auth-page {
        grid-template-columns: 1fr;
    }

    .auth-content {
        min-height: 100svh;
        padding: 24px clamp(18px, 5.5vw, 30px) 23px;
    }

    .auth-editorial {
        display: none;
    }

    .auth-edition {
        font-size: 9px;
    }

    .auth-center {
        margin: auto;
        padding: 50px 0;
    }

    .auth-bottom {
        font-size: 9px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .auth-page *,
    .auth-page *::before,
    .auth-page *::after {
        transition: none !important;
    }
}
</style>
