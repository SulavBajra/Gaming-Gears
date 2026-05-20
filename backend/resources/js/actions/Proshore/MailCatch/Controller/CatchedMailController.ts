import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
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

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::list
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:26
* @route '/__mailbox/api/emails'
*/
export const list = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

list.definition = {
    methods: ["get","head"],
    url: '/__mailbox/api/emails',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::list
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:26
* @route '/__mailbox/api/emails'
*/
list.url = (options?: RouteQueryOptions) => {
    return list.definition.url + queryParams(options)
}

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::list
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:26
* @route '/__mailbox/api/emails'
*/
list.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: list.url(options),
    method: 'get',
})

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::list
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:26
* @route '/__mailbox/api/emails'
*/
list.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: list.url(options),
    method: 'head',
})

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::show
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:43
* @route '/__mailbox/api/emails/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/__mailbox/api/emails/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::show
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:43
* @route '/__mailbox/api/emails/{id}'
*/
show.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return show.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::show
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:43
* @route '/__mailbox/api/emails/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::show
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:43
* @route '/__mailbox/api/emails/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::markAsRead
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:52
* @route '/__mailbox/api/emails/{id}/read'
*/
export const markAsRead = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead.url(args, options),
    method: 'post',
})

markAsRead.definition = {
    methods: ["post"],
    url: '/__mailbox/api/emails/{id}/read',
} satisfies RouteDefinition<["post"]>

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::markAsRead
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:52
* @route '/__mailbox/api/emails/{id}/read'
*/
markAsRead.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return markAsRead.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::markAsRead
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:52
* @route '/__mailbox/api/emails/{id}/read'
*/
markAsRead.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead.url(args, options),
    method: 'post',
})

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::destroy
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:62
* @route '/__mailbox/api/emails/{id}'
*/
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/__mailbox/api/emails/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::destroy
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:62
* @route '/__mailbox/api/emails/{id}'
*/
destroy.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return destroy.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::destroy
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:62
* @route '/__mailbox/api/emails/{id}'
*/
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::destroyAll
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:70
* @route '/__mailbox/api/emails'
*/
export const destroyAll = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAll.url(options),
    method: 'delete',
})

destroyAll.definition = {
    methods: ["delete"],
    url: '/__mailbox/api/emails',
} satisfies RouteDefinition<["delete"]>

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::destroyAll
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:70
* @route '/__mailbox/api/emails'
*/
destroyAll.url = (options?: RouteQueryOptions) => {
    return destroyAll.definition.url + queryParams(options)
}

/**
* @see \Proshore\MailCatch\Controller\CatchedMailController::destroyAll
* @see vendor/proshore/laravel-mailcatch/src/Controller/CatchedMailController.php:70
* @route '/__mailbox/api/emails'
*/
destroyAll.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAll.url(options),
    method: 'delete',
})

const CatchedMailController = { index, list, show, markAsRead, destroy, destroyAll }

export default CatchedMailController