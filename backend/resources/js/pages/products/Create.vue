<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import {
    Plus,
    Trash2,
    ChevronRight,
    ChevronLeft,
    Package,
    Layers,
    ImagePlus,
    X,
    Tag,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import { Field, FieldLabel, FieldError, FieldSet } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectTrigger,
    SelectValue,
    SelectContent,
    SelectItem,
} from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Brand {
    id: number;
    name: string;
}
interface Category {
    id: number;
    name: string;
}

const props = defineProps<{ brands: Brand[]; categories: Category[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Products', href: route('products.index') },
    { title: 'Add Product', href: route('products.create') },
];

const currentStep = ref(1);
const totalSteps = 2;

interface Variant {
    name: string;
    price: string;
    stock_quantity: string;
    is_active: boolean;
}

const form = useForm({
    name: '',
    description: '',
    brand_id: '',
    category_ids: [] as string[],
    is_active: true,
    is_featured: false,
    tags: [] as string[],
    thumbnail: null as File | null,
    variants: [
        {
            name: '',
            price: '',
            stock_quantity: '0',
            is_active: true,
        } as Variant,
    ],
});

const thumbnailPreview = ref<string | null>(null);
const handleThumbnail = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.thumbnail = file;
        thumbnailPreview.value = URL.createObjectURL(file);
    }
};
const removeThumbnail = () => {
    form.thumbnail = null;
    thumbnailPreview.value = null;
};

interface GalleryFile {
    file: File;
    preview: string;
}
const galleryFiles = ref<GalleryFile[]>([]);

const handleGallery = (e: Event) => {
    const files = Array.from((e.target as HTMLInputElement).files ?? []);
    for (const file of files) {
        galleryFiles.value.push({ file, preview: URL.createObjectURL(file) });
    }
    (e.target as HTMLInputElement).value = '';
};

const removeGalleryImage = (i: number) => {
    URL.revokeObjectURL(galleryFiles.value[i].preview);
    galleryFiles.value.splice(i, 1);
};

const addVariant = () =>
    form.variants.push({
        name: '',
        price: '',
        stock_quantity: '0',
        is_active: true,
    });
const removeVariant = (i: number) => form.variants.splice(i, 1);

// ── Tags helpers ─────────────────────────────────
const tagInput = ref('');
const addTag = () => {
    const tag = tagInput.value.trim().toLowerCase();
    if (tag && !form.tags.includes(tag)) {
        form.tags.push(tag);
    }
    tagInput.value = '';
};
const removeTag = (i: number) => form.tags.splice(i, 1);
const handleTagKeydown = (e: KeyboardEvent) => {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        addTag();
    }
};

const step1Valid = computed(
    () => form.name && form.brand_id && form.category_ids.length > 0,
);
const step2Valid = computed(
    () =>
        form.variants.length > 0 &&
        form.variants.every((v) => v.name && v.price),
);

const submit = () => {
    const data = new FormData();
    data.append('name', form.name);
    data.append('description', form.description);
    data.append('brand_id', form.brand_id);
    form.category_ids.forEach((id) => data.append('category_ids[]', id));
    data.append('is_active', form.is_active ? '1' : '0');
    data.append('is_featured', form.is_featured ? '1' : '0');
    form.tags.forEach((tag) => data.append('tags[]', tag));
    if (form.thumbnail) data.append('thumbnail', form.thumbnail);
    galleryFiles.value.forEach(({ file }) => data.append('gallery[]', file));
    data.append('variants', JSON.stringify(form.variants));
    form.transform(() => data).post(route('products.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Add Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-4 max-w-3xl">
            <Card>
                <CardHeader class="pb-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <CardTitle class="text-xl text-primary"
                                >Add Product</CardTitle
                            >
                            <CardDescription
                                >Fill in the details below to create a new
                                product.</CardDescription
                            >
                        </div>
                        <!-- Step indicator -->
                        <div class="flex items-center gap-2 text-sm">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold transition-colors"
                                :class="
                                    currentStep >= 1
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-muted text-muted-foreground'
                                "
                            >
                                1
                            </div>
                            <div class="h-px w-6 bg-border" />
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold transition-colors"
                                :class="
                                    currentStep >= 2
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-muted text-muted-foreground'
                                "
                            >
                                2
                            </div>
                        </div>
                    </div>

                    <!-- Step label -->
                    <div
                        class="mt-3 flex items-center gap-2 text-sm text-muted-foreground"
                    >
                        <Package v-if="currentStep === 1" class="h-4 w-4" />
                        <Layers v-else class="h-4 w-4" />
                        <span class="font-medium text-foreground">
                            {{
                                currentStep === 1 ? 'Product Info' : 'Variants'
                            }}
                        </span>
                        <span
                            >— Step {{ currentStep }} of {{ totalSteps }}</span
                        >
                    </div>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit">
                        <!-- Step 1: Product Info -->
                        <div v-if="currentStep === 1">
                            <FieldSet class="space-y-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <Field class="col-span-2">
                                        <FieldLabel
                                            >Product Name
                                            <span class="text-destructive"
                                                >*</span
                                            ></FieldLabel
                                        >
                                        <Input
                                            v-model="form.name"
                                            placeholder="e.g. Logitech G Pro X"
                                        />
                                        <FieldError v-if="form.errors.name">{{
                                            form.errors.name
                                        }}</FieldError>
                                    </Field>

                                    <Field>
                                        <FieldLabel
                                            >Brand
                                            <span class="text-destructive"
                                                >*</span
                                            ></FieldLabel
                                        >
                                        <Select v-model="form.brand_id">
                                            <SelectTrigger>
                                                <SelectValue
                                                    placeholder="Select brand"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="b in props.brands"
                                                    :key="b.id"
                                                    :value="String(b.id)"
                                                    >{{ b.name }}</SelectItem
                                                >
                                            </SelectContent>
                                        </Select>
                                        <FieldError
                                            v-if="form.errors.brand_id"
                                            >{{
                                                form.errors.brand_id
                                            }}</FieldError
                                        >
                                    </Field>

                                    <Field>
                                        <FieldLabel
                                            >Categories
                                            <span class="text-destructive"
                                                >*</span
                                            ></FieldLabel
                                        >
                                        <Select
                                            v-model="form.category_ids"
                                            multiple
                                        >
                                            <SelectTrigger>
                                                <SelectValue
                                                    placeholder="Select categories"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="c in props.categories"
                                                    :key="c.id"
                                                    :value="String(c.id)"
                                                    >{{ c.name }}</SelectItem
                                                >
                                            </SelectContent>
                                        </Select>
                                    </Field>
                                </div>

                                <Field>
                                    <FieldLabel>Description</FieldLabel>
                                    <Textarea
                                        v-model="form.description"
                                        rows="3"
                                        placeholder="Brief product description..."
                                    />
                                </Field>

                                <!-- Thumbnail upload -->
                                <Field>
                                    <FieldLabel>Thumbnail</FieldLabel>
                                    <div
                                        v-if="!thumbnailPreview"
                                        class="relative"
                                    >
                                        <label
                                            class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border border-dashed border-border bg-muted/30 py-8 text-sm text-muted-foreground transition-colors hover:bg-muted/50"
                                        >
                                            <ImagePlus class="h-6 w-6" />
                                            <span>Click to upload image</span>
                                            <span class="text-xs"
                                                >PNG, JPG, WEBP</span
                                            >
                                            <input
                                                type="file"
                                                accept="image/*"
                                                class="sr-only"
                                                @change="handleThumbnail"
                                            />
                                        </label>
                                    </div>
                                    <div v-else class="relative inline-block">
                                        <img
                                            :src="thumbnailPreview"
                                            class="h-28 w-28 rounded-lg object-cover ring-1 ring-border"
                                        />
                                        <button
                                            type="button"
                                            @click="removeThumbnail"
                                            class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-destructive text-destructive-foreground shadow"
                                        >
                                            <X class="h-3 w-3" />
                                        </button>
                                    </div>
                                </Field>
                                <!-- Gallery upload -->
                                <Field>
                                    <FieldLabel>Gallery</FieldLabel>

                                    <!-- Existing images grid -->
                                    <div
                                        v-if="galleryFiles.length"
                                        class="mb-3 flex flex-wrap gap-2"
                                    >
                                        <div
                                            v-for="(img, i) in galleryFiles"
                                            :key="i"
                                            class="relative"
                                        >
                                            <img
                                                :src="img.preview"
                                                class="h-20 w-20 rounded-lg object-cover ring-1 ring-border"
                                            />
                                            <button
                                                type="button"
                                                @click="removeGalleryImage(i)"
                                                class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-destructive text-destructive-foreground shadow"
                                            >
                                                <X class="h-3 w-3" />
                                            </button>
                                        </div>

                                        <!-- Compact "add more" tile -->
                                        <label
                                            class="flex h-20 w-20 cursor-pointer flex-col items-center justify-center gap-1 rounded-lg border border-dashed border-border bg-muted/30 text-xs text-muted-foreground transition-colors hover:bg-muted/50"
                                        >
                                            <ImagePlus class="h-5 w-5" />
                                            <span>Add</span>
                                            <input
                                                type="file"
                                                accept="image/*"
                                                multiple
                                                class="sr-only"
                                                @change="handleGallery"
                                            />
                                        </label>
                                    </div>

                                    <!-- Empty state drop zone -->
                                    <div v-else class="relative">
                                        <label
                                            class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border border-dashed border-border bg-muted/30 py-8 text-sm text-muted-foreground transition-colors hover:bg-muted/50"
                                        >
                                            <ImagePlus class="h-6 w-6" />
                                            <span
                                                >Click to upload gallery
                                                images</span
                                            >
                                            <span class="text-xs"
                                                >PNG, JPG, WEBP — multiple
                                                allowed</span
                                            >
                                            <input
                                                type="file"
                                                accept="image/*"
                                                multiple
                                                class="sr-only"
                                                @change="handleGallery"
                                            />
                                        </label>
                                    </div>
                                </Field>

                                <Field>
                                    <div class="flex items-center gap-3">
                                        <Switch v-model="form.is_active" />
                                        <div>
                                            <p class="text-sm font-medium">
                                                Active
                                            </p>
                                            <p
                                                class="text-xs text-muted-foreground"
                                            >
                                                Product will be visible in the
                                                store
                                            </p>
                                        </div>
                                    </div>
                                </Field>

                                <Field>
                                    <div class="flex items-center gap-3">
                                        <Switch v-model="form.is_featured" />
                                        <div>
                                            <p class="text-sm font-medium">
                                                Featured
                                            </p>
                                            <p
                                                class="text-xs text-muted-foreground"
                                            >
                                                Highlight this product on the
                                                storefront
                                            </p>
                                        </div>
                                    </div>
                                </Field>

                                <Field>
                                    <FieldLabel>
                                        <div class="flex items-center gap-1.5">
                                            <Tag class="h-3.5 w-3.5" />
                                            Tags
                                        </div>
                                    </FieldLabel>
                                    <!-- Tag pills -->
                                    <div
                                        v-if="form.tags.length"
                                        class="mb-2 flex flex-wrap gap-1.5"
                                    >
                                        <span
                                            v-for="(tag, i) in form.tags"
                                            :key="tag"
                                            class="flex items-center gap-1 rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary"
                                        >
                                            {{ tag }}
                                            <button
                                                type="button"
                                                @click="removeTag(i)"
                                                class="ml-0.5 opacity-60 hover:opacity-100"
                                            >
                                                <X class="h-3 w-3" />
                                            </button>
                                        </span>
                                    </div>
                                    <div class="flex gap-2">
                                        <Input
                                            v-model="tagInput"
                                            placeholder="e.g. gaming, pro (press Enter)"
                                            @keydown="handleTagKeydown"
                                        />
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            @click="addTag"
                                        >
                                            <Plus class="h-4 w-4" />
                                        </Button>
                                    </div>
                                    <p
                                        class="mt-1 text-xs text-muted-foreground"
                                    >
                                        Press Enter or comma to add a tag
                                    </p>
                                </Field>
                            </FieldSet>
                        </div>

                        <!-- Step 2: Variants -->
                        <div v-if="currentStep === 2" class="space-y-3">
                            <div
                                v-for="(variant, i) in form.variants"
                                :key="i"
                                class="rounded-lg border bg-muted/20 p-4"
                            >
                                <div
                                    class="mb-3 flex items-center justify-between"
                                >
                                    <span
                                        class="text-sm font-medium text-muted-foreground"
                                        >Variant {{ i + 1 }}</span
                                    >
                                    <button
                                        v-if="form.variants.length > 1"
                                        type="button"
                                        class="flex h-6 w-6 items-center justify-center rounded text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                                        @click="removeVariant(i)"
                                    >
                                        <Trash2 class="h-3.5 w-3.5" />
                                    </button>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <Field>
                                        <FieldLabel
                                            >Name
                                            <span class="text-destructive"
                                                >*</span
                                            ></FieldLabel
                                        >
                                        <Input
                                            v-model="variant.name"
                                            placeholder="e.g. Black"
                                        />
                                    </Field>
                                    <Field>
                                        <FieldLabel
                                            >Price (Rs.)
                                            <span class="text-destructive"
                                                >*</span
                                            ></FieldLabel
                                        >
                                        <Input
                                            type="number"
                                            step="0.01"
                                            v-model="variant.price"
                                            placeholder="0.00"
                                        />
                                    </Field>
                                    <Field>
                                        <FieldLabel>Stock</FieldLabel>
                                        <Input
                                            type="number"
                                            v-model="variant.stock_quantity"
                                            placeholder="0"
                                        />
                                    </Field>
                                </div>

                                <div class="mt-3 flex items-center gap-2">
                                    <Switch v-model="variant.is_active" />
                                    <span class="text-sm text-muted-foreground"
                                        >Active</span
                                    >
                                </div>
                            </div>

                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                @click="addVariant"
                            >
                                <Plus class="mr-1.5 h-4 w-4" /> Add Variant
                            </Button>
                        </div>

                        <!-- Navigation -->
                        <div
                            class="mt-8 flex items-center justify-between border-t pt-5"
                        >
                            <Button
                                v-if="currentStep > 1"
                                type="button"
                                variant="ghost"
                                @click="currentStep--"
                            >
                                <ChevronLeft class="mr-1 h-4 w-4" /> Back
                            </Button>
                            <div v-else />

                            <div class="flex gap-2">
                                <Button
                                    type="button"
                                    variant="outline"
                                    as-child
                                >
                                    <a :href="route('products.index')"
                                        >Cancel</a
                                    >
                                </Button>
                                <Button
                                    v-if="currentStep < totalSteps"
                                    type="button"
                                    :disabled="currentStep === 1 && !step1Valid"
                                    @click="currentStep++"
                                >
                                    Next <ChevronRight class="ml-1 h-4 w-4" />
                                </Button>
                                <Button
                                    v-else
                                    type="submit"
                                    :disabled="!step2Valid || form.processing"
                                >
                                    {{
                                        form.processing
                                            ? 'Saving…'
                                            : 'Save Product'
                                    }}
                                </Button>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
