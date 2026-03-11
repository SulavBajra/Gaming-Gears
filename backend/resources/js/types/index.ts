export * from './auth';
export * from './navigation';
export * from './ui';

export interface Product {
    id: number;
    name: string;
    slug: string;
    is_active: boolean;
    brand: { name: string };
    category: { name: string };
    product_variants_min_price: number | null;
    product_variants_max_price: number | null;
    product_variants_sum_stock_qty: number;
}

export interface Role {
    id: number;
    name: string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    roles: string[];
}

export interface ProductMedia {
    id: number;
    collection_name: string;
    original_url: string;
    preview_url: string;
}

export interface ProductHome {
    id: number;
    name: string;
    slug: string;
    description: string;
    price: number;
    brand: { id: number; name: string; slug: string };
    category: { id: number; name: string; slug: string };
    gender: { id: number; name: string; slug: string };
    media: ProductMedia[];
}
