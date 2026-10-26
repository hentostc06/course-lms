import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::index
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:16
* @route '/moderation/instructors'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/moderation/instructors',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::index
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:16
* @route '/moderation/instructors'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::index
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:16
* @route '/moderation/instructors'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::index
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:16
* @route '/moderation/instructors'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::index
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:16
* @route '/moderation/instructors'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::index
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:16
* @route '/moderation/instructors'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::index
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:16
* @route '/moderation/instructors'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::approve
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:36
* @route '/moderation/instructors/{profile}/approve'
*/
export const approve = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

approve.definition = {
    methods: ["post"],
    url: '/moderation/instructors/{profile}/approve',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::approve
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:36
* @route '/moderation/instructors/{profile}/approve'
*/
approve.url = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { profile: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { profile: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            profile: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        profile: typeof args.profile === 'object'
        ? args.profile.id
        : args.profile,
    }

    return approve.definition.url
            .replace('{profile}', parsedArgs.profile.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::approve
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:36
* @route '/moderation/instructors/{profile}/approve'
*/
approve.post = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::approve
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:36
* @route '/moderation/instructors/{profile}/approve'
*/
const approveForm = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: approve.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Moderation\InstructorReviewController::approve
* @see app/Http/Controllers/Moderation/InstructorReviewController.php:36
* @route '/moderation/instructors/{profile}/approve'
*/
approveForm.post = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: approve.url(args, options),
    method: 'post',
})

approve.form = approveForm

const InstructorReviewController = { index, approve }

export default InstructorReviewController