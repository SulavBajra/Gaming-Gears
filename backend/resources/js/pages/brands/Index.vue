<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardFooter,
    CardTitle,
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
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Brand {
    id: number;
    name: string;
    slug: string;
    description: string;
    is_active: boolean;
    logo_url: string | null;
}

interface PaginatedBrands {
    data: Brand[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
}

defineProps<{ brands: PaginatedBrands }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Brands', href: route('brands.index') },
];

const deleteBrand = (id: number) => {
    router.delete(route('brands.destroy', id), {
        preserveScroll: true,
    });
};

const goToPage = (page: number) => {
    router.get(
        route('brands.index'),
        { page },
        { preserveScroll: true, preserveState: true },
    );
};
</script>

<template>
    <Head title="Brands" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle class="text-xl text-primary"
                            >Brands</CardTitle
                        >
                        <p class="text-sm text-muted-foreground">
                            Manage all of {{ brands.total }} brands here
                        </p>
                    </div>
                    <Link :href="route('brands.create')">
                        <Button> <Plus class="h-4 w-4" /> Add Brand</Button>
                    </Link>
                </CardHeader>
                <Separator />
                <CardContent>
                    <div
                        class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                    >
                        <Card
                            v-for="brand in brands.data"
                            :key="brand.id"
                            class="border p-2 transition-shadow duration-200 hover:shadow-lg"
                        >
                            <CardContent
                                class="flex flex-col items-center gap-2 pt-4"
                            >
                                <div
                                    class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full border bg-muted"
                                >
                                    <img
                                        v-if="brand.logo_url"
                                        :src="brand.logo_url"
                                        :alt="brand.name"
                                        class="h-full w-full object-contain"
                                    />
                                    <span
                                        v-else
                                        class="text-xl font-bold text-muted-foreground"
                                    >
                                        {{ brand.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-semibold">
                                    {{ brand.name }}
                                </h3>

                                <div class="mt-3 flex w-full justify-end gap-2">
                                    <div>
                                        <span
                                            :class="[
                                                'inline-block rounded px-2 py-1 text-xs',
                                                brand.is_active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-gray-200 text-gray-600',
                                            ]"
                                        >
                                            {{
                                                brand.is_active
                                                    ? 'Active'
                                                    : 'Inactive'
                                            }}
                                        </span>
                                    </div>
                                    <Link
                                        :href="route('brands.edit', brand.id)"
                                    >
                                        <Button variant="ghost" size="sm">
                                            <Pencil />
                                        </Button>
                                    </Link>

                                    <Dialog>
                                        <DialogTrigger as-child>
                                            <Button
                                                variant="destructive"
                                                size="sm"
                                                ><Trash
                                            /></Button>
                                        </DialogTrigger>
                                        <DialogContent class="sm:max-w-[425px]">
                                            <DialogHeader>
                                                <DialogTitle
                                                    >Delete brand ?</DialogTitle
                                                >
                                                <DialogDescription>
                                                    Are you sure you want to
                                                    delete this brand
                                                    permanently. This cannot be
                                                    undone.
                                                </DialogDescription>
                                            </DialogHeader>
                                            <DialogFooter>
                                                <DialogClose as-child>
                                                    <Button variant="secondary">
                                                        Cancel
                                                    </Button>
                                                </DialogClose>
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    @click="
                                                        deleteBrand(brand.id)
                                                    "
                                                >
                                                    <Trash />Delete
                                                </Button>
                                            </DialogFooter>
                                        </DialogContent>
                                    </Dialog>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </CardContent>

                <CardFooter>
                    <div class="mt-4 flex w-full justify-center">
                        <Pagination
                            :total="brands.total"
                            :items-per-page="brands.per_page"
                            :page="brands.current_page"
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
                                                brands.current_page
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
