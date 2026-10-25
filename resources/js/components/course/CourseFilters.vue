<script setup lang="ts">
import { reactive, watch } from 'vue';
import type { CatalogFilters, FilterOption } from '@/types/catalog';

const props = defineProps<{
    filters: CatalogFilters;
    categories: Array<{ name: string; slug: string }>;
    levels: FilterOption[];
    idPrefix: string;
}>();

const emit = defineEmits<{
    apply: [filters: CatalogFilters];
    reset: [];
}>();

const local = reactive<CatalogFilters>({ ...props.filters });

watch(
    () => props.filters,
    (filters) => Object.assign(local, filters),
    { deep: true },
);
</script>

<template>
    <form class="space-y-5" @submit.prevent="emit('apply', { ...local })">
        <div>
            <label
                :for="`${idPrefix}-q`"
                class="mb-2 block text-sm font-semibold"
                >Kata kunci</label
            >
            <input
                :id="`${idPrefix}-q`"
                v-model="local.q"
                type="search"
                class="h-11 w-full rounded-market-sm border border-market-border bg-market-surface px-3 text-sm outline-none focus:border-market-primary focus:ring-2 focus:ring-market-primary/20"
                placeholder="Contoh: audit internal"
            />
        </div>

        <div>
            <label
                :for="`${idPrefix}-category`"
                class="mb-2 block text-sm font-semibold"
                >Kategori</label
            >
            <select
                :id="`${idPrefix}-category`"
                v-model="local.category"
                class="h-11 w-full rounded-market-sm border border-market-border bg-market-surface px-3 text-sm outline-none focus:border-market-primary focus:ring-2 focus:ring-market-primary/20"
            >
                <option value="">Semua kategori</option>
                <option
                    v-for="category in categories"
                    :key="category.slug"
                    :value="category.slug"
                >
                    {{ category.name }}
                </option>
            </select>
        </div>

        <div>
            <label
                :for="`${idPrefix}-level`"
                class="mb-2 block text-sm font-semibold"
                >Tingkat</label
            >
            <select
                :id="`${idPrefix}-level`"
                v-model="local.level"
                class="h-11 w-full rounded-market-sm border border-market-border bg-market-surface px-3 text-sm outline-none focus:border-market-primary focus:ring-2 focus:ring-market-primary/20"
            >
                <option value="">Semua tingkat</option>
                <option
                    v-for="level in levels"
                    :key="level.value"
                    :value="level.value"
                >
                    {{ level.label }}
                </option>
            </select>
        </div>

        <div>
            <label
                :for="`${idPrefix}-price`"
                class="mb-2 block text-sm font-semibold"
                >Harga</label
            >
            <select
                :id="`${idPrefix}-price`"
                v-model="local.price"
                class="h-11 w-full rounded-market-sm border border-market-border bg-market-surface px-3 text-sm outline-none focus:border-market-primary focus:ring-2 focus:ring-market-primary/20"
            >
                <option value="">Semua harga</option>
                <option value="free">Gratis</option>
                <option value="paid">Berbayar</option>
            </select>
        </div>

        <div>
            <label
                :for="`${idPrefix}-sort`"
                class="mb-2 block text-sm font-semibold"
                >Urutkan</label
            >
            <select
                :id="`${idPrefix}-sort`"
                v-model="local.sort"
                class="h-11 w-full rounded-market-sm border border-market-border bg-market-surface px-3 text-sm outline-none focus:border-market-primary focus:ring-2 focus:ring-market-primary/20"
            >
                <option value="newest">Terbaru</option>
                <option value="price_asc">Harga terendah</option>
                <option value="price_desc">Harga tertinggi</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-1">
            <button
                type="button"
                class="min-h-11 rounded-market-sm border border-market-border px-4 text-sm font-semibold hover:border-market-ink"
                @click="emit('reset')"
            >
                Reset
            </button>
            <button
                type="submit"
                class="min-h-11 rounded-market-sm bg-market-primary px-4 text-sm font-semibold text-white hover:bg-market-primary-hover"
            >
                Terapkan
            </button>
        </div>
    </form>
</template>
