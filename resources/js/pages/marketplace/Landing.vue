<script setup lang="ts">
// COURSE_LANDING_V3_20261028
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight, ArrowUpRight, Bookmark, BookOpen,
    CalendarDays, Check, Download, GitCompareArrows, Search, X,
} from '@lucide/vue';
import CourseCard from '@/components/course/CourseCard.vue';
import OriginalLandingHero from '@/components/course/OriginalLandingHero.vue';
import {
    Dialog, DialogClose, DialogContent, DialogDescription, DialogHeader, DialogTitle,
} from '@/components/ui/dialog';
import { formatDuration, formatIdr } from '@/lib/catalog';
import type { CatalogCourse, CatalogFilters, FilterOption, PaginatedCourses } from '@/types/catalog';

const props = defineProps<{
    courses: PaginatedCourses;
    categories: Array<{ name: string; slug: string; imageUrl?: string | null }>;
    filters: CatalogFilters;
    levels: FilterOption[];
}>();

type SavedCourse = Pick<CatalogCourse, 'slug' | 'title'>;
const savedKey = 'course.landing.saved.v3';
const recentKey = 'course.landing.recent.v3';
const saved = ref<SavedCourse[]>([]);
const recent = ref<SavedCourse[]>([]);
const storageAvailable = ref(true);
const savedOpen = ref(false);
const compareOpen = ref(false);
const compared = ref<string[]>([]);
const notice = ref('');
const quickFilter = ref('all');
const sort = ref('newest');
const query = ref(props.filters.q || '');
const planSlug = ref(props.courses.data[0]?.slug || '');
const dailyMinutes = ref(30);
const startDate = ref('');
const today = ref('');
let noticeTimer: ReturnType<typeof setTimeout> | undefined;

const comparedCourses = computed(() => compared.value.flatMap((slug) => {
    const course = props.courses.data.find((item) => item.slug === slug);
    return course ? [course] : [];
}));
const visibleCourses = computed(() => {
    let list = props.courses.data.filter((course) =>
        quickFilter.value === 'free' ? course.priceIdr === 0 :
        quickFilter.value === 'saved' ? isSaved(course.slug) : true,
    );
    if (sort.value === 'price') list = [...list].sort((a, b) => a.priceIdr - b.priceIdr);
    if (sort.value === 'duration') list = [...list].sort((a, b) =>
        (a.durationMinutes || Infinity) - (b.durationMinutes || Infinity),
    );
    return list;
});
const plannedCourse = computed(() => props.courses.data.find((course) => course.slug === planSlug.value));
const planDays = computed(() => {
    const duration = plannedCourse.value?.durationMinutes ?? 0;
    const minutes = Number(dailyMinutes.value);
    return duration > 0 && Number.isFinite(minutes) && minutes > 0
        ? Math.ceil(duration / minutes) : 0;
});
function parseDate(value: string): Date | null {
    if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) return null;
    const [year, month, day] = value.split('-').map(Number);
    const date = new Date(year, month - 1, day, 12);
    return date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day ? date : null;
}
const endDate = computed(() => {
    const date = parseDate(startDate.value);
    if (!date || !planDays.value) return '';
    date.setDate(date.getDate() + planDays.value - 1);
    return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(date);
});

function announce(message: string): void {
    notice.value = message;
    if (noticeTimer) clearTimeout(noticeTimer);
    noticeTimer = setTimeout(() => { notice.value = ''; }, 4500);
}
function readStored(key: string): SavedCourse[] {
    try {
        const value: unknown = JSON.parse(localStorage.getItem(key) || '[]');
        if (!Array.isArray(value)) return [];
        const seen = new Set<string>();
        return value.filter((entry): entry is SavedCourse => {
            if (!entry || typeof entry !== 'object') return false;
            const item = entry as Record<string, unknown>;
            if (typeof item.slug !== 'string' || !item.slug || item.slug.length > 255 ||
                typeof item.title !== 'string' || item.title.length > 500 || seen.has(item.slug)) return false;
            seen.add(item.slug);
            return true;
        }).slice(0, 60).map(({ slug, title }) => ({ slug, title }));
    } catch {
        storageAvailable.value = false;
        return [];
    }
}
function persist(key: string, value: SavedCourse[]): void {
    try { localStorage.setItem(key, JSON.stringify(value)); }
    catch { storageAvailable.value = false; }
}
function isSaved(slug: string): boolean { return saved.value.some((course) => course.slug === slug); }
function toggleSaved(course: CatalogCourse): void {
    if (isSaved(course.slug)) {
        removeSaved(course.slug);
        return;
    }
    if (saved.value.length >= 60) { announce('Maksimal 60 kelas tersimpan. Hapus salah satu terlebih dahulu.'); return; }
    saved.value = [{ slug: course.slug, title: course.title }, ...saved.value];
    persist(savedKey, saved.value);
    announce(storageAvailable.value ? 'Kelas disimpan di browser ini.' : 'Kelas disimpan sementara selama halaman terbuka.');
}
function removeSaved(slug: string): void {
    saved.value = saved.value.filter((course) => course.slug !== slug);
    persist(savedKey, saved.value);
    announce('Kelas dihapus dari daftar simpan.');
}
function rememberCourse(course: SavedCourse): void {
    recent.value = [{ slug: course.slug, title: course.title }, ...recent.value.filter((item) => item.slug !== course.slug)].slice(0, 4);
    persist(recentKey, recent.value);
}
function clearRecent(): void { recent.value = []; persist(recentKey, []); announce('Riwayat pada browser ini dihapus.'); }
function rememberCardClick(event: MouseEvent): void {
    const anchor = event.target instanceof Element ? event.target.closest('a[href]') : null;
    const href = anchor?.getAttribute('href');
    const course = props.courses.data.find((item) => href === `/courses/${item.slug}`);
    if (course) rememberCourse(course);
}
function toggleCompare(course: CatalogCourse): void {
    if (compared.value.includes(course.slug)) {
        compared.value = compared.value.filter((slug) => slug !== course.slug);
    } else if (compared.value.length < 3) {
        compared.value = [...compared.value, course.slug];
        announce(`${compared.value.length} dari 3 kelas dipilih untuk dibandingkan.`);
    } else { announce('Maksimal tiga kelas. Hapus salah satu pilihan terlebih dahulu.'); }
}
function onStorage(event: StorageEvent): void {
    if (event.key === savedKey || event.key === null) saved.value = readStored(savedKey);
    if (event.key === recentKey || event.key === null) recent.value = readStored(recentKey);
}
function icsEscape(value: string): string {
    return value.replace(/\\/g, '\\\\').replace(/\r\n|\r|\n/g, '\\n').replace(/,/g, '\\,').replace(/;/g, '\\;');
}
function foldIcs(line: string): string {
    const encoder = new TextEncoder();
    let result = '', segment = '', size = 0;
    for (const char of line) {
        const bytes = encoder.encode(char).length;
        if (size + bytes > 73) { result += segment + '\r\n '; segment = ''; size = 1; }
        segment += char; size += bytes;
    }
    return result + segment;
}
function downloadPlan(): void {
    const course = plannedCourse.value;
    const date = parseDate(startDate.value);
    if (!course || !date || !planDays.value || startDate.value < today.value) {
        announce('Pilih kelas berdurasi tersedia dan tanggal mulai hari ini atau setelahnya.'); return;
    }
    const dateString = (value: Date) => `${value.getFullYear()}${String(value.getMonth() + 1).padStart(2, '0')}${String(value.getDate()).padStart(2, '0')}`;
    const next = new Date(date); next.setDate(next.getDate() + 1);
    const stamp = new Date().toISOString().replace(/[-:]/g, '').replace(/\.\d{3}Z$/, 'Z');
    const url = `${window.location.origin}/courses/${encodeURIComponent(course.slug)}`;
    const lines = [
        'BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//COURSE//Rencana Belajar//ID', 'CALSCALE:GREGORIAN',
        'BEGIN:VEVENT', `UID:course-${Date.now()}@${window.location.hostname}`, `DTSTAMP:${stamp}`,
        `DTSTART;VALUE=DATE:${dateString(date)}`, `DTEND;VALUE=DATE:${dateString(next)}`,
        `RRULE:FREQ=DAILY;COUNT=${planDays.value}`,
        `SUMMARY:${icsEscape(`Belajar ${dailyMinutes.value} menit - ${course.title}`)}`,
        `DESCRIPTION:${icsEscape(`Target mandiri ${dailyMinutes.value} menit per hari. Estimasi berdasarkan durasi materi, belum termasuk latihan.\n${url}`)}`,
        `URL:${url}`, 'TRANSP:TRANSPARENT', 'END:VEVENT', 'END:VCALENDAR',
    ];
    const blob = new Blob([lines.map(foldIcs).join('\r\n') + '\r\n'], { type: 'text/calendar;charset=utf-8' });
    const objectUrl = URL.createObjectURL(blob);
    const anchor = document.createElement('a');
    anchor.href = objectUrl; anchor.download = 'rencana-belajar.ics';
    document.body.appendChild(anchor); anchor.click(); anchor.remove();
    setTimeout(() => URL.revokeObjectURL(objectUrl), 1000);
    announce('Jadwal diunduh. Impor file .ics ke aplikasi kalender pilihanmu.');
}
onMounted(() => {
    saved.value = readStored(savedKey); recent.value = readStored(recentKey);
    const now = new Date();
    today.value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
    startDate.value = today.value;
    window.addEventListener('storage', onStorage);
});
onUnmounted(() => {
    window.removeEventListener('storage', onStorage);
    if (noticeTimer) clearTimeout(noticeTimer);
});

const faqs = [
    { q: 'Bagaimana memilih kelas yang sesuai?', a: 'Gunakan pencarian atau pilihan kategori, tingkat, dan harga. Bandingkan kelas, kemudian buka detail untuk memeriksa kurikulum, hasil belajar, dan persyaratannya.' },
    { q: 'Apakah tersedia kelas gratis?', a: 'Pilih filter Gratis untuk melihat kelas tanpa biaya. Buka detail kelas dan masuk ke akun pelajar untuk mengikuti kelas gratis yang tersedia.' },
    { q: 'Di mana daftar kelas yang saya simpan?', a: 'Gunakan tombol Tersimpan di halaman ini. Daftar disimpan pada browser dan perangkat ini, belum disinkronkan ke akun. Menghapus data browser juga akan menghapus daftar tersebut.' },
    { q: 'Apakah estimasi jadwal menjamin saya akan selesai?', a: 'Estimasi membagi durasi materi dengan target menit per hari. Waktu latihan dan pengulangan belum dihitung. File kalender berisi pengingat harian sepanjang hari; impor terlebih dahulu ke aplikasi kalender.' },
];
</script>

<template>
    <Head title="Belajar yang berbeda">
        <meta name="description" content="Temukan kelas sesuai tujuanmu. Cari topik, bandingkan kelas, simpan pilihan, dan susun jadwal belajar harian." />
    </Head>
    <div class="landing-v3" :class="{ 'has-compare': compared.length > 0 }">
        <!-- COURSE_HERO_RESTORED_V1 -->
        <OriginalLandingHero />

        <nav class="lp-subnav lp-wrap" aria-label="Jelajahi beranda">
            <div class="lp-anchor-links">
                <a href="#pilihan-kelas">Pilihan kelas</a><a href="#pilih-jalur">Bantu pilih</a><a href="#rencana-belajar">Rencana belajar</a>
            </div>
            <button class="lp-saved" type="button" @click="savedOpen = true"><Bookmark :size="16" aria-hidden="true" /> Tersimpan <span>{{ saved.length }}</span></button>
        </nav>

        <div class="lp-wrap lp-restored-search">
<form action="/courses" method="get" class="lp-search" role="search">
                    <label for="landing-search" class="sr-only">Cari topik atau nama kelas</label>
                    <Search :size="21" aria-hidden="true" />
                    <input id="landing-search" v-model="query" name="q" type="search" list="landing-suggestions" maxlength="100" placeholder="Mau belajar apa hari ini?" />
                    <button type="submit">Cari kelas <ArrowUpRight :size="18" aria-hidden="true" /></button>
                    <datalist id="landing-suggestions"><option v-for="course in courses.data" :key="course.slug" :value="course.title" /></datalist>
                </form>
        </div>

        <div class="lp-value-strip"><div class="lp-wrap"><span><Search :size="20" aria-hidden="true" /> Pilih sesuai tujuan</span><span><GitCompareArrows :size="20" aria-hidden="true" /> Bandingkan sebelum mulai</span><span><CalendarDays :size="20" aria-hidden="true" /> Atur ritme belajarmu</span></div></div>

        <section v-if="recent.length" class="lp-recent lp-wrap" aria-label="Terakhir dilihat pada browser ini"><div><span>Terakhir dilihat</span><button type="button" @click="clearRecent">Hapus riwayat</button></div><ul><li v-for="course in recent" :key="course.slug"><Link :href="`/courses/${encodeURIComponent(course.slug)}`" @click="rememberCourse(course)">{{ course.title }} <ArrowUpRight :size="15" aria-hidden="true" /></Link></li></ul></section>

        <section v-if="categories.length" class="lp-section lp-wrap" aria-labelledby="category-title">
            <div class="lp-section-head"><div><p class="lp-eyebrow">Satu langkah awal</p><h2 id="category-title">Ikuti rasa ingin tahumu.</h2></div><Link href="/courses" class="lp-text-link">Semua kategori <ArrowUpRight :size="17" aria-hidden="true" /></Link></div>
            <div class="lp-categories"><Link v-for="(category, index) in categories" :key="category.slug" :href="`/courses?category=${encodeURIComponent(category.slug)}`"><span>{{ String(index + 1).padStart(2, '0') }}</span><strong>{{ category.name }}</strong><ArrowUpRight :size="20" aria-hidden="true" /></Link></div>
        </section>

        <section id="pilih-jalur" class="lp-finder-section"><div class="lp-wrap lp-finder"><div><p class="lp-eyebrow">Biar lebih terarah</p><h2>Tujuanmu beda.<br />Pilihan kelasmu juga.</h2><p>Pilih topik, tingkat, dan anggaran. Kami arahkan ke hasil yang sesuai di katalog.</p></div><form action="/courses" method="get"><div class="lp-finder-fields"><label for="finder-category">Saya ingin belajar<select id="finder-category" name="category"><option value="">Semua topik</option><option v-for="category in categories" :key="category.slug" :value="category.slug">{{ category.name }}</option></select></label><label for="finder-level">Tingkat saya<select id="finder-level" name="level"><option value="">Semua tingkat</option><option v-for="level in levels" :key="level.value" :value="level.value">{{ level.label }}</option></select></label><label for="finder-price">Anggaran belajar<select id="finder-price" name="price"><option value="">Semua harga</option><option value="free">Mulai dari gratis</option><option value="paid">Kelas berbayar</option></select></label></div><button type="submit" class="lp-btn lp-btn-dark">Temukan kelas yang cocok <ArrowRight :size="18" aria-hidden="true" /></button></form></div></section>

        <span id="jelajahi-kursus" class="block" aria-hidden="true"></span>
        <section id="pilihan-kelas" class="lp-section lp-wrap" aria-labelledby="courses-title">
            <div class="lp-section-head"><div><p class="lp-eyebrow">Ada hal baru untuk dipelajari</p><h2 id="courses-title">Mulai dari pilihan ini.</h2><p>Pilihan dari {{ courses.data.length }} kelas terbaru. Seluruh {{ courses.total }} kelas tersedia di katalog.</p></div><Link href="/courses" class="lp-text-link">Jelajahi katalog <ArrowUpRight :size="17" aria-hidden="true" /></Link></div>
            <div class="lp-toolbar"><div class="lp-filter-buttons" role="group" aria-label="Filter pilihan kelas"><button type="button" :aria-pressed="quickFilter === 'all'" @click="quickFilter = 'all'">Semua</button><button type="button" :aria-pressed="quickFilter === 'free'" @click="quickFilter = 'free'">Gratis</button><button type="button" :aria-pressed="quickFilter === 'saved'" @click="quickFilter = 'saved'">Tersimpan</button></div><label for="landing-sort">Urutkan<select id="landing-sort" v-model="sort"><option value="newest">Terbaru</option><option value="price">Harga terendah</option><option value="duration">Durasi tersingkat</option></select></label></div>
            <p class="lp-result-count" role="status">{{ visibleCourses.length }} kelas ditampilkan<span v-if="quickFilter === 'saved'"> dari pilihan terbaru. <button type="button" @click="savedOpen = true">Lihat seluruh daftar simpan</button></span></p>
            <div v-if="visibleCourses.length" class="lp-course-grid" @click.capture="rememberCardClick"><div v-for="course in visibleCourses" :key="course.slug" class="lp-course"><CourseCard :course="course" /><div class="lp-course-actions"><button type="button" :aria-pressed="isSaved(course.slug)" :aria-label="`${isSaved(course.slug) ? 'Hapus dari simpan:' : 'Simpan:'} ${course.title}`" @click="toggleSaved(course)"><Bookmark :size="16" :fill="isSaved(course.slug) ? 'currentColor' : 'none'" aria-hidden="true" />{{ isSaved(course.slug) ? 'Tersimpan' : 'Simpan' }}</button><button type="button" :aria-pressed="compared.includes(course.slug)" :aria-label="`Bandingkan ${course.title}`" :disabled="compared.length >= 3 && !compared.includes(course.slug)" @click="toggleCompare(course)"><Check v-if="compared.includes(course.slug)" :size="16" aria-hidden="true" /><GitCompareArrows v-else :size="16" aria-hidden="true" />Bandingkan</button></div></div></div>
            <div v-else class="lp-empty"><BookOpen :size="34" aria-hidden="true" /><h3>{{ quickFilter === 'saved' ? 'Belum ada pilihan terbaru yang disimpan.' : 'Belum ada kelas pada pilihan ini.' }}</h3><p>Coba pilihan lainnya atau jelajahi seluruh katalog.</p><div><button type="button" class="lp-btn lp-btn-dark" @click="quickFilter = 'all'">Tampilkan semua pilihan</button><Link href="/courses" class="lp-text-link">Buka katalog <ArrowUpRight :size="17" aria-hidden="true" /></Link></div></div>
            <p class="lp-storage-note"><Bookmark :size="14" aria-hidden="true" />{{ storageAvailable ? 'Daftar simpan dan riwayat tersimpan di browser ini, belum tersinkron ke akun.' : 'Penyimpanan browser tidak tersedia. Daftar hanya bertahan selama halaman terbuka.' }}</p>
        </section>

        <section id="rencana-belajar" class="lp-plan-section"><div class="lp-wrap lp-plan"><div class="lp-plan-copy"><p class="lp-eyebrow">Sedikit setiap hari</p><h2>Buat waktu<br />untuk <span>bertumbuh.</span></h2><p>Tiga puluh menit hari ini bisa jadi langkah pertama. Ubah durasi kelas menjadi target harian yang masuk akal.</p><ol><li><span>01</span>Pilih kelas yang ingin kamu pelajari.</li><li><span>02</span>Tentukan waktu belajar setiap hari.</li><li><span>03</span>Unduh jadwal dan impor ke kalender.</li></ol><a href="#pilihan-kelas" class="lp-text-link">Cari kelas dulu <ArrowUpRight :size="18" aria-hidden="true" /></a></div><form class="lp-plan-card" @submit.prevent="downloadPlan"><div class="lp-plan-card-title"><CalendarDays :size="21" aria-hidden="true" /><h3>Rencana belajarku</h3><span>Mandiri</span></div><template v-if="courses.data.length"><label for="plan-course">Kelas yang ingin dipelajari<select id="plan-course" v-model="planSlug"><option v-for="course in courses.data" :key="course.slug" :value="course.slug">{{ course.title }}</option></select></label><div class="lp-plan-fields"><label for="plan-minutes">Menit per hari<select id="plan-minutes" v-model.number="dailyMinutes"><option v-for="minutes in [15, 30, 45, 60, 90]" :key="minutes" :value="minutes">{{ minutes }} menit</option></select></label><label for="plan-start">Mulai tanggal<input id="plan-start" v-model="startDate" type="date" :min="today" required /></label></div><div class="lp-estimate" role="status"><template v-if="planDays"><div><strong>{{ planDays }}</strong><span>hari belajar</span></div><p>Perkiraan selesai<br /><b>{{ endDate || 'Pilih tanggal mulai' }}</b></p></template><p v-else>Durasi kelas belum tersedia. Pilih kelas lain untuk menghitung estimasi.</p></div><p class="lp-plan-hint">Berdasarkan durasi materi, belum termasuk latihan. File kalender berisi pengingat harian sepanjang hari.</p><button class="lp-btn lp-btn-primary" type="submit" :disabled="!planDays || !endDate || startDate < today"><Download :size="17" aria-hidden="true" />Unduh jadwal (.ics)</button></template><p v-else>Rencana dapat dibuat setelah kelas diterbitkan.</p></form></div></section>

        <section class="lp-section lp-wrap lp-faq" aria-labelledby="faq-title"><div><p class="lp-eyebrow">Sebelum mulai</p><h2 id="faq-title">Masih penasaran?</h2><p>Beberapa hal yang perlu kamu tahu untuk menentukan langkah pertama.</p><Link href="/courses" class="lp-text-link">Lihat semua kelas <ArrowUpRight :size="17" aria-hidden="true" /></Link></div><div class="lp-faq-list"><details v-for="faq in faqs" :key="faq.q"><summary>{{ faq.q }}<span aria-hidden="true">+</span></summary><p>{{ faq.a }}</p></details></div></section>
        <section class="lp-teach lp-wrap"><div><p class="lp-eyebrow">Bagikan yang kamu kuasai</p><h2>Punya ilmu untuk dibagikan?</h2><p>Ajukan profil instruktur dan susun materi belajarmu.</p></div><Link href="/instructor/onboarding" class="lp-btn lp-btn-dark">Mulai jadi instruktur <ArrowUpRight :size="18" aria-hidden="true" /></Link></section>

        <div v-if="compared.length" class="lp-compare-bar" role="region" aria-label="Pilihan perbandingan"><div><GitCompareArrows :size="22" aria-hidden="true" /><p><strong>{{ compared.length }}/3 kelas dipilih</strong><span>Pilih minimal dua untuk membandingkan.</span></p></div><button type="button" class="lp-clear-compare" @click="compared = []">Reset</button><button type="button" class="lp-btn lp-btn-primary" :disabled="compared.length < 2" @click="compareOpen = true">Bandingkan <ArrowRight :size="17" aria-hidden="true" /></button></div>
        <div class="lp-notice" :class="{ 'is-visible': notice }" role="status" aria-live="polite" aria-atomic="true">{{ notice }}</div>
    </div>

    <Dialog v-model:open="savedOpen"><DialogContent class="marketplace-shell max-h-[85dvh] overflow-y-auto border-market-border bg-market-canvas text-market-ink sm:max-w-xl" :show-close-button="false"><DialogHeader><DialogTitle class="pr-10 text-2xl">Kelas tersimpan <span class="text-market-primary">({{ saved.length }})</span></DialogTitle><DialogDescription class="text-market-muted">Disimpan pada browser ini. Buka detail untuk melihat ketersediaan dan harga terbaru.</DialogDescription></DialogHeader><DialogClose class="absolute top-4 right-4 grid size-10 place-items-center rounded-full border border-market-border" aria-label="Tutup daftar tersimpan"><X :size="18" aria-hidden="true" /></DialogClose><p v-if="!saved.length" class="py-8 text-center text-market-muted">Tekan Simpan pada kelas yang ingin kamu pelajari.</p><ul v-else class="divide-y divide-market-border"><li v-for="course in saved" :key="course.slug" class="flex items-center gap-3 py-4"><Link :href="`/courses/${encodeURIComponent(course.slug)}`" class="min-w-0 flex-1 text-sm leading-6 font-semibold hover:underline" @click="rememberCourse(course); savedOpen = false">{{ course.title }}</Link><button type="button" class="grid size-11 shrink-0 place-items-center border border-market-border" :aria-label="`Hapus ${course.title} dari simpan`" @click="removeSaved(course.slug)"><X :size="17" aria-hidden="true" /></button></li></ul></DialogContent></Dialog>
    <Dialog v-model:open="compareOpen"><DialogContent class="marketplace-shell max-h-[85dvh] overflow-y-auto border-market-border bg-market-canvas text-market-ink sm:max-w-4xl" :show-close-button="false"><DialogHeader><DialogTitle class="pr-10 text-2xl">Kelas mana yang cocok untukmu?</DialogTitle><DialogDescription class="text-market-muted">Bandingkan informasi kelas. Buka kurikulum untuk memeriksa hasil belajar dan persyaratannya.</DialogDescription></DialogHeader><DialogClose class="absolute top-4 right-4 grid size-10 place-items-center rounded-full border border-market-border" aria-label="Tutup perbandingan"><X :size="18" aria-hidden="true" /></DialogClose><div class="overflow-x-auto pb-2" tabindex="0" role="region" aria-label="Tabel perbandingan kelas, geser untuk melihat semua kolom"><table class="lp-compare-table"><caption class="sr-only">Perbandingan kelas terpilih</caption><thead><tr><th scope="col">Informasi</th><th v-for="course in comparedCourses" :key="course.slug" scope="col">{{ course.title }}</th></tr></thead><tbody><tr><th scope="row">Instruktur</th><td v-for="course in comparedCourses" :key="course.slug">{{ course.instructor }}</td></tr><tr><th scope="row">Harga</th><td v-for="course in comparedCourses" :key="course.slug"><strong>{{ formatIdr(course.priceIdr) }}</strong></td></tr><tr><th scope="row">Tingkat</th><td v-for="course in comparedCourses" :key="course.slug">{{ course.level }}</td></tr><tr><th scope="row">Durasi</th><td v-for="course in comparedCourses" :key="course.slug">{{ course.durationMinutes > 0 ? formatDuration(course.durationMinutes) : 'Belum tersedia' }}</td></tr><tr><th scope="row">Kategori</th><td v-for="course in comparedCourses" :key="course.slug">{{ course.category }}</td></tr><tr><th scope="row">Ringkasan</th><td v-for="course in comparedCourses" :key="course.slug">{{ course.summary || 'Lihat detail kelas.' }}</td></tr><tr><th scope="row">Kurikulum</th><td v-for="course in comparedCourses" :key="course.slug"><Link :href="`/courses/${encodeURIComponent(course.slug)}`" class="inline-flex min-h-11 items-center gap-2 font-bold underline underline-offset-4" @click="rememberCourse(course); compareOpen = false">Lihat detail <ArrowUpRight :size="15" aria-hidden="true" /></Link></td></tr></tbody></table></div></DialogContent></Dialog>
</template>

<style scoped>
.lp-restored-search { padding-block: 20px; }
.lp-restored-search .lp-search { max-width: 700px; margin: 0 auto; }
.landing-v3 { --lp-primary: var(--market-primary, #d94a2f); --lp-muted: var(--market-muted, #655f57); --lp-border: var(--market-border, #cbc4b9); --lp-ink: var(--market-ink, #171716); --lp-paper: var(--market-canvas, #f4f1e9); --lp-surface: var(--market-surface, #fffefa); color: var(--lp-ink); background: var(--lp-paper); }
.lp-wrap { width: min(100%, 1320px); padding-inline: 40px; margin-inline: auto; }
.landing-v3 :is(button, a, input, select) { touch-action: manipulation; }
.landing-v3 button, .landing-v3 select { cursor: pointer; }
.landing-v3 :is(input, select) { min-width: 0; }
.landing-v3 button:disabled { opacity: .48; cursor: not-allowed; }
.landing-v3 :is(button, a, input, select, summary):focus-visible { outline: 3px solid var(--lp-ink); outline-offset: 4px; }
.landing-v3 :is(section[id]) { scroll-margin-top: 24px; }
.lp-subnav { display: flex; align-items: center; justify-content: space-between; gap: 16px; min-height: 65px; border-bottom: 1px solid var(--lp-border); font-size: 13px; font-weight: 600; }
.lp-anchor-links { display: flex; gap: 28px; }
.lp-anchor-links a, .lp-saved { display: inline-flex; align-items: center; min-height: 44px; }
.lp-anchor-links a:hover, .lp-text-link:hover { text-decoration: underline; text-underline-offset: 5px; }
.lp-saved { gap: 8px; flex-shrink: 0; }
.lp-saved span { display: grid; place-items: center; min-width: 25px; height: 25px; padding: 0 5px; color: var(--lp-paper); background: var(--lp-ink); border-radius: 50%; font-size: 11px; }
.lp-hero { display: grid; grid-template-columns: 1.1fr 1fr; align-items: center; gap: 70px; padding-block: 52px 64px; }
.lp-eyebrow { display: flex; align-items: center; gap: 10px; margin: 0 0 16px; color: var(--lp-muted); font-size: 12px; font-weight: 600; }
.lp-line { width: 27px; height: 2px; background: var(--lp-primary); }
.lp-hero h1 { font-family: 'Archivo', sans-serif; font-size: clamp(54px, 5.9vw, 80px); line-height: .99; letter-spacing: -.075em; font-weight: 800; margin: 0 0 24px; }
.lp-hero h1 > span, .lp-plan-copy h2 > span { color: var(--lp-primary); }
.lp-lead { max-width: 450px; color: var(--lp-muted); font-size: 16px; line-height: 1.8; }
.lp-search { display: flex; align-items: center; gap: 12px; margin-top: 28px; padding: 6px 6px 6px 16px; border: 1px solid var(--lp-ink); background: var(--lp-surface); }
.lp-search > svg { flex-shrink: 0; color: var(--lp-muted); }
.lp-search input { width: 100%; min-height: 44px; background: transparent; font-size: 14px; outline-offset: 0; }
.lp-search button { display: inline-flex; align-items: center; justify-content: center; gap: 8px; flex-shrink: 0; min-height: 46px; padding-inline: 16px; color: white; background: var(--lp-ink); font-size: 13px; font-weight: 700; }
.lp-search button:hover { background: var(--lp-primary); }
.lp-hero-links { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 16px; margin-top: 10px; font-size: 12px; }
.lp-hero-links > span { color: var(--lp-muted); }
.lp-hero-links a { display: inline-flex; gap: 5px; align-items: center; min-height: 36px; text-decoration: underline; text-underline-offset: 4px; }
.lp-real-stats { display: flex; gap: 24px; margin-top: 25px; padding-top: 22px; border-top: 1px solid var(--lp-border); }
.lp-real-stats > div { display: flex; flex-direction: column; gap: 5px; }
.lp-real-stats > div + div { border-left: 1px solid var(--lp-border); padding-left: 24px; }
.lp-real-stats strong { font-family: 'Archivo', sans-serif; font-size: 26px; line-height: 1; letter-spacing: -.05em; }
.lp-real-stats span { font-size: 11px; color: var(--lp-muted); line-height: 1.5; }
.lp-real-stats > div:last-child { flex-direction: row; align-items: center; gap: 10px; }
.lp-spotlight { position: relative; padding: 24px; color: var(--lp-paper); background: var(--lp-ink); box-shadow: 12px 12px 0 var(--lp-primary); }
.lp-spotlight-top { display: flex; justify-content: space-between; margin-bottom: 19px; font-size: 11px; letter-spacing: .05em; }
.lp-spotlight-top > span:first-child::before { content: ''; display: inline-block; width: 6px; height: 6px; margin-right: 8px; border-radius: 50%; background: var(--lp-primary); }
.lp-spotlight-image { display: block; position: relative; aspect-ratio: 16/9; overflow: hidden; background: var(--lp-paper); }
.lp-spotlight-image img { width: 100%; height: 100%; object-fit: cover; }
.lp-image-label { position: absolute; left: 12px; top: 12px; padding: 5px 10px; background: var(--lp-paper); color: var(--lp-ink); font-size: 11px; font-weight: 600; }
.lp-art-fallback { display: flex; height: 100%; align-items: center; justify-content: center; flex-direction: column; gap: 10px; color: var(--lp-primary); }
.lp-spotlight-copy { min-height: 125px; padding-top: 20px; }
.lp-spotlight-copy > p { margin-bottom: 8px; color: #f3ab8c; font-size: 11px; font-weight: 600; }
.lp-spotlight-copy h2 { font-family: 'Archivo', sans-serif; font-size: clamp(20px, 2vw, 25px); font-weight: 600; line-height: 1.25; letter-spacing: -.04em; }
.lp-spotlight-copy > span { display: block; margin-top: 10px; color: #c9c3b9; font-size: 12px; }
.lp-spotlight-meta { display: flex; justify-content: space-between; gap: 15px; padding-block: 19px; font-size: 12px; }
.lp-spotlight-meta > span { display: inline-flex; align-items: center; gap: 6px; color: #c9c3b9; }
.lp-spotlight-meta > strong { font-size: 17px; }
.lp-spotlight-bottom { display: flex; align-items: center; justify-content: space-between; gap: 14px; border-top: 1px solid #57534e; padding-top: 16px; }
.lp-spotlight-bottom > a { display: inline-flex; align-items: center; gap: 9px; min-height: 44px; font-size: 13px; font-weight: 600; }
.lp-cycle { display: flex; gap: 7px; }
.lp-cycle button { display: grid; place-items: center; width: 44px; height: 44px; border: 1px solid #77716a; }
.lp-cycle button:hover { background: var(--lp-primary); border-color: var(--lp-primary); }
.lp-no-courses { min-height: 400px; display: flex; flex-direction: column; justify-content: center; gap: 20px; }
.lp-no-courses h2 { font-size: 32px; line-height: 1.15; font-weight: 700; }
.lp-no-courses p { color: #c9c3b9; }
.lp-no-courses a { display: inline-flex; gap: 10px; align-items: center; text-decoration: underline; }
.lp-value-strip { border-top: 1px solid var(--lp-border); border-bottom: 1px solid var(--lp-border); }
.lp-value-strip > div { display: grid; grid-template-columns: repeat(3, 1fr); }
.lp-value-strip span { display: flex; align-items: center; justify-content: center; gap: 12px; padding-block: 24px; font-size: 13px; font-weight: 600; }
.lp-value-strip span + span { border-left: 1px solid var(--lp-border); }
.lp-value-strip svg { color: var(--lp-primary); }
.lp-section { padding-block: 64px; }
.lp-section-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 28px; }
.lp-section-head h2, .lp-finder > div > h2, .lp-plan-copy > h2, .lp-faq > div > h2, .lp-teach > div > h2 { font-family: 'Archivo', sans-serif; font-size: clamp(28px, 3.2vw, 40px); font-weight: 700; line-height: 1.13; letter-spacing: -.055em; }
.lp-section-head p:not(.lp-eyebrow), .lp-faq > div > p:not(.lp-eyebrow), .lp-teach p:not(.lp-eyebrow) { margin-top: 12px; font-size: 14px; line-height: 1.7; color: var(--lp-muted); }
.lp-text-link { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; flex-shrink: 0; font-size: 13px; font-weight: 600; }
.lp-categories { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); border-top: 1px solid var(--lp-border); border-left: 1px solid var(--lp-border); }
.lp-categories a { display: flex; align-items: center; gap: 14px; min-height: 90px; padding: 18px; border-right: 1px solid var(--lp-border); border-bottom: 1px solid var(--lp-border); transition: background .16s; }
.lp-categories a:hover { background: var(--market-sage); }
.lp-categories span { color: var(--lp-muted); font-size: 11px; }
.lp-categories strong { flex: 1; min-width: 0; font-size: 15px; font-weight: 600; }
.lp-categories svg { flex-shrink: 0; }
.lp-finder-section { background: var(--market-sage); }
.lp-finder { display: grid; grid-template-columns: .8fr 1.2fr; gap: 65px; padding-block: 46px; align-items: center; }
.lp-finder > div > p:last-child { margin-top: 16px; max-width: 350px; color: var(--lp-muted); font-size: 14px; line-height: 1.8; }
.lp-finder-fields { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
.landing-v3 :is(.lp-finder, .lp-plan-card) label { display: flex; flex-direction: column; gap: 9px; font-size: 12px; font-weight: 600; }
.landing-v3 :is(.lp-finder, .lp-plan-card) :is(select, input) { width: 100%; min-height: 48px; padding: 10px 12px; border: 1px solid var(--lp-border); background: var(--lp-surface); color: var(--lp-ink); font-size: 14px; font-weight: 400; }
.lp-finder form > button { margin-top: 18px; }
.lp-btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; min-height: 48px; padding: 12px 20px; font-size: 13px; font-weight: 600; text-align: center; }
.lp-btn-dark { color: white; background: var(--lp-ink); }
.lp-btn-dark:hover { background: #37332e; }
.lp-btn-primary { color: white; background: var(--market-primary-hover, #b83725); }
.lp-btn-primary:hover { background: var(--lp-ink); }
.lp-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding-block: 16px; border-top: 1px solid var(--lp-border); border-bottom: 1px solid var(--lp-border); }
.lp-filter-buttons { display: flex; gap: 6px; }
.lp-filter-buttons button { min-height: 44px; padding: 10px 18px; font-size: 13px; font-weight: 600; }
.lp-filter-buttons button[aria-pressed=true] { background: var(--lp-ink); color: var(--lp-paper); }
.lp-toolbar > label { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--lp-muted); }
.lp-toolbar select { min-height: 44px; padding: 8px; border: 1px solid var(--lp-border); background: var(--lp-surface); color: var(--lp-ink); font-size: 13px; }
.lp-result-count { min-height: 45px; padding-block: 15px; color: var(--lp-muted); font-size: 12px; }
.lp-result-count button { text-decoration: underline; text-underline-offset: 4px; }
.lp-course-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 34px 26px; }
.lp-course { min-width: 0; display: flex; flex-direction: column; }
.lp-course :deep(article) { height: auto; flex: 1; border: 0; padding-bottom: 16px; }
.lp-course :deep(article > a) { border-radius: 2px; }
.lp-course :deep(h2) { font-family: inherit; font-size: 18px; line-height: 1.35; letter-spacing: -.025em; }
.lp-course-actions { display: flex; gap: 12px; border-top: 1px solid var(--lp-border); border-bottom: 1px solid var(--lp-border); }
.lp-course-actions button { flex: 1; display: flex; justify-content: center; align-items: center; gap: 7px; min-height: 46px; font-size: 12px; font-weight: 500; }
.lp-course-actions button[aria-pressed=true] { color: var(--market-primary-hover); font-weight: 700; }
.lp-course-actions button:hover:not(:disabled) { background: var(--market-sage); }
.lp-storage-note { display: flex; align-items: flex-start; gap: 7px; margin-top: 24px; color: var(--lp-muted); font-size: 11px; line-height: 1.6; }
.lp-storage-note svg { flex-shrink: 0; margin-top: 2px; }
.lp-empty { display: flex; align-items: center; flex-direction: column; gap: 15px; padding: 50px 20px; border: 1px dashed var(--lp-border); text-align: center; }
.lp-empty h3 { font-size: 21px; font-weight: 600; }
.lp-empty p { font-size: 14px; color: var(--lp-muted); }
.lp-empty > div { display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; }
.lp-plan-section { background: var(--lp-ink); color: var(--lp-paper); }
.lp-plan { display: grid; grid-template-columns: 1fr 1fr; align-items: center; gap: 90px; padding-block: 64px; }
.lp-plan-copy > .lp-eyebrow { color: #c9c3b9; }
.landing-v3 .lp-plan-copy h2 { font-size: clamp(36px, 4.3vw, 55px); }
.lp-plan-copy h2 > span { color: #f3ab8c; }
.lp-plan-copy > p:not(.lp-eyebrow) { margin-top: 20px; max-width: 380px; color: #c9c3b9; font-size: 15px; line-height: 1.8; }
.lp-plan-copy ol { display: grid; gap: 17px; margin-block: 28px; font-size: 13px; }
.lp-plan-copy li { display: flex; gap: 15px; align-items: center; }
.lp-plan-copy li > span { color: #f3ab8c; font-size: 11px; }
.lp-plan-card { padding: 28px; background: var(--lp-paper); color: var(--lp-ink); }
.lp-plan-card-title { display: flex; align-items: center; gap: 10px; padding-bottom: 20px; margin-bottom: 24px; border-bottom: 1px solid var(--lp-border); }
.lp-plan-card-title h3 { font-size: 17px; font-weight: 600; letter-spacing: -.03em; }
.lp-plan-card-title > span { margin-left: auto; padding: 4px 8px; border: 1px solid var(--lp-border); font-size: 10px; }
.lp-plan-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-block: 20px; }
.lp-estimate { display: flex; justify-content: space-between; align-items: center; gap: 20px; padding-block: 20px; border-block: 1px solid var(--lp-border); }
.lp-estimate > div { display: flex; flex-direction: column; gap: 3px; }
.lp-estimate strong { font-family: 'Archivo', sans-serif; font-size: 46px; line-height: 1; letter-spacing: -.07em; font-weight: 700; }
.lp-estimate span { font-size: 12px; color: var(--lp-muted); }
.lp-estimate > p { font-size: 12px; line-height: 1.9; color: var(--lp-muted); }
.lp-estimate b { font-weight: 600; color: var(--lp-ink); }
.lp-plan-hint { margin-block: 16px; font-size: 11px; color: var(--lp-muted); line-height: 1.7; }
.lp-plan-card .lp-btn { width: 100%; }
.lp-faq { display: grid; grid-template-columns: .75fr 1.25fr; gap: 65px; }
.lp-faq > div > .lp-text-link { margin-top: 20px; }
.lp-faq-list details { border-top: 1px solid var(--lp-border); }
.lp-faq-list details:last-child { border-bottom: 1px solid var(--lp-border); }
.lp-faq-list summary { display: flex; align-items: center; justify-content: space-between; gap: 20px; min-height: 70px; padding-block: 18px; list-style: none; cursor: pointer; font-size: 14px; font-weight: 600; }
.lp-faq-list summary::-webkit-details-marker { display: none; }
.lp-faq-list summary > span { font-size: 24px; font-weight: 400; }
.lp-faq-list details[open] summary > span { transform: rotate(45deg); }
.lp-faq-list details > p { padding: 0 30px 22px 0; color: var(--lp-muted); font-size: 14px; line-height: 1.9; }
.lp-teach { display: flex; justify-content: space-between; align-items: center; gap: 30px; padding-block: 35px; border-top: 1px solid var(--lp-border); margin-bottom: 30px; }
.landing-v3 .lp-teach h2 { font-size: 28px; }
.lp-teach .lp-eyebrow { margin-bottom: 10px; }
.lp-recent { padding-block: 25px; border-bottom: 1px solid var(--lp-border); }
.lp-recent > div { display: flex; align-items: center; justify-content: space-between; font-size: 12px; font-weight: 600; }
.lp-recent > div > button { min-height: 44px; font-size: 11px; color: var(--lp-muted); text-decoration: underline; }
.lp-recent ul { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 5px; }
.lp-recent li { flex-shrink: 0; max-width: 300px; }
.lp-recent li > a { display: flex; align-items: center; justify-content: space-between; gap: 10px; min-height: 48px; padding: 10px 14px; border: 1px solid var(--lp-border); font-size: 12px; }
.lp-recent svg { flex-shrink: 0; }
.lp-compare-bar { position: fixed; bottom: max(16px, env(safe-area-inset-bottom)); left: 50%; transform: translateX(-50%); z-index: 40; display: flex; align-items: center; gap: 20px; width: min(720px, calc(100% - 32px)); padding: 16px 20px; border: 1px solid #57534e; background: var(--lp-ink); color: var(--lp-paper); box-shadow: 0 8px 28px #0003; }
.lp-compare-bar > div { display: flex; align-items: center; gap: 14px; flex: 1; }
.lp-compare-bar p { display: flex; flex-direction: column; gap: 5px; font-size: 13px; }
.lp-compare-bar p > span { color: #c9c3b9; font-size: 11px; }
.lp-clear-compare { min-height: 44px; font-size: 12px; text-decoration: underline; text-underline-offset: 4px; }
.has-compare { padding-bottom: 110px; }
.lp-notice { position: fixed; bottom: 18px; left: 50%; transform: translateX(-50%); z-index: 60; max-width: calc(100% - 32px); width: max-content; padding: 0; color: white; background: var(--lp-ink); font-size: 13px; line-height: 1.6; pointer-events: none; }
.lp-notice.is-visible { padding: 12px 18px; border: 1px solid #77716a; }
.has-compare .lp-notice { bottom: 126px; }
.lp-compare-table { width: 100%; min-width: 560px; border-collapse: collapse; text-align: left; font-size: 13px; line-height: 1.7; }
.lp-compare-table :is(th, td) { padding: 14px; border: 1px solid var(--market-border); vertical-align: top; overflow-wrap: anywhere; }
.lp-compare-table thead th { background: var(--market-sage); font-weight: 700; min-width: 160px; }
.lp-compare-table tbody th { width: 110px; font-size: 12px; font-weight: 600; }
@media (min-width: 1440px) { .lp-hero { gap: 95px; } }
@media (max-width: 1023px) {
    .lp-wrap { padding-inline: 28px; }
    .lp-hero { gap: 30px; }
    .lp-hero h1 { font-size: 62px; }
    .lp-real-stats { gap: 16px; }
    .lp-real-stats > div + div { padding-left: 16px; }
    .lp-real-stats > div:last-child { display: none; }
    .lp-search { flex-wrap: wrap; }
    .lp-search input { flex: 1; width: auto; }
    .lp-search button { width: 100%; }
    .lp-spotlight { padding: 20px; box-shadow: 7px 7px 0 var(--lp-primary); }
    .lp-value-strip span { font-size: 12px; gap: 8px; }
    .lp-finder, .lp-plan, .lp-faq { gap: 32px; }
    .lp-finder-fields { grid-template-columns: 1fr; gap: 14px; }
    .lp-course-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .lp-categories { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .lp-plan-card { padding: 20px; }
    .lp-plan-fields { grid-template-columns: 1fr; }
}
@media (max-width: 639px) {
    .lp-wrap { padding-inline: 20px; }
    .lp-subnav { flex-wrap: wrap; gap: 0; padding-top: 5px; padding-bottom: 8px; }
    .lp-anchor-links { gap: 16px; font-size: 11px; }
    .lp-saved { margin-left: auto; font-size: 12px; }
    .lp-hero { grid-template-columns: 1fr; padding-top: 30px; padding-bottom: 40px; gap: 30px; }
    .lp-hero h1 { font-size: clamp(52px, 15vw, 72px); }
    .lp-lead { font-size: 14px; }
    .lp-search { gap: 8px; }
    .lp-search input { min-width: 0; width: 0; font-size: 14px; }
    .lp-search button { width: auto; padding-inline: 12px; font-size: 12px; }
    .lp-search button > svg { display: none; }
    .lp-real-stats { margin-top: 16px; padding-top: 17px; }
    .lp-real-stats > div:last-child { display: flex; }
    .lp-real-stats > div:last-child > svg { display: none; }
    .lp-spotlight { margin-right: 7px; }
    .lp-spotlight-copy { min-height: 110px; }
    .lp-value-strip > div { grid-template-columns: 1fr; padding-block: 8px; }
    .lp-value-strip span { justify-content: flex-start; padding-block: 12px; font-size: 13px; }
    .lp-value-strip span + span { border-left: 0; }
    .lp-section { padding-block: 42px; }
    .lp-section-head { display: block; margin-bottom: 20px; }
    .lp-section-head > a { margin-top: 10px; }
    .lp-categories a { padding: 14px 10px; gap: 8px; }
    .lp-categories strong { font-size: 13px; }
    .lp-categories a > svg { width: 16px; }
    .lp-finder, .lp-plan, .lp-faq { grid-template-columns: 1fr; gap: 28px; }
    .lp-finder { padding-block: 35px; }
    .lp-finder form > button { width: 100%; }
    .lp-toolbar { align-items: flex-start; flex-direction: column; gap: 12px; }
    .lp-filter-buttons { width: 100%; gap: 3px; }
    .lp-filter-buttons button { flex: 1; padding-inline: 10px; }
    .lp-toolbar > label { width: 100%; justify-content: space-between; }
    .lp-toolbar select { min-width: 170px; }
    .lp-course-grid { grid-template-columns: 1fr; gap: 28px; }
    .lp-course-actions button { min-height: 48px; }
    .lp-plan { padding-block: 42px; }
    .lp-plan-card { padding: 20px; }
    .lp-plan-fields { grid-template-columns: 1fr; }
    .lp-plan-card-title { gap: 8px; }
    .lp-plan-card-title h3 { font-size: 16px; }
    .lp-teach { flex-direction: column; align-items: flex-start; gap: 20px; }
    .lp-teach .lp-btn { width: 100%; }
    .lp-compare-bar { gap: 10px; flex-wrap: wrap; padding: 12px; bottom: max(8px, env(safe-area-inset-bottom)); width: calc(100% - 16px); }
    .lp-compare-bar > div { gap: 8px; }
    .lp-compare-bar > div > svg { display: none; }
    .lp-compare-bar p { font-size: 12px; }
    .lp-compare-bar p > span { display: none; }
    .lp-compare-bar .lp-btn { padding: 10px 12px; font-size: 12px; min-height: 44px; }
    .lp-compare-bar .lp-btn svg { display: none; }
    .has-compare .lp-notice { bottom: 95px; }
}
@media (prefers-reduced-motion: reduce) { .landing-v3 *, .landing-v3 *::before, .landing-v3 *::after { animation: none !important; transition: none !important; scroll-behavior: auto !important; } }
</style>
