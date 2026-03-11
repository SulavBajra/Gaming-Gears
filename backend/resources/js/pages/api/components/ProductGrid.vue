<script setup lang="ts">
import Card from '@/components/ui/card/Card.vue';
import type { ProductHome } from '@/types';

defineProps<{
    products: ProductHome[];
}>();
</script>

<template>
    <div
        class="grid grid-cols-2 gap-4 p-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5"
    >
        <Card
            v-for="product in products"
            :key="product.id"
            class="flex h-52 w-56 flex-col justify-between px-4 py-4"
        >
            <CardContent>
                {{ product.name }}
            </CardContent>
            <CardFooter>
                <p>{{ product.price }}</p>
            </CardFooter>
        </Card>
    </div>
</template>
