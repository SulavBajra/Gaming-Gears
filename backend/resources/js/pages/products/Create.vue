<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Plus, Trash2, ChevronRight, ChevronLeft } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { route } from 'ziggy-js';
import { Badge } from '@/components/ui/badge';
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
    FieldError,
    FieldGroup,
    FieldLabel,
    FieldSet,
    FieldDescription,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
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
interface Gender {
    id: number;
    name: string;
}

const props = defineProps<{
    brands: Brand[];
    categories: Category[];
    genders: Gender[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Products', href: route('products.index') },
    { title: 'Add Product', href: route('products.create') },
];

// ── Step management ────────────────────────────────────
const currentStep = ref(1);
const totalSteps = 3;

const stepLabels = ['Product Info', 'Colorways', 'Variants'];

// ── Colorway & Variant types ───────────────────────────
interface Variant {
    sku: string;
    size: string;
    width: string;
    price: string;
    compare_at_price: string;
    stock_qty: string;
    is_active: boolean;
}

interface Colorway {
    name: string;
    colorway_code: string;
    release_date: string;
    is_limited_edition: boolean;
    images: File[];
    imagePreviews: string[];
    variants: Variant[];
}

// ── Form ───────────────────────────────────────────────
const thumbnailPreview = ref<string | null>(null);

const form = useForm({
    // Step 1
    name: '',
    description: '',
    brand_id: '',
    category_id: '',
    gender_id: '',
    is_active: true,
    thumbnail: null as File | null,
    // Step 2 & 3 — sent as JSON
    colorways: [] as Colorway[],
});

// ── Thumbnail ──────────────────────────────────────────
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

// ── Colorway helpers ───────────────────────────────────
const newColorway = (): Colorway => ({
    name: '',
    colorway_code: '',
    release_date: '',
    is_limited_edition: false,
    images: [],
    imagePreviews: [],
    variants: [newVariant()],
});

const addColorway = () => form.colorways.push(newColorway());

const removeColorway = (ci: number) => form.colorways.splice(ci, 1);

const handleColorwayImages = (e: Event, ci: number) => {
    const files = Array.from((e.target as HTMLInputElement).files ?? []);
    form.colorways[ci].images = files;
    form.colorways[ci].imagePreviews = files.map((f) => URL.createObjectURL(f));
};

const removeColorwayImage = (ci: number, ii: number) => {
    form.colorways[ci].images.splice(ii, 1);
    form.colorways[ci].imagePreviews.splice(ii, 1);
};

// ── Variant helpers ────────────────────────────────────
const newVariant = (): Variant => ({
    sku: '',
    size: '',
    width: '',
    price: '',
    compare_at_price: '',
    stock_qty: '0',
    is_active: true,
});

const addVariant = (ci: number) =>
    form.colorways[ci].variants.push(newVariant());
const removeVariant = (ci: number, vi: number) =>
    form.colorways[ci].variants.splice(vi, 1);

// ── Step validation (basic) ────────────────────────────
const step1Valid = computed(
    () => form.name && form.brand_id && form.category_id && form.gender_id,
);

const step2Valid = computed(
    () => form.colorways.length > 0 && form.colorways.every((c) => c.name),
);

// ── Submit ─────────────────────────────────────────────
const submit = () => {
    // Build FormData manually to handle nested files
    const data = new FormData();
    data.append('name', form.name);
    data.append('description', form.description);
    data.append('brand_id', form.brand_id);
    data.append('category_id', form.category_id);
    data.append('gender_id', form.gender_id);
    data.append('is_active', form.is_active ? '1' : '0');

    if (form.thumbnail) data.append('thumbnail', form.thumbnail);

    const colorwaysMeta = form.colorways.map((c) => ({
        name: c.name,
        colorway_code: c.colorway_code,
        release_date: c.release_date,
        is_limited_edition: c.is_limited_edition,
        variants: c.variants,
        image_count: c.images.length,
    }));
    data.append('colorways', JSON.stringify(colorwaysMeta));

    // Append colorway images flat: colorway_images[0][0], colorway_images[0][1]...
    form.colorways.forEach((c, ci) => {
        c.images.forEach((img, ii) => {
            data.append(`colorway_images[${ci}][${ii}]`, img);
        });
    });

    form.transform(() => data).post(route('products.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Add Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-4">
            <Card>
                <CardHeader>
                    <CardTitle>Add Product</CardTitle>
                    <CardDescription>Create a new product</CardDescription>

                    <!-- Step indicator -->
                    <div class="mt-4 flex items-center gap-2">
                        <template v-for="(label, i) in stepLabels" :key="i">
                            <div class="flex items-center gap-2">
                                <div
                                    :class="[
                                        'flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold',
                                        currentStep === i + 1
                                            ? 'bg-primary text-primary-foreground'
                                            : currentStep > i + 1
                                              ? 'bg-primary/20 text-primary'
                                              : 'bg-muted text-muted-foreground',
                                    ]"
                                >
                                    {{ i + 1 }}
                                </div>
                                <span
                                    :class="[
                                        'text-sm',
                                        currentStep === i + 1
                                            ? 'font-medium text-primary'
                                            : 'text-muted-foreground',
                                    ]"
                                    >{{ label }}</span
                                >
                            </div>
                            <ChevronRight
                                v-if="i < stepLabels.length - 1"
                                class="h-4 w-4 text-muted-foreground"
                            />
                        </template>
                    </div>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit">
                        <!-- ── Step 1: Product Info ── -->
                        <div v-if="currentStep === 1">
                            <FieldSet>
                                <FieldGroup>
                                    <Field>
                                        <FieldLabel for="name"
                                            >Product Name</FieldLabel
                                        >
                                        <Input
                                            id="name"
                                            v-model="form.name"
                                            autocomplete="off"
                                            placeholder="e.g. Air Max 90"
                                            :aria-invalid="!!form.errors.name"
                                        />
                                        <FieldError v-if="form.errors.name">{{
                                            form.errors.name
                                        }}</FieldError>
                                    </Field>

                                    <div
                                        class="grid grid-cols-1 gap-4 sm:grid-cols-3"
                                    >
                                        <Field>
                                            <FieldLabel>Brand</FieldLabel>
                                            <Select v-model="form.brand_id">
                                                <SelectTrigger
                                                    :aria-invalid="
                                                        !!form.errors.brand_id
                                                    "
                                                >
                                                    <SelectValue
                                                        placeholder="Select brand"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem
                                                        v-for="brand in props.brands"
                                                        :key="brand.id"
                                                        :value="
                                                            String(brand.id)
                                                        "
                                                    >
                                                        {{ brand.name }}
                                                    </SelectItem>
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
                                            <FieldLabel>Category</FieldLabel>
                                            <Select v-model="form.category_id">
                                                <SelectTrigger
                                                    :aria-invalid="
                                                        !!form.errors
                                                            .category_id
                                                    "
                                                >
                                                    <SelectValue
                                                        placeholder="Select category"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem
                                                        v-for="category in props.categories"
                                                        :key="category.id"
                                                        :value="
                                                            String(category.id)
                                                        "
                                                    >
                                                        {{ category.name }}
                                                    </SelectItem>
                                                </SelectContent>
                                            </Select>
                                            <FieldError
                                                v-if="form.errors.category_id"
                                                >{{
                                                    form.errors.category_id
                                                }}</FieldError
                                            >
                                        </Field>

                                        <Field>
                                            <FieldLabel>Gender</FieldLabel>
                                            <Select v-model="form.gender_id">
                                                <SelectTrigger
                                                    :aria-invalid="
                                                        !!form.errors.gender_id
                                                    "
                                                >
                                                    <SelectValue
                                                        placeholder="Select gender"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem
                                                        v-for="gender in props.genders"
                                                        :key="gender.id"
                                                        :value="
                                                            String(gender.id)
                                                        "
                                                    >
                                                        {{ gender.name }}
                                                    </SelectItem>
                                                </SelectContent>
                                            </Select>
                                            <FieldError
                                                v-if="form.errors.gender_id"
                                                >{{
                                                    form.errors.gender_id
                                                }}</FieldError
                                            >
                                        </Field>
                                    </div>

                                    <Field>
                                        <FieldLabel for="description"
                                            >Description</FieldLabel
                                        >
                                        <Textarea
                                            id="description"
                                            v-model="form.description"
                                            placeholder="Optional product description"
                                            rows="4"
                                        />
                                    </Field>

                                    <Field>
                                        <FieldLabel for="thumbnail"
                                            >Thumbnail</FieldLabel
                                        >
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-lg border bg-muted"
                                            >
                                                <img
                                                    v-if="thumbnailPreview"
                                                    :src="thumbnailPreview"
                                                    class="h-full w-full object-cover"
                                                />
                                                <span
                                                    v-else
                                                    class="text-xs text-muted-foreground"
                                                    >Preview</span
                                                >
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <Input
                                                    id="thumbnail"
                                                    type="file"
                                                    accept="image/*"
                                                    class="max-w-xs"
                                                    @change="handleThumbnail"
                                                />
                                                <button
                                                    v-if="thumbnailPreview"
                                                    type="button"
                                                    class="text-left text-xs text-destructive hover:underline"
                                                    @click="removeThumbnail"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                        <FieldDescription
                                            >Max 2MB. Main product
                                            image.</FieldDescription
                                        >
                                        <FieldError
                                            v-if="form.errors.thumbnail"
                                            >{{
                                                form.errors.thumbnail
                                            }}</FieldError
                                        >
                                    </Field>

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
                        </div>

                        <!-- ── Step 2: Colorways ── -->
                        <div v-if="currentStep === 2" class="space-y-6">
                            <div
                                v-for="(colorway, ci) in form.colorways"
                                :key="ci"
                                class="rounded-lg border p-4"
                            >
                                <div
                                    class="mb-4 flex items-center justify-between"
                                >
                                    <h3 class="font-medium">
                                        Colorway {{ ci + 1 }}
                                    </h3>
                                    <button
                                        type="button"
                                        class="text-destructive hover:text-destructive/80"
                                        @click="removeColorway(ci)"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>

                                <div
                                    class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                                >
                                    <Field>
                                        <FieldLabel>Colorway Name</FieldLabel>
                                        <Input
                                            v-model="colorway.name"
                                            placeholder="e.g. Bred, University Blue"
                                        />
                                    </Field>
                                    <Field>
                                        <FieldLabel>Colorway Code</FieldLabel>
                                        <Input
                                            v-model="colorway.colorway_code"
                                            placeholder="e.g. 554724-065"
                                        />
                                    </Field>
                                    <Field>
                                        <FieldLabel>Release Date</FieldLabel>
                                        <Input
                                            v-model="colorway.release_date"
                                            type="date"
                                        />
                                    </Field>
                                    <Field
                                        orientation="horizontal"
                                        class="self-end pb-2"
                                    >
                                        <Switch
                                            :id="`limited-${ci}`"
                                            v-model="
                                                colorway.is_limited_edition
                                            "
                                        />
                                        <FieldLabel :for="`limited-${ci}`"
                                            >Limited Edition</FieldLabel
                                        >
                                    </Field>
                                </div>

                                <!-- Colorway images -->
                                <Field class="mt-4">
                                    <FieldLabel>Colorway Images</FieldLabel>
                                    <Input
                                        type="file"
                                        accept="image/*"
                                        multiple
                                        @change="
                                            (e) => handleColorwayImages(e, ci)
                                        "
                                    />
                                    <div
                                        v-if="colorway.imagePreviews.length"
                                        class="mt-3 flex flex-wrap gap-2"
                                    >
                                        <div
                                            v-for="(
                                                src, ii
                                            ) in colorway.imagePreviews"
                                            :key="ii"
                                            class="relative h-20 w-20 overflow-hidden rounded-lg border"
                                        >
                                            <img
                                                :src="src"
                                                class="h-full w-full object-cover"
                                            />
                                            <button
                                                type="button"
                                                class="absolute top-1 right-1 flex h-5 w-5 items-center justify-center rounded-full bg-black/60 text-xs text-white"
                                                @click="
                                                    removeColorwayImage(ci, ii)
                                                "
                                            >
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                </Field>
                            </div>

                            <Button
                                type="button"
                                variant="outline"
                                class="w-full"
                                @click="addColorway"
                            >
                                <Plus class="mr-2 h-4 w-4" /> Add Colorway
                            </Button>
                        </div>

                        <!-- ── Step 3: Variants ── -->
                        <div v-if="currentStep === 3" class="space-y-8">
                            <div
                                v-for="(colorway, ci) in form.colorways"
                                :key="ci"
                            >
                                <div class="mb-3 flex items-center gap-2">
                                    <h3 class="font-medium">
                                        {{
                                            colorway.name ||
                                            `Colorway ${ci + 1}`
                                        }}
                                    </h3>
                                    <Badge variant="outline"
                                        >{{
                                            colorway.variants.length
                                        }}
                                        variant{{
                                            colorway.variants.length !== 1
                                                ? 's'
                                                : ''
                                        }}</Badge
                                    >
                                </div>

                                <div class="space-y-3">
                                    <div
                                        v-for="(
                                            variant, vi
                                        ) in colorway.variants"
                                        :key="vi"
                                        class="rounded-lg border p-4"
                                    >
                                        <div
                                            class="mb-3 flex items-center justify-between"
                                        >
                                            <span
                                                class="text-sm text-muted-foreground"
                                                >Variant {{ vi + 1 }}</span
                                            >
                                            <button
                                                v-if="
                                                    colorway.variants.length > 1
                                                "
                                                type="button"
                                                class="text-destructive"
                                                @click="removeVariant(ci, vi)"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </button>
                                        </div>

                                        <div
                                            class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6"
                                        >
                                            <Field>
                                                <FieldLabel>SKU</FieldLabel>
                                                <Input
                                                    v-model="variant.sku"
                                                    placeholder="e.g. NK-AM90-10"
                                                />
                                            </Field>
                                            <Field>
                                                <FieldLabel
                                                    >Size (US)</FieldLabel
                                                >
                                                <Input
                                                    v-model="variant.size"
                                                    type="number"
                                                    min="0"
                                                    step="0.5"
                                                    placeholder="10"
                                                />
                                            </Field>
                                            <Field>
                                                <FieldLabel>Width</FieldLabel>
                                                <Input
                                                    v-model="variant.width"
                                                    placeholder="D, 2E..."
                                                />
                                            </Field>
                                            <Field>
                                                <FieldLabel>Price</FieldLabel>
                                                <Input
                                                    v-model="variant.price"
                                                    type="number"
                                                    min="0"
                                                    step="0.01"
                                                    placeholder="120.00"
                                                />
                                            </Field>
                                            <Field>
                                                <FieldLabel
                                                    >Compare At</FieldLabel
                                                >
                                                <Input
                                                    v-model="
                                                        variant.compare_at_price
                                                    "
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="150.00"
                                                />
                                            </Field>
                                            <Field>
                                                <FieldLabel>Stock</FieldLabel>
                                                <Input
                                                    v-model="variant.stock_qty"
                                                    type="number"
                                                    min="0"
                                                    placeholder="0"
                                                />
                                            </Field>
                                        </div>

                                        <div class="mt-3">
                                            <Field orientation="horizontal">
                                                <Switch
                                                    :id="`variant-active-${ci}-${vi}`"
                                                    v-model="variant.is_active"
                                                />
                                                <FieldLabel
                                                    :for="`variant-active-${ci}-${vi}`"
                                                    >Active</FieldLabel
                                                >
                                            </Field>
                                        </div>
                                    </div>
                                </div>

                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    class="mt-2"
                                    @click="addVariant(ci)"
                                >
                                    <Plus class="mr-1 h-3 w-3" /> Add Size
                                </Button>

                                <Separator
                                    v-if="ci < form.colorways.length - 1"
                                    class="mt-6"
                                />
                            </div>
                        </div>

                        <!-- ── Navigation ── -->
                        <div class="mt-6 flex justify-between">
                            <Button
                                v-if="currentStep > 1"
                                type="button"
                                variant="outline"
                                @click="currentStep--"
                            >
                                <ChevronLeft class="mr-1 h-4 w-4" /> Back
                            </Button>
                            <div v-else />

                            <div class="flex gap-3">
                                <Button variant="outline" as-child>
                                    <a :href="route('products.index')"
                                        >Cancel</a
                                    >
                                </Button>
                                <Button
                                    v-if="currentStep < totalSteps"
                                    type="button"
                                    :disabled="
                                        (currentStep === 1 && !step1Valid) ||
                                        (currentStep === 2 && !step2Valid)
                                    "
                                    @click="currentStep++"
                                >
                                    Next <ChevronRight class="ml-1 h-4 w-4" />
                                </Button>
                                <Button
                                    v-else
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    {{
                                        form.processing
                                            ? 'Saving...'
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
