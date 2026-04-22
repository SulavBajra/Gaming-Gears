<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { OrderView } from '@/types';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogFooter,
    DialogClose,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

const props = defineProps<{ order: OrderView }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Customers Orders', href: route('customers.index') },
    {
        title: 'Order Details',
        href: route('customers.edit', { order: props.order.data.id }),
    },
];

const orderStatusOptions = [
    { id: 1, name: 'Pending' },
    { id: 2, name: 'Confirmed' },
    { id: 3, name: 'Processing' },
    { id: 4, name: 'Shipped' },
    { id: 5, name: 'Delivered' },
    { id: 6, name: 'Cancelled' },
    { id: 7, name: 'Refunded' },
];

const paymentStatusOptions = [
    { id: 1, name: 'Unpaid' },
    { id: 2, name: 'Paid' },
    { id: 3, name: 'Failed' },
    { id: 4, name: 'Refunded' },
];

const selectedOrderStatus = ref<number>(props.order.data.order_status.id);
const selectedPaymentStatus = ref<number>(props.order.data.payment_status.id);
const dialogOpen = ref(false);

const updateStatus = () => {
    router.patch(
        route('customers.update', { order: props.order.data.id }),
        {
            order_status_id: selectedOrderStatus.value,
            payment_status_id: selectedPaymentStatus.value,
        },
        {
            onSuccess: () => {
                dialogOpen.value = false;
            },
        },
    );
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Page Title -->
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold tracking-tight">
                    Order
                    <span class="text-muted-foreground"
                        >#{{ order.data.order_number }}</span
                    >
                </h1>
                <span class="text-sm text-muted-foreground"
                    >Placed on {{ order.data.created_at }}</span
                >
            </div>

            <Card>
                <CardContent class="divide-y p-0">
                    <!-- Customer -->
                    <div class="p-5">
                        <p
                            class="mb-3 text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                        >
                            Customer
                        </p>
                        <dl
                            class="grid grid-cols-2 gap-x-6 gap-y-3 sm:grid-cols-3"
                        >
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Name
                                </dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{ order.data.customer_name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Email
                                </dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{ order.data.customer_email }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Phone
                                </dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{ order.data.customer_phone ?? '—' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Shipping Address -->
                    <div class="p-5">
                        <p
                            class="mb-3 text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                        >
                            Shipping Address
                        </p>
                        <dl
                            class="grid grid-cols-2 gap-x-6 gap-y-3 sm:grid-cols-3"
                        >
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Line 1
                                </dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{
                                        order.data.shipping_address.line1 ?? '—'
                                    }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Line 2
                                </dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{
                                        order.data.shipping_address.line2 ?? '—'
                                    }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    City
                                </dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{
                                        order.data.shipping_address.city ?? '—'
                                    }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Order Details -->
                    <div class="p-5">
                        <p
                            class="mb-3 text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                        >
                            Order Details
                        </p>
                        <dl
                            class="grid grid-cols-2 gap-x-6 gap-y-3 sm:grid-cols-4"
                        >
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Total
                                </dt>
                                <dd class="mt-0.5 text-sm font-semibold">
                                    Rs. {{ order.data.total }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Payment Method
                                </dt>
                                <dd
                                    class="mt-0.5 text-sm font-medium capitalize"
                                >
                                    {{ order.data.payment_method ?? '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Shipped At
                                </dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{ order.data.shipped_at ?? '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Delivered At
                                </dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{ order.data.delivered_at ?? '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">
                                    Cancelled At
                                </dt>
                                <dd class="mt-0.5 text-sm font-medium">
                                    {{ order.data.cancelled_at ?? '—' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Status Management -->
                    <div class="p-5">
                        <p
                            class="mb-3 text-xs font-semibold tracking-widest text-muted-foreground uppercase"
                        >
                            Status
                        </p>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-1.5">
                                <label class="text-xs text-muted-foreground"
                                    >Order Status</label
                                >
                                <select
                                    v-model="selectedOrderStatus"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus:ring-2 focus:ring-ring focus:outline-none"
                                >
                                    <option
                                        v-for="status in orderStatusOptions"
                                        :key="status.id"
                                        :value="status.id"
                                    >
                                        {{ status.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs text-muted-foreground"
                                    >Payment Status</label
                                >
                                <select
                                    v-model="selectedPaymentStatus"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus:ring-2 focus:ring-ring focus:outline-none"
                                >
                                    <option
                                        v-for="status in paymentStatusOptions"
                                        :key="status.id"
                                        :value="status.id"
                                    >
                                        {{ status.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <Dialog v-model:open="dialogOpen">
                                <DialogTrigger as-child>
                                    <button
                                        class="rounded-md bg-primary px-5 py-2 text-sm font-medium text-primary-foreground shadow-sm transition hover:cursor-pointer hover:opacity-90 active:scale-95"
                                    >
                                        Update Status
                                    </button>
                                </DialogTrigger>
                                <DialogContent class="sm:max-w-md">
                                    <DialogHeader>
                                        <DialogTitle
                                            >Confirm Status Update</DialogTitle
                                        >
                                        <DialogDescription>
                                            Are you sure you want to update the
                                            order and payment status? This
                                            action cannot be undone.
                                        </DialogDescription>
                                    </DialogHeader>
                                    <DialogFooter class="gap-2 sm:gap-2">
                                        <DialogClose as-child>
                                            <Button
                                                variant="destructive"
                                                class="rounded-md border border-input bg-background px-5 py-2 text-sm font-medium shadow-sm transition hover:bg-accent"
                                            >
                                                Cancel
                                            </Button>
                                        </DialogClose>
                                        <button
                                            @click="updateStatus"
                                            class="rounded-md bg-primary px-5 py-2 text-sm font-medium text-primary-foreground shadow-sm transition hover:cursor-pointer hover:opacity-90 active:scale-95"
                                        >
                                            Confirm
                                        </button>
                                    </DialogFooter>
                                </DialogContent>
                            </Dialog>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
