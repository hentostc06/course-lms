<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import MarketplaceLayout from '@/layouts/MarketplaceLayout.vue';

defineProps<{
    categories: Array<{
        id: number;
        name: string;
        slug: string;
        is_active: boolean;
    }>;
}>();
const form = useForm({ name: '', description: '' });
function save(): void {
    form.post('/moderation/categories', { onSuccess: () => form.reset() });
}
</script>

<template>
    <Head title="Kategori pelatihan" />
    <MarketplaceLayout>
        <div class="mx-auto max-w-market px-4 py-10 sm:px-6 lg:px-8">
            <nav class="mb-6 text-sm text-market-muted">
                <Link
                    href="/moderation/courses"
                    class="underline underline-offset-4"
                    >Moderasi</Link
                >
                / Kategori
            </nav>
            <div class="grid gap-10 lg:grid-cols-12">
                <section class="lg:col-span-5">
                    <p
                        class="mb-2 text-xs font-bold tracking-wider text-market-primary uppercase"
                    >
                        Taksonomi pembelajaran
                    </p>
                    <h1 class="text-3xl font-bold tracking-tight">
                        Kategori kursus
                    </h1>
                    <p class="mt-3 text-sm leading-7 text-market-muted">
                        Gunakan kategori yang mudah dipahami peserta. Jangan
                        membuat terlalu banyak kategori yang tumpang tindih.
                    </p>
                    <form
                        class="mt-8 space-y-5 rounded-market-md border border-market-border bg-market-surface p-6"
                        @submit.prevent="save"
                    >
                        <div>
                            <label
                                for="name"
                                class="mb-2 block text-sm font-semibold"
                                >Nama kategori</label
                            ><input
                                id="name"
                                v-model="form.name"
                                minlength="3"
                                maxlength="120"
                                required
                                class="min-h-12 w-full rounded-market-sm border border-market-border px-3"
                                placeholder="Audit & Kepatuhan"
                            />
                            <p
                                v-if="form.errors.name"
                                class="mt-1 text-sm text-red-700"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>
                        <div>
                            <label
                                for="desc"
                                class="mb-2 block text-sm font-semibold"
                                >Deskripsi</label
                            ><textarea
                                id="desc"
                                v-model="form.description"
                                rows="4"
                                maxlength="1000"
                                class="w-full rounded-market-sm border border-market-border p-3"
                            />
                        </div>
                        <button
                            :disabled="form.processing"
                            class="min-h-11 rounded-market-sm bg-market-primary px-5 text-sm font-semibold text-white disabled:opacity-50"
                        >
                            Simpan kategori
                        </button>
                    </form>
                </section>
                <section class="lg:col-span-7">
                    <h2 class="mb-5 text-xl font-semibold">
                        Kategori tersedia ({{ categories.length }})
                    </h2>
                    <div
                        v-if="categories.length === 0"
                        class="rounded-market-md border border-dashed border-market-border p-10 text-market-muted"
                    >
                        Belum ada kategori. Tambahkan kategori pertama untuk
                        memulai.
                    </div>
                    <div
                        v-else
                        class="divide-y divide-market-border rounded-market-md border border-market-border bg-market-surface"
                    >
                        <div
                            v-for="item in categories"
                            :key="item.id"
                            class="flex flex-wrap items-center justify-between gap-3 p-4"
                        >
                            <div>
                                <h3 class="font-semibold">{{ item.name }}</h3>
                                <p class="mt-1 text-xs text-market-muted">
                                    /{{ item.slug }}
                                </p>
                            </div>
                            <span
                                class="text-xs font-semibold text-market-primary"
                                >{{
                                    item.is_active ? 'Aktif' : 'Nonaktif'
                                }}</span
                            >
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </MarketplaceLayout>
</template>
