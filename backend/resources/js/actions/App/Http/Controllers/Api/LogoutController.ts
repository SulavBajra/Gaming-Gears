import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\LogoutController::__invoke
* @see app/Http/Controllers/Api/LogoutController.php:10
* @route '/api/logout'
*/
const LogoutController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LogoutController.url(options),
    method: 'post',
})

LogoutController.definition = {
    methods: ["post"],
    url: '/api/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\LogoutController::__invoke
* @see app/Http/Controllers/Api/LogoutController.php:10
* @route '/api/logout'
*/
LogoutController.url = (options?: RouteQueryOptions) => {
    return LogoutController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LogoutController::__invoke
* @see app/Http/Controllers/Api/LogoutController.php:10
* @route '/api/logout'
*/
LogoutController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LogoutController.url(options),
    method: 'post',
})

export default LogoutController