export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    role: 'admin' | 'shop_owner';
}

export interface Shop {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    status: boolean;
    owner: User;
}

export interface Product {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    base_price: string;
    thumbnail: string | null;
    creator: User;
}

export interface ShopProduct {
    id: number;
    shop_id: number;
    product_id: number;
    price: string;
    description: string | null;
    thumbnail: string | null;
    is_active: boolean;
    product?: Product;
    shop?: Shop;
}

export interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}

export interface ApiPaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}
