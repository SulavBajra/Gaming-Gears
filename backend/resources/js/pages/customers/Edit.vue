<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { OrderStatus, OrderView, PaymentStatus } from '@/types';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';

const props = defineProps<{ order: OrderView }>();
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

const updateStatus = () => {
    router.patch(route('customers.update', { order: props.order.data.id }), {
        order_status_id: selectedOrderStatus.value,
        payment_status_id: selectedPaymentStatus.value,
    });
};
</script>
<template>
    <AppLayout>
        <Card>
            <CardHeader>
                <CardTitle>Order No: {{ order.data.order_number }}</CardTitle>
            </CardHeader>
            <CardContent>
                <p><strong>Customer:</strong> {{ order.data.customer_name }}</p>
                <p><strong>Email:</strong> {{ order.data.customer_email }}</p>
                <p><strong>Phone:</strong> {{ order.data.customer_phone }}</p>
                <p><strong>Total:</strong> {{ order.data.total }}</p>
                <p>
                    <strong>Order Status:</strong>
                    <select
                        v-model="selectedOrderStatus"
                        class="rounded border p-1"
                    >
                        <option
                            v-for="status in orderStatusOptions"
                            :key="status.id"
                            :value="status.id"
                        >
                            {{ status.name }}
                        </option>
                    </select>
                </p>

                <p>
                    <strong>Payment Status:</strong>
                    <select
                        v-model="selectedPaymentStatus"
                        class="rounded border p-1"
                    >
                        <option
                            v-for="status in paymentStatusOptions"
                            :key="status.id"
                            :value="status.id"
                        >
                            {{ status.name }}
                        </option>
                    </select>
                </p>

                <button
                    @click="updateStatus"
                    class="mt-4 rounded bg-black px-3 py-1 text-white"
                >
                    Update Status
                </button>
            </CardContent>
        </Card>
    </AppLayout>
</template>
