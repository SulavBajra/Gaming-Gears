import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\ProductHomeController::index
* @see app/Http/Controllers/Api/ProductHomeController.php:11
* @route '/api/home'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/home',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ProductHomeController::index
* @see app/Http/Controllers/Api/ProductHomeController.php:11
* @route '/api/home'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ProductHomeController::index
* @see app/Http/Controllers/Api/ProductHomeController.php:11
* @route '/api/home'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ProductHomeController::index
* @see app/Http/Controllers/Api/ProductHomeController.php:11
* @route '/api/home'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

const ProductHomeController = { index }

export default ProductHomeController