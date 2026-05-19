import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Api\LoginController::__invoke
* @see app/Http/Controllers/Api/LoginController.php:12
* @route '/api/login'
*/
export const login = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: login.url(options),
    method: 'post',
})

login.definition = {
    methods: ["post"],
    url: '/api/login',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\LoginController::__invoke
* @see app/Http/Controllers/Api/LoginController.php:12
* @route '/api/login'
*/
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LoginController::__invoke
* @see app/Http/Controllers/Api/LoginController.php:12
* @route '/api/login'
*/
login.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: login.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\RegisterController::__invoke
* @see app/Http/Controllers/Api/RegisterController.php:12
* @route '/api/register'
*/
export const register = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: register.url(options),
    method: 'post',
})

register.definition = {
    methods: ["post"],
    url: '/api/register',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\RegisterController::__invoke
* @see app/Http/Controllers/Api/RegisterController.php:12
* @route '/api/register'
*/
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\RegisterController::__invoke
* @see app/Http/Controllers/Api/RegisterController.php:12
* @route '/api/register'
*/
register.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: register.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\ProductHomeController::home
* @see app/Http/Controllers/Api/ProductHomeController.php:11
* @route '/api/home'
*/
export const home = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

home.definition = {
    methods: ["get","head"],
    url: '/api/home',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ProductHomeController::home
* @see app/Http/Controllers/Api/ProductHomeController.php:11
* @route '/api/home'
*/
home.url = (options?: RouteQueryOptions) => {
    return home.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ProductHomeController::home
* @see app/Http/Controllers/Api/ProductHomeController.php:11
* @route '/api/home'
*/
home.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ProductHomeController::home
* @see app/Http/Controllers/Api/ProductHomeController.php:11
* @route '/api/home'
*/
home.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: home.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ShopController::shop
* @see app/Http/Controllers/Api/ShopController.php:15
* @route '/api/shop/{product}'
*/
export const shop = (args: { product: string | number | { slug: string | number } } | [product: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: shop.url(args, options),
    method: 'get',
})

shop.definition = {
    methods: ["get","head"],
    url: '/api/shop/{product}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ShopController::shop
* @see app/Http/Controllers/Api/ShopController.php:15
* @route '/api/shop/{product}'
*/
shop.url = (args: { product: string | number | { slug: string | number } } | [product: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions) => {
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

    return shop.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ShopController::shop
* @see app/Http/Controllers/Api/ShopController.php:15
* @route '/api/shop/{product}'
*/
shop.get = (args: { product: string | number | { slug: string | number } } | [product: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: shop.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ShopController::shop
* @see app/Http/Controllers/Api/ShopController.php:15
* @route '/api/shop/{product}'
*/
shop.head = (args: { product: string | number | { slug: string | number } } | [product: string | number | { slug: string | number } ] | string | number | { slug: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: shop.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\LogoutController::__invoke
* @see app/Http/Controllers/Api/LogoutController.php:10
* @route '/api/logout'
*/
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '/api/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\LogoutController::__invoke
* @see app/Http/Controllers/Api/LogoutController.php:10
* @route '/api/logout'
*/
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LogoutController::__invoke
* @see app/Http/Controllers/Api/LogoutController.php:10
* @route '/api/logout'
*/
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\CartController::cart
* @see app/Http/Controllers/Api/CartController.php:21
* @route '/api/cart'
*/
export const cart = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cart.url(options),
    method: 'post',
})

cart.definition = {
    methods: ["post"],
    url: '/api/cart',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CartController::cart
* @see app/Http/Controllers/Api/CartController.php:21
* @route '/api/cart'
*/
cart.url = (options?: RouteQueryOptions) => {
    return cart.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::cart
* @see app/Http/Controllers/Api/CartController.php:21
* @route '/api/cart'
*/
cart.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cart.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::profile
* @see app/Http/Controllers/Api/CustomerProfileController.php:17
* @route '/api/profile'
*/
export const profile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})

profile.definition = {
    methods: ["get","head"],
    url: '/api/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::profile
* @see app/Http/Controllers/Api/CustomerProfileController.php:17
* @route '/api/profile'
*/
profile.url = (options?: RouteQueryOptions) => {
    return profile.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::profile
* @see app/Http/Controllers/Api/CustomerProfileController.php:17
* @route '/api/profile'
*/
profile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::profile
* @see app/Http/Controllers/Api/CustomerProfileController.php:17
* @route '/api/profile'
*/
profile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: profile.url(options),
    method: 'head',
})

const api = {
    login: Object.assign(login, login),
    register: Object.assign(register, register),
    home: Object.assign(home, home),
    shop: Object.assign(shop, shop),
    logout: Object.assign(logout, logout),
    cart: Object.assign(cart, cart),
    profile: Object.assign(profile, profile),
}

export default api