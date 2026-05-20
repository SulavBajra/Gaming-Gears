import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\ShopController::show
* @see app/Http/Controllers/Api/ShopController.php:15
* @route '/api/shop/{product}'
*/
export const show = (args: { product: string | number | { slug: string | number } } | [product: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/shop/{product}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ShopController::show
* @see app/Http/Controllers/Api/ShopController.php:15
* @route '/api/shop/{product}'
*/
show.url = (args: { product: string | number | { slug: string | number } } | [product: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { product: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
        args = { product: args.slug }
    }

    if (Array.isArray(args)) {
        args = {
            product: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        product: typeof args.product === 'object'
        ? args.product.slug
        : args.product,
    }

    return show.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ShopController::show
* @see app/Http/Controllers/Api/ShopController.php:15
* @route '/api/shop/{product}'
*/
show.get = (args: { product: string | number | { slug: string | number } } | [product: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ShopController::show
* @see app/Http/Controllers/Api/ShopController.php:15
* @route '/api/shop/{product}'
*/
show.head = (args: { product: string | number | { slug: string | number } } | [product: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ShopController::similar
* @see app/Http/Controllers/Api/ShopController.php:30
* @route '/api/shop/category/{category}'
*/
export const similar = (args: { category: string | number | { slug: string | number } } | [category: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: similar.url(args, options),
    method: 'get',
})

similar.definition = {
    methods: ["get","head"],
    url: '/api/shop/category/{category}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ShopController::similar
* @see app/Http/Controllers/Api/ShopController.php:30
* @route '/api/shop/category/{category}'
*/
similar.url = (args: { category: string | number | { slug: string | number } } | [category: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { category: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
        args = { category: args.slug }
    }

    if (Array.isArray(args)) {
        args = {
            category: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        category: typeof args.category === 'object'
        ? args.category.slug
        : args.category,
    }

    return similar.definition.url
            .replace('{category}', parsedArgs.category.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ShopController::similar
* @see app/Http/Controllers/Api/ShopController.php:30
* @route '/api/shop/category/{category}'
*/
similar.get = (args: { category: string | number | { slug: string | number } } | [category: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: similar.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ShopController::similar
* @see app/Http/Controllers/Api/ShopController.php:30
* @route '/api/shop/category/{category}'
*/
similar.head = (args: { category: string | number | { slug: string | number } } | [category: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: similar.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ShopController::index
* @see app/Http/Controllers/Api/ShopController.php:43
* @route '/api/shops'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/shops',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ShopController::index
* @see app/Http/Controllers/Api/ShopController.php:43
* @route '/api/shops'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ShopController::index
* @see app/Http/Controllers/Api/ShopController.php:43
* @route '/api/shops'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ShopController::index
* @see app/Http/Controllers/Api/ShopController.php:43
* @route '/api/shops'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

const ShopController = { show, similar, index }

export default ShopController