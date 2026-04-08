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

export interface FallBackCategory {
  '1': 'keyboard'
  '7': 'Mice'
  '13': 'Headsets'
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
  is_in_wishlist: boolean
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

export interface Profile {
  id: number
  name: string
  email: string
  customer: Customer
  address: Address
}

export interface Address {
  id: number
  first_name: string
  last_name: string
  company: string | null
  address_line_1: string
  address_line_2: string | null
  city: string
  state: string
}

export interface Customer {
  id: number
  phone: string
  date_of_birth: string
  gender: string | null
}

export interface Wishlist {
  id: number
  product_id: number
  product: WishlistItem
}

export interface WishlistItem {
  name: string
  slug: string
  tags: string[]
  thumbnail: string | null
  variants: Variant[]
}

export interface Brand {
  id: number
  name: string
  slug: string
  logo_url: string | null
}
