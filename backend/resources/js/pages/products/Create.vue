<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldGroup,
    FieldLabel,
    FieldSet,
} from '@/components/ui/field';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: route('products.index'),
    },
    {
        title: 'Add Products',
        href: route('products.create'),
    },
];
</script>

<template>
    <Head title="Products" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="">
            <Card>
                <CardHeader
                    ><CardTitle>Add Products</CardTitle>
                    <CardDescription>Create new product here</CardDescription>
                </CardHeader>
                <CardContent>
                    <FieldSet>
                        <FieldGroup>
                            <Field>
                                <FieldLabel for="name"> Item name </FieldLabel>
                                <Input
                                    id="name"
                                    autocomplete="off"
                                    placeholder="Evil Rabbit"
                                />
                                <FieldDescription
                                    >This appears on invoices and
                                    emails.</FieldDescription
                                >
                            </Field>
                            <Field>
                                <FieldLabel for="username">
                                    Username
                                </FieldLabel>
                                <Input
                                    id="username"
                                    autocomplete="off"
                                    aria-invalid
                                />
                                <FieldError
                                    >Choose another username.</FieldError
                                >
                            </Field>
                            <Field orientation="horizontal">
                                <Switch id="newsletter" />
                                <FieldLabel for="newsletter">
                                    Subscribe to the newsletter
                                </FieldLabel>
                            </Field>
                        </FieldGroup>
                    </FieldSet>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
