import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\LoginController::__invoke
* @see app/Http/Controllers/Api/LoginController.php:12
* @route '/api/login'
*/
const LoginController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LoginController.url(options),
    method: 'post',
})

LoginController.definition = {
    methods: ["post"],
    url: '/api/login',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\LoginController::__invoke
* @see app/Http/Controllers/Api/LoginController.php:12
* @route '/api/login'
*/
LoginController.url = (options?: RouteQueryOptions) => {
    return LoginController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LoginController::__invoke
* @see app/Http/Controllers/Api/LoginController.php:12
* @route '/api/login'
*/
LoginController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: LoginController.url(options),
    method: 'post',
})

export default LoginController