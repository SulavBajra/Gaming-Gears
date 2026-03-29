<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardFooter,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
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
import type { Product } from '@/types/index';

interface PaginatedProducts {
    data: Product[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
}

defineProps<{ products: PaginatedProducts }>();

const category: Record<number, string> = {
    1: 'Keyboard',
    7: 'Mice',
    13: 'Headsets',
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Products', href: route('products.index') },
];

const formatPrice = (max: number | null): string => {
    return `Rs. ${Number(max).toFixed(2)}`;
};

const deleteProduct = (id: number) => {
    router.delete(route('products.destroy', id), {
        preserveScroll: true,
    });
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
                        <CardDescription
                            ><p class="text-sm text-muted-foreground">
                                Manage all of {{ products.total }} products here
                            </p></CardDescription
                        >
                    </div>
                    <Link :href="route('products.create')">
                        <Button> <Plus class="h-2 w-4" /> Add Product</Button>
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
                                <TableCell>
                                    {{
                                        product?.categories?.[0]?.parent_id !==
                                            null &&
                                        product?.categories?.[0]?.parent_id !==
                                            undefined
                                            ? category[
                                                  product.categories[0]
                                                      .parent_id as number
                                              ]
                                            : '—'
                                    }}
                                </TableCell>
                                <TableCell>
                                    {{
                                        formatPrice(product.variants_max_price)
                                    }}
                                </TableCell>
                                <TableCell>
                                    <span
                                        :class="
                                            product.variants_sum_stock_quantity ===
                                            0
                                                ? 'font-medium text-destructive'
                                                : ''
                                        "
                                    >
                                        {{
                                            product.variants_sum_stock_quantity ??
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
                                    <div class="flex justify-end gap-2">
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

                                        <Dialog>
                                            <DialogTrigger as-child>
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    ><Trash
                                                /></Button>
                                            </DialogTrigger>
                                            <DialogContent
                                                class="sm:max-w-[425px]"
                                            >
                                                <DialogHeader>
                                                    <DialogTitle
                                                        >Delete product
                                                        ?</DialogTitle
                                                    >
                                                    <DialogDescription>
                                                        Are you sure you want to
                                                        delete this product
                                                        permanently. This cannot
                                                        be undone.
                                                    </DialogDescription>
                                                </DialogHeader>
                                                <DialogFooter>
                                                    <DialogClose as-child>
                                                        <Button
                                                            variant="secondary"
                                                        >
                                                            Cancel
                                                        </Button>
                                                    </DialogClose>
                                                    <Button
                                                        variant="destructive"
                                                        size="sm"
                                                        @click="
                                                            deleteProduct(
                                                                product.id,
                                                            )
                                                        "
                                                    >
                                                        <Trash />
                                                        Delete
                                                    </Button>
                                                </DialogFooter>
                                            </DialogContent>
                                        </Dialog>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
                <CardFooter class="flex justify-center">
                    <div
                        class="flex w-full max-w-md flex-wrap justify-center gap-2"
                    >
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
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>
