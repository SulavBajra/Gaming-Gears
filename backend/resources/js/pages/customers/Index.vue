<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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
    TableCaption,
    TableHead,
    TableHeader,
    TableRow,
    TableCell,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type {
    BreadcrumbItem,
    OrderStatus,
    PaymentStatus,
    OrderData,
} from '@/types';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import {
    NativeSelect,
    NativeSelectOption,
} from '@/components/ui/native-select';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Customers Orders', href: route('customers.index') },
];

const goToPage = (page: number) => {
    router.get(
        route('customers.index'),
        {
            page,
            order_status: orderStatus.value,
            payment_status: paymentStatus.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const applyFilters = () => {
    router.get(
        route('customers.index'),
        {
            page: 1,
            search: search.value,
            order_status: orderStatus.value,
            payment_status: paymentStatus.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

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

const props = defineProps<{
    orders: OrderData;
    filters: {
        search?: string;
        order_status?: OrderStatus;
        payment_status?: PaymentStatus;
    };
}>();

const updateOrderStatus = (orderId: number, status: string | null) => {
    if (!status) return;

    router.patch(
        route('orders.updateStatus', orderId),
        {
            order_status: status,
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
};

const updatePaymentStatus = (orderId: number, status: string | null) => {
    if (!status) return;

    router.patch(
        route('orders.updatePaymentStatus', orderId),
        {
            payment_status: status,
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
};

const search = ref(props.filters.search ?? '');
const orderStatus = ref<OrderStatus | null>(props.filters.order_status ?? null);
const paymentStatus = ref<PaymentStatus | null>(
    props.filters.payment_status ?? null,
);
</script>

<template>
    <Head title="Manage Orders" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Card class="mt-2 p-4">
            <CardHeader>
                <CardTitle>Customer's Orders</CardTitle>
                <CardDescription
                    >Manage customer's order status from here</CardDescription
                >
            </CardHeader>
            <CardContent>
                <div class="flex items-center gap-3 pb-2">
                    <InputGroup class="w-50">
                        <InputGroupInput
                            v-model="search"
                            placeholder="Search..."
                            @keyup.enter="applyFilters"
                        />
                        <InputGroupAddon>
                            <Search />
                        </InputGroupAddon>
                    </InputGroup>
                    <NativeSelect v-model="orderStatus" @change="applyFilters">
                        <NativeSelectOption :value="null">
                            All Orders
                        </NativeSelectOption>

                        <NativeSelectOption
                            v-for="status in orderStatusOptions"
                            :key="status"
                            :value="status"
                        >
                            {{ status }}
                        </NativeSelectOption>
                    </NativeSelect>
                    <NativeSelect
                        v-model="paymentStatus"
                        @change="applyFilters"
                    >
                        <NativeSelectOption :value="null">
                            All Payment
                        </NativeSelectOption>

                        <NativeSelectOption
                            v-for="status in paymentStatusOptions"
                            :key="status"
                            :value="status"
                        >
                            {{ status }}
                        </NativeSelectOption>
                    </NativeSelect>
                </div>
                <Table>
                    <TableCaption
                        >Showing {{ orders.meta.from }} to
                        {{ orders.meta.to }} of
                        {{ orders.meta.total }}</TableCaption
                    >
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Order Number</TableHead>
                            <TableHead>Customer Email</TableHead>
                            <TableHead>Total (Rs)</TableHead>
                            <TableHead>Order Status</TableHead>
                            <TableHead class="w-[120px]"
                                >Payment Status</TableHead
                            >
                            <TableHead>Ordered At</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="(order, index) in orders.data"
                            :key="order.id"
                        >
                            <TableCell>{{ index + 1 }}</TableCell>
                            <TableCell>{{ order.order_number }}</TableCell>
                            <TableCell>{{ order.customer_email }}</TableCell>
                            <TableCell>{{ order.total }}</TableCell>
                            <TableCell>
                                <select
                                    :value="order.order_status"
                                    @change="
                                        updateOrderStatus(
                                            order.id,
                                            ($event.target as HTMLSelectElement)
                                                .value,
                                        )
                                    "
                                >
                                    <option
                                        v-for="status in orderStatusOptions"
                                        :key="status"
                                        :value="status"
                                    >
                                        {{ status }}
                                    </option>
                                </select>
                                <!-- <NativeSelect
                                    :model-value="order.order_status"
                                    @update="
                                        (value: OrderStatus) =>
                                            updateOrderStatus(
                                                order.id,
                                                value as OrderStatus,
                                            )
                                    "
                                >
                                    <NativeSelectOption
                                        v-for="status in orderStatusOptions"
                                        :key="status"
                                        :value="status"
                                    >
                                        {{ status }}
                                    </NativeSelectOption>
                                </NativeSelect> -->
                            </TableCell>
                            <TableCell>
                                <NativeSelect
                                    :model-value="order.payment_status"
                                    @update="
                                        (value: PaymentStatus) =>
                                            updatePaymentStatus(
                                                order.id,
                                                value as PaymentStatus,
                                            )
                                    "
                                >
                                    <NativeSelectOption
                                        v-for="status in paymentStatusOptions"
                                        :key="status"
                                        :value="status"
                                    >
                                        {{ status }}
                                    </NativeSelectOption>
                                </NativeSelect></TableCell
                            >
                            <TableCell>{{ order.created_at }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="flex flex-col gap-6 pt-4">
                    <Pagination
                        :items-per-page="orders.meta.per_page"
                        :total="orders.meta.total"
                        :page="orders.meta.current_page"
                    >
                        <PaginationContent v-slot="{ items }">
                            <PaginationPrevious
                                @click="
                                    orders.meta.current_page > 1 &&
                                    goToPage(orders.meta.current_page - 1)
                                "
                                :disabled="orders.meta.current_page === 1"
                            />
                            <template
                                v-for="(item, index) in items"
                                :key="index"
                            >
                                <PaginationItem
                                    v-if="item.type === 'page'"
                                    :value="item.value"
                                    :is-active="
                                        item.value === orders.meta.current_page
                                    "
                                    @click="goToPage(item.value)"
                                >
                                    {{ item.value }}
                                </PaginationItem>
                            </template>
                            <PaginationEllipsis :index="4" />
                            <PaginationNext
                                @click="
                                    orders.meta.current_page <
                                        orders.meta.last_page &&
                                    goToPage(orders.meta.current_page + 1)
                                "
                                :disabled="
                                    orders.meta.current_page ===
                                    orders.meta.last_page
                                "
                            />
                        </PaginationContent>
                    </Pagination>
                </div>
            </CardContent>
        </Card>
    </AppLayout>
</template>
