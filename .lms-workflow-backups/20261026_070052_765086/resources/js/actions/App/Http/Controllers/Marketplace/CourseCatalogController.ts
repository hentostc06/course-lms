import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/'
*/
const index980bb49ee7ae63891f1d891d2fbcf1c9 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

index980bb49ee7ae63891f1d891d2fbcf1c9.definition = {
    methods: ["get","head"],
    url: '/',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9.url = (options?: RouteQueryOptions) => {
    return index980bb49ee7ae63891f1d891d2fbcf1c9.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/'
*/
const index980bb49ee7ae63891f1d891d2fbcf1c9Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index980bb49ee7ae63891f1d891d2fbcf1c9.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/'
*/
index980bb49ee7ae63891f1d891d2fbcf1c9Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index980bb49ee7ae63891f1d891d2fbcf1c9.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index980bb49ee7ae63891f1d891d2fbcf1c9.form = index980bb49ee7ae63891f1d891d2fbcf1c9Form
/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/courses'
*/
const indexae0d8013bc7dd1aeb7c9b49bac5f9e3b = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.url(options),
    method: 'get',
})

indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.definition = {
    methods: ["get","head"],
    url: '/courses',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/courses'
*/
indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.url = (options?: RouteQueryOptions) => {
    return indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/courses'
*/
indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/courses'
*/
indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/courses'
*/
const indexae0d8013bc7dd1aeb7c9b49bac5f9e3bForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/courses'
*/
indexae0d8013bc7dd1aeb7c9b49bac5f9e3bForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::index
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:16
* @route '/courses'
*/
indexae0d8013bc7dd1aeb7c9b49bac5f9e3bForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

indexae0d8013bc7dd1aeb7c9b49bac5f9e3b.form = indexae0d8013bc7dd1aeb7c9b49bac5f9e3bForm

/**
* Multiple routes resolve to \App\Http\Controllers\Marketplace\CourseCatalogController::index, so this export is a
* dictionary keyed by URI rather than a callable, with the verbs prefixed where two routes share a URI.
* Call a specific route with `index['<key>'](...)`, or import the route by name from your
* generated `routes/` directory.
*/
export const index = {
    '/': index980bb49ee7ae63891f1d891d2fbcf1c9,
    '/courses': indexae0d8013bc7dd1aeb7c9b49bac5f9e3b,
}

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::show
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:70
* @route '/courses/{slug}'
*/
export const show = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/courses/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::show
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:70
* @route '/courses/{slug}'
*/
show.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    if (Array.isArray(args)) {
        args = {
            slug: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        slug: args.slug,
    }

    return show.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::show
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:70
* @route '/courses/{slug}'
*/
show.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::show
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:70
* @route '/courses/{slug}'
*/
show.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::show
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:70
* @route '/courses/{slug}'
*/
const showForm = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::show
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:70
* @route '/courses/{slug}'
*/
showForm.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Marketplace\CourseCatalogController::show
* @see app/Http/Controllers/Marketplace/CourseCatalogController.php:70
* @route '/courses/{slug}'
*/
showForm.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const CourseCatalogController = { index, show }

export default CourseCatalogController