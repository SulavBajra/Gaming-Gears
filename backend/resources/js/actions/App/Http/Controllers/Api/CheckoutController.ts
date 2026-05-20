import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\CheckoutController::createPaymentIntent
* @see app/Http/Controllers/Api/CheckoutController.php:25
* @route '/api/checkout/intent'
*/
export const createPaymentIntent = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: createPaymentIntent.url(options),
    method: 'post',
})

createPaymentIntent.definition = {
    methods: ["post"],
    url: '/api/checkout/intent',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CheckoutController::createPaymentIntent
* @see app/Http/Controllers/Api/CheckoutController.php:25
* @route '/api/checkout/intent'
*/
createPaymentIntent.url = (options?: RouteQueryOptions) => {
    return createPaymentIntent.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CheckoutController::createPaymentIntent
* @see app/Http/Controllers/Api/CheckoutController.php:25
* @route '/api/checkout/intent'
*/
createPaymentIntent.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: createPaymentIntent.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\CheckoutController::cashOnDelivery
* @see app/Http/Controllers/Api/CheckoutController.php:13
* @route '/api/checkout/cash'
*/
export const cashOnDelivery = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cashOnDelivery.url(options),
    method: 'post',
})

cashOnDelivery.definition = {
    methods: ["post"],
    url: '/api/checkout/cash',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CheckoutController::cashOnDelivery
* @see app/Http/Controllers/Api/CheckoutController.php:13
* @route '/api/checkout/cash'
*/
cashOnDelivery.url = (options?: RouteQueryOptions) => {
    return cashOnDelivery.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CheckoutController::cashOnDelivery
* @see app/Http/Controllers/Api/CheckoutController.php:13
* @route '/api/checkout/cash'
*/
cashOnDelivery.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cashOnDelivery.url(options),
    method: 'post',
})

const CheckoutController = { createPaymentIntent, cashOnDelivery }

export default CheckoutController