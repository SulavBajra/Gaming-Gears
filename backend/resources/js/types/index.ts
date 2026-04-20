export * from './auth';
export * from './navigation';
export * from './ui';

export interface Product {
    id: number;
    name: string;
    slug: string;
    is_active: boolean;
    brand: { name: string };
    categories: Category[];
    variants_max_price: number | null;
    variants_sum_stock_quantity: number;
}

export interface Role {
    id: number;
    name: string;
}

export interface Category {
    id: number;
    name: string;
    slug: string;
    parent_id: number | null;
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

export interface DashboardOrderResource {
    id: number;
    order_number: string;
    email: string;
    created_at: string;
    order_status: string;
    payment_status: string;
}

export interface Order {
    id: number;
    order_number: string;
    total: number;
    customer_email: string;
    created_at: string;
    order_status: string;
    payment_status: string;
}

export interface OrderData {
    data: Order[];
    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        path: string;
        per_page: number;
        to: number;
        total: number;
    };
}

export type OrderStatus =
    | 'Pending'
    | 'Confirmed'
    | 'Processing'
    | 'Shipped'
    | 'Delivered'
    | 'Cancelled'
    | 'Refunded';

export type PaymentStatus = 'Unpaid' | 'Paid' | 'Failed' | 'Refunded';

export interface OrderView {
    data: {
        id: number;
        order_number: string;
        total: number;
        customer_email: string;
        customer_name: string;
        customer_phone: string;
        shipping_address: {
            city: string;
            line1: string;
            line2: string;
        };
        payment_method: string;
        paid_at: string | null;
        shipped_at: string | null;
        delivered_at: string | null;
        cancelled_at: string | null;
        updated_at: string;
        order_status: {
            id: number;
            name: string;
        };

        payment_status: {
            id: number;
            name: string;
        };
    };
}
