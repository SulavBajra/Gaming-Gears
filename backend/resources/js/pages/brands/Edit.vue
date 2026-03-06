<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldGroup,
    FieldLabel,
    FieldSet,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Brands', href: route('brands.index') },
    { title: 'Edit Brand', href: '#' },
];

interface Brand {
    id: number;
    name: string;
    slug: string;
    description: string;
    is_active: boolean;
    logo_url: string | null;
}

const props = defineProps<{ brand: Brand }>();
const brand = props.brand;

const logoPreview = ref<string | null>(brand.logo_url ?? null);
const form = useForm({
    name: brand.name,
    description: brand.description ?? '',
    is_active: brand.is_active,
    logo: null as File | null,
});

const handleLogoChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];

    if (!file) return;

    if (logoPreview.value) {
        URL.revokeObjectURL(logoPreview.value);
    }

    form.logo = file;
    logoPreview.value = URL.createObjectURL(file);
};

const submit = () => {
    form.put(route('brands.update', brand.id), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Add Brand" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-4 max-w-2xl">
            <Card>
                <CardHeader>
                    <CardTitle>Add Brand</CardTitle>
                    <CardDescription>Add a new brand here</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit">
                        <FieldSet>
                            <FieldGroup>
                                <!-- Brand Name -->
                                <Field>
                                    <FieldLabel for="name"
                                        >Brand Name</FieldLabel
                                    >
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        required
                                        autocomplete="off"
                                        placeholder="e.g. Nike"
                                        :aria-invalid="!!form.errors.name"
                                    />
                                    <FieldError v-if="form.errors.name">
                                        {{ form.errors.name }}
                                    </FieldError>
                                </Field>

                                <!-- Description -->
                                <Field>
                                    <FieldLabel for="description"
                                        >Description</FieldLabel
                                    >
                                    <Textarea
                                        id="description"
                                        v-model="form.description"
                                        placeholder="Optional brand description"
                                        rows="3"
                                    />
                                    <FieldError v-if="form.errors.description">
                                        {{ form.errors.description }}
                                    </FieldError>
                                </Field>

                                <Field>
                                    <FieldLabel for="logo">Logo</FieldLabel>
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full border bg-muted"
                                        >
                                            <img
                                                v-if="logoPreview"
                                                :src="logoPreview"
                                                alt="Logo preview"
                                                class="h-full w-full object-contain"
                                            />
                                            <span
                                                v-else
                                                class="text-xs text-muted-foreground"
                                                >Logo</span
                                            >
                                        </div>
                                        <Input
                                            id="logo"
                                            name="logo"
                                            type="file"
                                            accept="image/*"
                                            class="max-w-xs"
                                            @change="handleLogoChange"
                                        />
                                    </div>
                                    <FieldDescription
                                        >Accepted formats: JPG, PNG, SVG. Max
                                        2MB.</FieldDescription
                                    >
                                    <FieldError v-if="form.errors.logo">
                                        {{ form.errors.logo }}
                                    </FieldError>
                                </Field>

                                <!-- Active Toggle -->
                                <Field orientation="horizontal">
                                    <Switch
                                        id="is_active"
                                        v-model="form.is_active"
                                    />
                                    <FieldLabel for="is_active"
                                        >Active</FieldLabel
                                    >
                                </Field>
                            </FieldGroup>
                        </FieldSet>

                        <div class="mt-6 flex gap-3">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Saving...' : 'Update' }}
                            </Button>
                            <Button variant="outline" as-child>
                                <Link :href="route('brands.index')"
                                    >Cancel</Link
                                >
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
