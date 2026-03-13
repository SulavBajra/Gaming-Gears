<script setup lang="ts">
// import { Toaster } from '@/components/ui/sonner';
import { useFlashToast } from '@/composables/useFlashToast';
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

useFlashToast();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- <Toaster
            position="top-right"
            :expand="false"
            :duration="4000"
            :toast-options="{
                classes: {
                    toast: 'rounded-xl border shadow-lg px-4 py-3 text-sm font-medium',
                    success:
                        'border-green-200 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200',
                    error: 'border-red-200 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200',
                    warning:
                        'border-yellow-200 bg-yellow-50 text-yellow-800 dark:border-yellow-800 dark:bg-yellow-950 dark:text-yellow-200',
                    info: 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-200',
                    title: 'font-semibold',
                    description: 'text-xs opacity-80 mt-0.5',
                },
            }"
        /> -->
        <div class="pt-5">
            <slot />
        </div>
    </AppLayout>
</template>
