<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Bar, Doughnut } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement,
} from 'chart.js';
import { route } from 'ziggy-js';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement,
);

interface StockStatusItem {
    name: string;
    value: number;
}

interface BrandInventory {
    brand: string;
    inventoryValue: number;
    totalUnits: number;
}

interface CategoryStock {
    category: string;
    total_stock: number;
}

interface LowStockProduct {
    product: string;
    brand: string | null;
    variant: string;
    stock: number;
    threshold: number;
}

interface Summary {
    totalProducts: number;
    totalVariants: number;
    totalUnitsInStock: number;
    totalInventoryValue: number;
    lowStockCount: number;
}

const props = defineProps<{
    summary: Summary;
    stockStatus: StockStatusItem[];
    inventoryByBrand: BrandInventory[];
    stockByCategory: CategoryStock[];
    lowStockProducts: LowStockProduct[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Products', href: route('products.index') },
    { title: 'Analytics', href: route('products.analytics') },
];

const stockStatusData = computed(() => ({
    labels: props.stockStatus.map((s) => s.name),
    datasets: [
        {
            data: props.stockStatus.map((s) => s.value),
            backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
            borderWidth: 0,
        },
    ],
}));

const inventoryByBrandData = computed(() => ({
    labels: props.inventoryByBrand.map((b) => b.brand),
    datasets: [
        {
            label: 'Inventory Value',
            data: props.inventoryByBrand.map((b) => b.inventoryValue),
            backgroundColor: '#3b82f6',
            borderRadius: 4,
        },
    ],
}));

const stockByCategoryData = computed(() => ({
    labels: props.stockByCategory.map((c) => c.category),
    datasets: [
        {
            label: 'Units in Stock',
            data: props.stockByCategory.map((c) => c.total_stock),
            backgroundColor: '#8b5cf6',
            borderRadius: 4,
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: 'bottom' as const },
    },
};

const formatCurrency = (value: number): string =>
    `Rs. ${value.toLocaleString()}`;
</script>

<template>
    <Head title="Inventory Analytics" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-4 space-y-6">
            <!-- Summary cards -->
            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Total Products</CardDescription>
                        <CardTitle class="text-2xl">{{
                            summary.totalProducts
                        }}</CardTitle>
                    </CardHeader>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Total Variants</CardDescription>
                        <CardTitle class="text-2xl">{{
                            summary.totalVariants
                        }}</CardTitle>
                    </CardHeader>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Units in Stock</CardDescription>
                        <CardTitle class="text-2xl">{{
                            summary.totalUnitsInStock
                        }}</CardTitle>
                    </CardHeader>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Inventory Value</CardDescription>
                        <CardTitle class="text-2xl">
                            {{ formatCurrency(summary.totalInventoryValue) }}
                        </CardTitle>
                    </CardHeader>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Low Stock Alerts</CardDescription>
                        <CardTitle class="text-2xl text-destructive">
                            {{ summary.lowStockCount }}
                        </CardTitle>
                    </CardHeader>
                </Card>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg text-primary"
                            >Stock Status</CardTitle
                        >
                        <CardDescription
                            >Breakdown of variant stock health</CardDescription
                        >
                    </CardHeader>
                    <CardContent class="h-72">
                        <Doughnut
                            :data="stockStatusData"
                            :options="chartOptions"
                        />
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg text-primary"
                            >Inventory Value by Brand</CardTitle
                        >
                        <CardDescription
                            >Price &times; stock quantity per
                            brand</CardDescription
                        >
                    </CardHeader>
                    <CardContent class="h-72">
                        <Bar
                            :data="inventoryByBrandData"
                            :options="chartOptions"
                        />
                    </CardContent>
                </Card>

                <Card class="md:col-span-2">
                    <CardHeader>
                        <CardTitle class="text-lg text-primary"
                            >Stock Units by Category</CardTitle
                        >
                        <CardDescription
                            >Total units currently held per
                            category</CardDescription
                        >
                    </CardHeader>
                    <CardContent class="h-72">
                        <Bar
                            :data="stockByCategoryData"
                            :options="chartOptions"
                        />
                    </CardContent>
                </Card>
            </div>

            <!-- Low stock table -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg text-primary"
                        >Low Stock Products</CardTitle
                    >
                    <CardDescription>
                        Variants at or below their low stock threshold
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Product</TableHead>
                                <TableHead>Brand</TableHead>
                                <TableHead>Variant</TableHead>
                                <TableHead>Stock</TableHead>
                                <TableHead>Threshold</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="(item, i) in lowStockProducts"
                                :key="i"
                            >
                                <TableCell class="font-medium">{{
                                    item.product
                                }}</TableCell>
                                <TableCell>{{ item.brand ?? '—' }}</TableCell>
                                <TableCell>{{ item.variant }}</TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            item.stock === 0
                                                ? 'destructive'
                                                : 'secondary'
                                        "
                                    >
                                        {{ item.stock }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-muted-foreground">
                                    {{ item.threshold }}
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="lowStockProducts.length === 0">
                                <TableCell
                                    colspan="5"
                                    class="text-center text-muted-foreground"
                                >
                                    No low stock products 🎉
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
