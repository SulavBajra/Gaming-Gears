import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\CustomerProfileController::show
* @see app/Http/Controllers/Api/CustomerProfileController.php:17
* @route '/api/profile'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::show
* @see app/Http/Controllers/Api/CustomerProfileController.php:17
* @route '/api/profile'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::show
* @see app/Http/Controllers/Api/CustomerProfileController.php:17
* @route '/api/profile'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::show
* @see app/Http/Controllers/Api/CustomerProfileController.php:17
* @route '/api/profile'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::store
* @see app/Http/Controllers/Api/CustomerProfileController.php:32
* @route '/api/profile'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/profile',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::store
* @see app/Http/Controllers/Api/CustomerProfileController.php:32
* @route '/api/profile'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::store
* @see app/Http/Controllers/Api/CustomerProfileController.php:32
* @route '/api/profile'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::update
* @see app/Http/Controllers/Api/CustomerProfileController.php:45
* @route '/api/profile'
*/
const update1910360d79a6e38211eaafe23a2a74b7 = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update1910360d79a6e38211eaafe23a2a74b7.url(options),
    method: 'put',
})

update1910360d79a6e38211eaafe23a2a74b7.definition = {
    methods: ["put"],
    url: '/api/profile',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::update
* @see app/Http/Controllers/Api/CustomerProfileController.php:45
* @route '/api/profile'
*/
update1910360d79a6e38211eaafe23a2a74b7.url = (options?: RouteQueryOptions) => {
    return update1910360d79a6e38211eaafe23a2a74b7.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::update
* @see app/Http/Controllers/Api/CustomerProfileController.php:45
* @route '/api/profile'
*/
update1910360d79a6e38211eaafe23a2a74b7.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update1910360d79a6e38211eaafe23a2a74b7.url(options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::update
* @see app/Http/Controllers/Api/CustomerProfileController.php:45
* @route '/api/profile'
*/
const update1910360d79a6e38211eaafe23a2a74b7 = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update1910360d79a6e38211eaafe23a2a74b7.url(options),
    method: 'patch',
})

update1910360d79a6e38211eaafe23a2a74b7.definition = {
    methods: ["patch"],
    url: '/api/profile',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::update
* @see app/Http/Controllers/Api/CustomerProfileController.php:45
* @route '/api/profile'
*/
update1910360d79a6e38211eaafe23a2a74b7.url = (options?: RouteQueryOptions) => {
    return update1910360d79a6e38211eaafe23a2a74b7.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CustomerProfileController::update
* @see app/Http/Controllers/Api/CustomerProfileController.php:45
* @route '/api/profile'
*/
update1910360d79a6e38211eaafe23a2a74b7.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update1910360d79a6e38211eaafe23a2a74b7.url(options),
    method: 'patch',
})

export const update = {
    '/api/profile': update1910360d79a6e38211eaafe23a2a74b7,
    '/api/profile': update1910360d79a6e38211eaafe23a2a74b7,
}

const CustomerProfileController = { show, store, update }

export default CustomerProfileController