import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Learning\LearningController::index
* @see app/Http/Controllers/Learning/LearningController.php:18
* @route '/my-learning'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/my-learning',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learning\LearningController::index
* @see app/Http/Controllers/Learning/LearningController.php:18
* @route '/my-learning'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learning\LearningController::index
* @see app/Http/Controllers/Learning/LearningController.php:18
* @route '/my-learning'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::index
* @see app/Http/Controllers/Learning/LearningController.php:18
* @route '/my-learning'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::index
* @see app/Http/Controllers/Learning/LearningController.php:18
* @route '/my-learning'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::index
* @see app/Http/Controllers/Learning/LearningController.php:18
* @route '/my-learning'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::index
* @see app/Http/Controllers/Learning/LearningController.php:18
* @route '/my-learning'
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
* @see \App\Http\Controllers\Learning\LearningController::enroll
* @see app/Http/Controllers/Learning/LearningController.php:68
* @route '/courses/{course}/enroll'
*/
export const enroll = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enroll.url(args, options),
    method: 'post',
})

enroll.definition = {
    methods: ["post"],
    url: '/courses/{course}/enroll',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Learning\LearningController::enroll
* @see app/Http/Controllers/Learning/LearningController.php:68
* @route '/courses/{course}/enroll'
*/
enroll.url = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return enroll.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learning\LearningController::enroll
* @see app/Http/Controllers/Learning/LearningController.php:68
* @route '/courses/{course}/enroll'
*/
enroll.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: enroll.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::enroll
* @see app/Http/Controllers/Learning/LearningController.php:68
* @route '/courses/{course}/enroll'
*/
const enrollForm = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enroll.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::enroll
* @see app/Http/Controllers/Learning/LearningController.php:68
* @route '/courses/{course}/enroll'
*/
enrollForm.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enroll.url(args, options),
    method: 'post',
})

enroll.form = enrollForm

/**
* @see \App\Http\Controllers\Learning\LearningController::show
* @see app/Http/Controllers/Learning/LearningController.php:93
* @route '/learn/{course}/{lesson}'
*/
export const show = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/learn/{course}/{lesson}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learning\LearningController::show
* @see app/Http/Controllers/Learning/LearningController.php:93
* @route '/learn/{course}/{lesson}'
*/
show.url = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            course: args[0],
            lesson: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: typeof args.course === 'object'
        ? args.course.slug
        : args.course,
        lesson: typeof args.lesson === 'object'
        ? args.lesson.id
        : args.lesson,
    }

    return show.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace('{lesson}', parsedArgs.lesson.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learning\LearningController::show
* @see app/Http/Controllers/Learning/LearningController.php:93
* @route '/learn/{course}/{lesson}'
*/
show.get = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::show
* @see app/Http/Controllers/Learning/LearningController.php:93
* @route '/learn/{course}/{lesson}'
*/
show.head = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::show
* @see app/Http/Controllers/Learning/LearningController.php:93
* @route '/learn/{course}/{lesson}'
*/
const showForm = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::show
* @see app/Http/Controllers/Learning/LearningController.php:93
* @route '/learn/{course}/{lesson}'
*/
showForm.get = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::show
* @see app/Http/Controllers/Learning/LearningController.php:93
* @route '/learn/{course}/{lesson}'
*/
showForm.head = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Learning\LearningController::complete
* @see app/Http/Controllers/Learning/LearningController.php:139
* @route '/learn/{course}/{lesson}/progress'
*/
export const complete = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: complete.url(args, options),
    method: 'put',
})

complete.definition = {
    methods: ["put"],
    url: '/learn/{course}/{lesson}/progress',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Learning\LearningController::complete
* @see app/Http/Controllers/Learning/LearningController.php:139
* @route '/learn/{course}/{lesson}/progress'
*/
complete.url = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            course: args[0],
            lesson: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: typeof args.course === 'object'
        ? args.course.slug
        : args.course,
        lesson: typeof args.lesson === 'object'
        ? args.lesson.id
        : args.lesson,
    }

    return complete.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace('{lesson}', parsedArgs.lesson.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learning\LearningController::complete
* @see app/Http/Controllers/Learning/LearningController.php:139
* @route '/learn/{course}/{lesson}/progress'
*/
complete.put = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: complete.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::complete
* @see app/Http/Controllers/Learning/LearningController.php:139
* @route '/learn/{course}/{lesson}/progress'
*/
const completeForm = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: complete.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Learning\LearningController::complete
* @see app/Http/Controllers/Learning/LearningController.php:139
* @route '/learn/{course}/{lesson}/progress'
*/
completeForm.put = (args: { course: string | { slug: string }, lesson: number | { id: number } } | [course: string | { slug: string }, lesson: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: complete.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

complete.form = completeForm

const learning = {
    index: Object.assign(index, index),
    enroll: Object.assign(enroll, enroll),
    show: Object.assign(show, show),
    complete: Object.assign(complete, complete),
}

export default learning