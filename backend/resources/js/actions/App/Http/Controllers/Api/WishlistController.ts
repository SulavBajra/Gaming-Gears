import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\WishlistController::show
* @see app/Http/Controllers/Api/WishlistController.php:67
* @route '/api/wishlist'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/wishlist',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\WishlistController::show
* @see app/Http/Controllers/Api/WishlistController.php:67
* @route '/api/wishlist'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\WishlistController::show
* @see app/Http/Controllers/Api/WishlistController.php:67
* @route '/api/wishlist'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\WishlistController::show
* @see app/Http/Controllers/Api/WishlistController.php:67
* @route '/api/wishlist'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\WishlistController::update
* @see app/Http/Controllers/Api/WishlistController.php:79
* @route '/api/wishlist'
*/
export const update = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/api/wishlist',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Api\WishlistController::update
* @see app/Http/Controllers/Api/WishlistController.php:79
* @route '/api/wishlist'
*/
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\WishlistController::update
* @see app/Http/Controllers/Api/WishlistController.php:79
* @route '/api/wishlist'
*/
update.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Api\WishlistController::destroy
* @see app/Http/Controllers/Api/WishlistController.php:103
* @route '/api/wishlist/{wishlist}'
*/
export const destroy = (args: { wishlist: string | number | { id: string | number } } | [wishlist: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/wishlist/{wishlist}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\WishlistController::destroy
* @see app/Http/Controllers/Api/WishlistController.php:103
* @route '/api/wishlist/{wishlist}'
*/
destroy.url = (args: { wishlist: string | number | { id: string | number } } | [wishlist: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { wishlist: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { wishlist: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            wishlist: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        wishlist: typeof args.wishlist === 'object'
        ? args.wishlist.id
        : args.wishlist,
    }

    return destroy.definition.url
            .replace('{wishlist}', parsedArgs.wishlist.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\WishlistController::destroy
* @see app/Http/Controllers/Api/WishlistController.php:103
* @route '/api/wishlist/{wishlist}'
*/
destroy.delete = (args: { wishlist: string | number | { id: string | number } } | [wishlist: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const WishlistController = { show, update, destroy }

export default WishlistController