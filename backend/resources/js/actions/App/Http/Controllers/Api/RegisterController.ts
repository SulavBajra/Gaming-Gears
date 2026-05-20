import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\RegisterController::__invoke
* @see app/Http/Controllers/Api/RegisterController.php:12
* @route '/api/register'
*/
const RegisterController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RegisterController.url(options),
    method: 'post',
})

RegisterController.definition = {
    methods: ["post"],
    url: '/api/register',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\RegisterController::__invoke
* @see app/Http/Controllers/Api/RegisterController.php:12
* @route '/api/register'
*/
RegisterController.url = (options?: RouteQueryOptions) => {
    return RegisterController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\RegisterController::__invoke
* @see app/Http/Controllers/Api/RegisterController.php:12
* @route '/api/register'
*/
RegisterController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RegisterController.url(options),
    method: 'post',
})

export default RegisterController