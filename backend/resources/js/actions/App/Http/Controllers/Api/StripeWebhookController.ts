import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\StripeWebhookController::handle
* @see app/Http/Controllers/Api/StripeWebhookController.php:12
* @route '/api/stripe/webhook'
*/
export const handle = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handle.url(options),
    method: 'post',
})

handle.definition = {
    methods: ["post"],
    url: '/api/stripe/webhook',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\StripeWebhookController::handle
* @see app/Http/Controllers/Api/StripeWebhookController.php:12
* @route '/api/stripe/webhook'
*/
handle.url = (options?: RouteQueryOptions) => {
    return handle.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\StripeWebhookController::handle
* @see app/Http/Controllers/Api/StripeWebhookController.php:12
* @route '/api/stripe/webhook'
*/
handle.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: handle.url(options),
    method: 'post',
})

const StripeWebhookController = { handle }

export default StripeWebhookController