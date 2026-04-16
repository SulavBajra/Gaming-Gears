<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem, DashboardOrderResource } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
];

defineProps<{
    pending_orders: number;
    week_total: number;
    month_total: string;
    year_total: string;
    completed_order: number;
    recentOrders: DashboardOrderResource[];
}>();

const fmt = (val: any) =>
    new Intl.NumberFormat('ne-NP', {
        style: 'currency',
        currency: 'NPR',
        maximumFractionDigits: 0,
    }).format(val);
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid grid-cols-2 gap-3 pt-2.5 lg:grid-cols-4">
            <div class="rounded-md bg-muted/50 p-4">
                <p
                    class="text-xs tracking-wide text-muted-foreground uppercase"
                >
                    Revenue · Year
                </p>
                <p class="mt-1.5 text-2xl font-medium">{{ fmt(year_total) }}</p>
            </div>
            <div class="rounded-md bg-muted/50 p-4">
                <p
                    class="text-xs tracking-wide text-muted-foreground uppercase"
                >
                    Revenue · Month
                </p>
                <p class="mt-1.5 text-2xl font-medium">
                    {{ fmt(month_total) }}
                </p>
            </div>

            <div class="rounded-md bg-muted/50 p-4">
                <p
                    class="text-xs tracking-wide text-muted-foreground uppercase"
                >
                    Completed orders
                </p>
                <p class="mt-1.5 text-2xl font-medium">{{ completed_order }}</p>
            </div>
            <div class="rounded-md bg-muted/50 p-4">
                <p
                    class="text-xs tracking-wide text-muted-foreground uppercase"
                >
                    Pending orders
                </p>
                <p class="mt-1.5 text-2xl font-medium">{{ pending_orders }}</p>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-lg border">
            <div class="flex items-center justify-between border-b px-5 py-3">
                <p class="text-sm font-medium">Recent orders</p>
                <span class="text-xs text-muted-foreground">Last 10</span>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>S.N</TableHead>
                        <TableHead>Order Number</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Payment Status</TableHead>
                        <TableHead>Order Status</TableHead>
                        <TableHead>Date</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="(order, index) in recentOrders"
                        :key="order.id"
                    >
                        <TableCell>{{ index + 1 }}</TableCell>
                        <TableCell>{{ order.order_number }}</TableCell>
                        <TableCell>{{ order.email }}</TableCell>
                        <TableCell>
                            <span
                                class="rounded px-2 py-1 text-xs"
                                :class="{
                                    'bg-green-100 text-green-700':
                                        order.payment_status === 'Paid',
                                    'bg-yellow-100 text-yellow-700':
                                        order.payment_status === 'Pending',
                                    'bg-red-100 text-red-700':
                                        order.payment_status === 'Failed',
                                }"
                            >
                                {{ order.payment_status }}
                            </span>
                        </TableCell>
                        {{ console.log(order.payment_status) }}
                        <TableCell>{{ order.order_status }}</TableCell>
                        <TableCell>{{ order.created_at }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
