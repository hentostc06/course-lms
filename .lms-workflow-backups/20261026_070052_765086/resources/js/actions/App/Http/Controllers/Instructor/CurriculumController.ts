import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Instructor\CurriculumController::show
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
export const show = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/instructor/courses/{course}/curriculum',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::show
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
show.url = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::show
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
show.get = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::show
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
show.head = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::show
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
const showForm = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::show
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
showForm.get = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::show
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
showForm.head = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Instructor\CurriculumController::storeSection
* @see app/Http/Controllers/Instructor/CurriculumController.php:44
* @route '/instructor/courses/{course}/curriculum/sections'
*/
export const storeSection = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeSection.url(args, options),
    method: 'post',
})

storeSection.definition = {
    methods: ["post"],
    url: '/instructor/courses/{course}/curriculum/sections',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::storeSection
* @see app/Http/Controllers/Instructor/CurriculumController.php:44
* @route '/instructor/courses/{course}/curriculum/sections'
*/
storeSection.url = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return storeSection.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::storeSection
* @see app/Http/Controllers/Instructor/CurriculumController.php:44
* @route '/instructor/courses/{course}/curriculum/sections'
*/
storeSection.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeSection.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::storeSection
* @see app/Http/Controllers/Instructor/CurriculumController.php:44
* @route '/instructor/courses/{course}/curriculum/sections'
*/
const storeSectionForm = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: storeSection.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::storeSection
* @see app/Http/Controllers/Instructor/CurriculumController.php:44
* @route '/instructor/courses/{course}/curriculum/sections'
*/
storeSectionForm.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: storeSection.url(args, options),
    method: 'post',
})

storeSection.form = storeSectionForm

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::storeLesson
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
export const storeLesson = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeLesson.url(args, options),
    method: 'post',
})

storeLesson.definition = {
    methods: ["post"],
    url: '/instructor/courses/{course}/curriculum/sections/{section}/lessons',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::storeLesson
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
storeLesson.url = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            course: args[0],
            section: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: typeof args.course === 'object'
        ? args.course.slug
        : args.course,
        section: typeof args.section === 'object'
        ? args.section.id
        : args.section,
    }

    return storeLesson.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace('{section}', parsedArgs.section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::storeLesson
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
storeLesson.post = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeLesson.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::storeLesson
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
const storeLessonForm = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: storeLesson.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::storeLesson
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
storeLessonForm.post = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: storeLesson.url(args, options),
    method: 'post',
})

storeLesson.form = storeLessonForm

const CurriculumController = { show, storeSection, storeLesson }

export default CurriculumController