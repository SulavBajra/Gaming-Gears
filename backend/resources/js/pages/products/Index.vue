<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Product {
    id: number;
    name: string;
    slug: string;
    is_active: boolean;
    brand: { name: string };
    category: { name: string };
    product_variants_min_price: number | null;
    product_variants_max_price: number | null;
    product_variants_sum_stock_qty: number;
}

interface PaginatedProducts {
    data: Product[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
}

defineProps<{ products: PaginatedProducts }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Products', href: route('products.index') },
];

const formatPrice = (min: number | null, max: number | null): string => {
    if (!min) return '—';
    if (min === max) return `$${Number(min).toFixed(2)}`;
    return `Rs.${Number(min).toFixed(2)} – Rs.${Number(max).toFixed(2)}`;
};

const goToPage = (page: number) => {
    router.get(
        route('products.index'),
        { page },
        { preserveScroll: true, preserveState: true },
    );
};
</script>

<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle class="text-xl text-primary"
                            >Products</CardTitle
                        >
                        <p class="text-sm text-muted-foreground">
                            Manage all of {{ products.total }} products here
                        </p>
                    </div>
                    <Link :href="route('products.create')">
                        <Button> <Plus class="h-4 w-4" /> Add Product</Button>
                    </Link>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>S.N</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Brand</TableHead>
                                <TableHead>Category</TableHead>
                                <TableHead>Price Range</TableHead>
                                <TableHead>Stock</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead class="text-right"
                                    >Actions</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="(product, index) in products.data"
                                :key="product.id"
                            >
                                <TableCell class="text-muted-foreground">
                                    {{
                                        (products.current_page - 1) *
                                            products.per_page +
                                        index +
                                        1
                                    }}
                                </TableCell>
                                <TableCell class="font-medium">{{
                                    product.name
                                }}</TableCell>
                                <TableCell>{{ product.brand.name }}</TableCell>
                                <TableCell>{{
                                    product.category.name
                                }}</TableCell>
                                <TableCell>
                                    {{
                                        formatPrice(
                                            product.product_variants_min_price,
                                            product.product_variants_max_price,
                                        )
                                    }}
                                </TableCell>
                                <TableCell>
                                    <span
                                        :class="
                                            product.product_variants_sum_stock_qty ===
                                            0
                                                ? 'font-medium text-destructive'
                                                : ''
                                        "
                                    >
                                        {{
                                            product.product_variants_sum_stock_qty ??
                                            0
                                        }}
                                    </span>
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            product.is_active
                                                ? 'default'
                                                : 'secondary'
                                        "
                                    >
                                        {{
                                            product.is_active
                                                ? 'Active'
                                                : 'Inactive'
                                        }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-right">
                                    <div>
                                        <Link
                                            :href="
                                                route(
                                                    'products.edit',
                                                    product.id,
                                                )
                                            "
                                        >
                                            <Button variant="ghost" size="sm"
                                                ><Pencil
                                                    class="h-4 w-4 text-shadow-green-800"
                                            /></Button>
                                        </Link>
                                        <Link
                                            :href="
                                                route(
                                                    'products.destroy',
                                                    product.id,
                                                )
                                            "
                                            method="delete"
                                            as="button"
                                        >
                                            <Button variant="ghost" size="sm"
                                                ><Trash class="text-red-500"
                                            /></Button>
                                        </Link>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <div class="mt-6 flex items-center justify-between">
                        <Pagination
                            :total="products.total"
                            :items-per-page="products.per_page"
                            :page="products.current_page"
                            :sibling-count="1"
                            show-edges
                            @update:page="goToPage"
                        >
                            <PaginationContent v-slot="{ items }">
                                <PaginationPrevious />
                                <template
                                    v-for="item in items"
                                    :key="
                                        item.type === 'page'
                                            ? item.value
                                            : item.type
                                    "
                                >
                                    <PaginationItem
                                        v-if="item.type === 'page'"
                                        :value="item.value"
                                        as-child
                                    >
                                        <Button
                                            :variant="
                                                item.value ===
                                                products.current_page
                                                    ? 'default'
                                                    : 'outline'
                                            "
                                            size="sm"
                                            class="w-9"
                                            @click="goToPage(item.value)"
                                        >
                                            {{ item.value }}
                                        </Button>
                                    </PaginationItem>
                                    <PaginationEllipsis
                                        v-else-if="item.type === 'ellipsis'"
                                    />
                                </template>

                                <PaginationNext />
                            </PaginationContent>
                        </Pagination>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
