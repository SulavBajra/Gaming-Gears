import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
import api from './api'
/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::index
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:16
* @route '/__mailbox'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/__mailbox',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::index
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:16
* @route '/__mailbox'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::index
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:16
* @route '/__mailbox'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::index
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:16
* @route '/__mailbox'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

const mailcatch = {
    index: Object.assign(index, index),
    api: Object.assign(api, api),
}

export default mailcatch