import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::index
* @see app/Http/Controllers/Moderation/CourseReviewController.php:17
* @route '/moderation/courses'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/moderation/courses',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::index
* @see app/Http/Controllers/Moderation/CourseReviewController.php:17
* @route '/moderation/courses'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::index
* @see app/Http/Controllers/Moderation/CourseReviewController.php:17
* @route '/moderation/courses'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::index
* @see app/Http/Controllers/Moderation/CourseReviewController.php:17
* @route '/moderation/courses'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::index
* @see app/Http/Controllers/Moderation/CourseReviewController.php:17
* @route '/moderation/courses'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::index
* @see app/Http/Controllers/Moderation/CourseReviewController.php:17
* @route '/moderation/courses'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::index
* @see app/Http/Controllers/Moderation/CourseReviewController.php:17
* @route '/moderation/courses'
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
* @see \App\Http\Controllers\Moderation\CourseReviewController::decide
* @see app/Http/Controllers/Moderation/CourseReviewController.php:37
* @route '/moderation/courses/{course}/decide'
*/
export const decide = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: decide.url(args, options),
    method: 'post',
})

decide.definition = {
    methods: ["post"],
    url: '/moderation/courses/{course}/decide',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::decide
* @see app/Http/Controllers/Moderation/CourseReviewController.php:37
* @route '/moderation/courses/{course}/decide'
*/
decide.url = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { course: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
        args = { course: args.slug }
    }

    if (Array.isArray(args)) {
        args = {
            course: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: typeof args.course === 'object'
        ? args.course.slug
        : args.course,
    }

    return decide.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::decide
* @see app/Http/Controllers/Moderation/CourseReviewController.php:37
* @route '/moderation/courses/{course}/decide'
*/
decide.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: decide.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::decide
* @see app/Http/Controllers/Moderation/CourseReviewController.php:37
* @route '/moderation/courses/{course}/decide'
*/
const decideForm = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: decide.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Moderation\CourseReviewController::decide
* @see app/Http/Controllers/Moderation/CourseReviewController.php:37
* @route '/moderation/courses/{course}/decide'
*/
decideForm.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: decide.url(args, options),
    method: 'post',
})

decide.form = decideForm

const courses = {
    index: Object.assign(index, index),
    decide: Object.assign(decide, decide),
}

export default courses