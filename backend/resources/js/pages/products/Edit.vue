<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
    Card,
    CardContent,
    CardHeader,
    CardFooter,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: route('products.edit'),
    },
];
</script>

<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="">
            <Card>
                <CardContent>
                    <CardTitle>Products</CardTitle>
                    <CardHeader>Header</CardHeader>
                    <CardFooter>Footer</CardFooter>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
