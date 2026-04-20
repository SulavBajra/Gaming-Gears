<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { OrderStatus, OrderView, PaymentStatus } from '@/types';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';

const props = defineProps<{ order: OrderView }>();
const orderStatusOptions: OrderStatus[] = [
    'Pending',
    'Confirmed',
    'Processing',
    'Shipped',
    'Delivered',
    'Cancelled',
    'Refunded',
];

const paymentStatusOptions: PaymentStatus[] = [
    'Unpaid',
    'Paid',
    'Failed',
    'Refunded',
];

const selectedOrderStatus = ref<OrderStatus>(
    props.order.data.order_status.name as OrderStatus,
);

const selectedPaymentStatus = ref<PaymentStatus>(
    props.order.data.payment_status.name as PaymentStatus,
);

const updateStatus = () => {
    router.patch(route('customers.update', { order: props.order.data.id }), {
        order_status: selectedOrderStatus.value,
        payment_status: selectedPaymentStatus.value,
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
                            :key="status"
                            :value="status"
                        >
                            {{ status }}
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
                            :key="status"
                            :value="status"
                        >
                            {{ status }}
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
