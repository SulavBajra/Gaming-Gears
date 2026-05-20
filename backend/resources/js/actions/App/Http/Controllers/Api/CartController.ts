import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\CartController::store
* @see app/Http/Controllers/Api/CartController.php:21
* @route '/api/cart'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/cart',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CartController::store
* @see app/Http/Controllers/Api/CartController.php:21
* @route '/api/cart'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::store
* @see app/Http/Controllers/Api/CartController.php:21
* @route '/api/cart'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\CartController::index
* @see app/Http/Controllers/Api/CartController.php:28
* @route '/api/cart'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/cart',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\CartController::index
* @see app/Http/Controllers/Api/CartController.php:28
* @route '/api/cart'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::index
* @see app/Http/Controllers/Api/CartController.php:28
* @route '/api/cart'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\CartController::index
* @see app/Http/Controllers/Api/CartController.php:28
* @route '/api/cart'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\CartController::destroy
* @see app/Http/Controllers/Api/CartController.php:39
* @route '/api/cart/{cart}'
*/
export const destroy = (args: { cart: string | number | { id: string | number } } | [cart: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/cart/{cart}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\CartController::destroy
* @see app/Http/Controllers/Api/CartController.php:39
* @route '/api/cart/{cart}'
*/
destroy.url = (args: { cart: string | number | { id: string | number } } | [cart: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cart: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { cart: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            cart: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        cart: typeof args.cart === 'object'
        ? args.cart.id
        : args.cart,
    }

    return destroy.definition.url
            .replace('{cart}', parsedArgs.cart.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::destroy
* @see app/Http/Controllers/Api/CartController.php:39
* @route '/api/cart/{cart}'
*/
destroy.delete = (args: { cart: string | number | { id: string | number } } | [cart: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Api\CartController::deleteItem
* @see app/Http/Controllers/Api/CartController.php:46
* @route '/api/cart/items/{cartItem}'
*/
export const deleteItem = (args: { cartItem: string | number | { id: string | number } } | [cartItem: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteItem.url(args, options),
    method: 'delete',
})

deleteItem.definition = {
    methods: ["delete"],
    url: '/api/cart/items/{cartItem}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\CartController::deleteItem
* @see app/Http/Controllers/Api/CartController.php:46
* @route '/api/cart/items/{cartItem}'
*/
deleteItem.url = (args: { cartItem: string | number | { id: string | number } } | [cartItem: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cartItem: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { cartItem: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            cartItem: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        cartItem: typeof args.cartItem === 'object'
        ? args.cartItem.id
        : args.cartItem,
    }

    return deleteItem.definition.url
            .replace('{cartItem}', parsedArgs.cartItem.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::deleteItem
* @see app/Http/Controllers/Api/CartController.php:46
* @route '/api/cart/items/{cartItem}'
*/
deleteItem.delete = (args: { cartItem: string | number | { id: string | number } } | [cartItem: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteItem.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Api\CartController::updateItem
* @see app/Http/Controllers/Api/CartController.php:53
* @route '/api/cart/items/{cartItem}'
*/
export const updateItem = (args: { cartItem: string | number | { id: string | number } } | [cartItem: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateItem.url(args, options),
    method: 'patch',
})

updateItem.definition = {
    methods: ["patch"],
    url: '/api/cart/items/{cartItem}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Api\CartController::updateItem
* @see app/Http/Controllers/Api/CartController.php:53
* @route '/api/cart/items/{cartItem}'
*/
updateItem.url = (args: { cartItem: string | number | { id: string | number } } | [cartItem: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cartItem: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { cartItem: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            cartItem: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        cartItem: typeof args.cartItem === 'object'
        ? args.cartItem.id
        : args.cartItem,
    }

    return updateItem.definition.url
            .replace('{cartItem}', parsedArgs.cartItem.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::updateItem
* @see app/Http/Controllers/Api/CartController.php:53
* @route '/api/cart/items/{cartItem}'
*/
updateItem.patch = (args: { cartItem: string | number | { id: string | number } } | [cartItem: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateItem.url(args, options),
    method: 'patch',
})

const CartController = { store, index, destroy, deleteItem, updateItem }

export default CartController