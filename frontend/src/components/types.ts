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
