<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import InstructorLayout from '@/layouts/InstructorLayout.vue';

const props = defineProps<{
    profile: null | {
        headline: string | null;
        bio: string | null;
        status: 'pending' | 'approved' | 'suspended';
    };
}>();
const form = useForm({ headline: '', bio: '' });
function submit(): void {
    form.post('/instructor/onboarding');
}
</script>

<template>
    <Head title="Pengajuan instruktur" />
    <InstructorLayout>
        <div class="grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <p
                    class="text-xs font-bold tracking-[0.1em] text-market-primary uppercase"
                >
                    Mengajar di marketplace
                </p>
                <h2 class="mt-3 text-3xl font-bold tracking-[-0.04em]">
                    Bagikan kompetensi yang dapat dibuktikan.
                </h2>
                <p class="mt-4 max-w-md text-sm leading-7 text-market-muted">
                    Setiap instruktur melalui verifikasi. Kursus yang
                    diterbitkan juga melewati review sebelum bisa dibeli
                    peserta.
                </p>
                <ol class="mt-8 space-y-3 text-sm text-market-muted">
                    <li>
                        <strong class="text-market-ink">01.</strong> Kirim
                        profil profesional.
                    </li>
                    <li>
                        <strong class="text-market-ink">02.</strong> Tunggu
                        persetujuan pengelola.
                    </li>
                    <li>
                        <strong class="text-market-ink">03.</strong> Susun
                        materi dan ajukan publikasi.
                    </li>
                </ol>
            </div>
            <div
                class="rounded-market-md border border-market-border bg-market-surface p-6 lg:col-span-7 lg:p-8"
            >
                <template v-if="props.profile">
                    <p
                        class="text-xs font-bold tracking-wider text-market-primary uppercase"
                    >
                        Status pendaftaran
                    </p>
                    <h3 class="mt-3 text-2xl font-bold">
                        {{
                            props.profile.status === 'approved'
                                ? 'Akun instruktur aktif'
                                : props.profile.status === 'suspended'
                                  ? 'Akun sedang ditangguhkan'
                                  : 'Menunggu verifikasi'
                        }}
                    </h3>
                    <p class="mt-3 text-market-muted">
                        {{ props.profile.headline }}
                    </p>
                    <p class="mt-5 text-sm leading-7 whitespace-pre-line">
                        {{ props.profile.bio }}
                    </p>
                    <Link
                        v-if="props.profile.status === 'approved'"
                        href="/instructor"
                        class="mt-6 inline-flex min-h-11 items-center rounded-market-sm bg-market-primary px-5 font-semibold text-white"
                        >Buka Studio →</Link
                    >
                    <p
                        v-else
                        class="mt-6 border-t border-market-border pt-5 text-sm text-market-muted"
                    >
                        Tim admin akan meninjau pengajuanmu. Pengajuan berulang
                        dinonaktifkan.
                    </p>
                </template>
                <form v-else class="space-y-5" @submit.prevent="submit">
                    <h3 class="text-xl font-semibold">Profil profesional</h3>
                    <div>
                        <label
                            for="headline"
                            class="mb-2 block text-sm font-semibold"
                            >Keahlian utama</label
                        >
                        <input
                            id="headline"
                            v-model="form.headline"
                            required
                            minlength="10"
                            maxlength="150"
                            placeholder="Contoh: Auditor internal dan fasilitator ISO 9001"
                            class="min-h-12 w-full rounded-market-sm border border-market-border px-4 outline-none focus:border-market-primary"
                        />
                        <p
                            v-if="form.errors.headline"
                            class="mt-1 text-sm text-red-700"
                        >
                            {{ form.errors.headline }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="bio"
                            class="mb-2 block text-sm font-semibold"
                            >Pengalaman dan kompetensi</label
                        >
                        <textarea
                            id="bio"
                            v-model="form.bio"
                            required
                            minlength="50"
                            rows="7"
                            maxlength="5000"
                            placeholder="Jelaskan pengalaman, sertifikasi profesional, dan topik pelatihan yang dikuasai."
                            class="w-full rounded-market-sm border border-market-border p-4 outline-none focus:border-market-primary"
                        />
                        <p
                            v-if="form.errors.bio"
                            class="mt-1 text-sm text-red-700"
                        >
                            {{ form.errors.bio }}
                        </p>
                    </div>
                    <button
                        :disabled="form.processing"
                        class="min-h-12 rounded-market-sm bg-market-primary px-6 font-semibold text-white disabled:opacity-50"
                    >
                        Kirim pengajuan
                    </button>
                </form>
            </div>
        </div>
    </InstructorLayout>
</template>
