<script setup lang="ts">
import { Card } from '@/components/ui/card';
import type { ProductHome } from '@/types';

defineProps<{
    products: ProductHome[];
}>();

const getThumbnail = (product: ProductHome): string | null => {
    const media = product.media?.find((m) => m.collection_name === 'thumbnail');
    return media?.original_url ?? null;
};
</script>

<template>
    <div
        class="grid grid-cols-2 gap-5 p-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5"
    >
        <Card
            v-for="product in products"
            :key="product.id"
            class="group flex cursor-pointer flex-col overflow-hidden rounded-xl border border-border/60 bg-card shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
        >
            <!-- Image -->
            <div
                class="relative h-52 w-full overflow-hidden rounded-t-xl bg-muted"
            >
                <img
                    v-if="getThumbnail(product)"
                    :src="getThumbnail(product)!"
                    :alt="product.name"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                />
                <div
                    v-else
                    class="flex h-full w-full flex-col items-center justify-center gap-1 text-muted-foreground"
                >
                    <svg
                        class="h-8 w-8 opacity-30"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5M21 3.75H3A.75.75 0 0 0 2.25 4.5v15"
                        />
                    </svg>
                    <span class="text-xs">No image</span>
                </div>

                <!-- Brand badge -->
                <span
                    class="absolute top-2 left-2 rounded-full bg-background/80 px-2 py-0.5 text-xs font-medium text-foreground backdrop-blur-sm"
                >
                    {{ product.brand.name }}
                </span>

                <!-- Category badge -->
                <span
                    class="absolute top-2 right-2 rounded-full bg-background/80 px-2 py-0.5 text-xs font-medium text-foreground backdrop-blur-sm"
                >
                    {{ product.category.name }}
                </span>
            </div>

            <!-- Info -->
            <div class="flex flex-1 flex-col justify-between gap-2 px-4 py-3">
                <p class="line-clamp-2 text-sm font-medium text-foreground">
                    {{ product.name }}
                </p>

                <div class="flex items-center justify-between">
                    <p
                        class="text-sm font-semibold tracking-tight text-foreground"
                    >
                        NPR {{ product.price }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                        {{ product.gender.name }}
                    </p>
                </div>
            </div>
        </Card>
    </div>
</template>
