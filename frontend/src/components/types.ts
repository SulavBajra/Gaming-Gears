export interface HomeProduct {
  id: number
  name: string
  slug: string
  description: string
  tags: string[]
  price: number
  categories: Category[]
  brand: Brand | null
  thumbnail: string
}

export interface ShopMeta {
  current_page: number
  from: number
  to: number
  total: number
  last_page: number
  per_page: number
  path: string
}

export interface ShopProduct {
  data: HomeProduct[]
  links: {
    first: string
    last: string
    prev: string | null
    next: string | null
  }
  meta: ShopMeta
}

export interface Category {
  id: number
  name: string
  slug: string
}

export interface Brand {
  id: number
  name: string
  slug: string
}

export interface Variant {
  id: number
  name: string
  price: number
  stock: number
}

export interface ProductView {
  id: number
  name: string
  slug: string
  description: string
  tags: string[]
  price: number
  categories: Category[]
  brand: Brand | null
  thumbnail: string
  gallery: string[]
  variants: Variant[]
}

export type User = {
  id: number
  name: string
  email: string
} | null

export interface Cart {
  items: (CartItem | GuestCartItem)[]
  total_items: number
  total_price: string
}

type BaseCartItem = {
  product_id: number
  product_variant_id: number
  quantity: number
  unit_price: string
  product_name: string
  product_variant_name: string
}

export type CartItem = BaseCartItem & {
  cart_item_id: number
  cart_id: number
  updated_at: string
  item_total_price: string
}

export type GuestCartItem = {
  product_id: number
  product_variant_id: number
  quantity: number
  unit_price: string
  product_name: string
  product_variant_name: string
  item_total_price: string
}

export type AddToCartPayload = {
  product_id: number
  product_variant_id: number
  quantity: number
}
