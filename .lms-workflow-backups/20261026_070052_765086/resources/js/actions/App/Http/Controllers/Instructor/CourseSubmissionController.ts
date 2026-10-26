import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
const CourseSubmissionController = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: CourseSubmissionController.url(args, options),
    method: 'post',
})

CourseSubmissionController.definition = {
    methods: ["post"],
    url: '/instructor/courses/{course}/submit',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
CourseSubmissionController.url = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return CourseSubmissionController.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
CourseSubmissionController.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: CourseSubmissionController.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
const CourseSubmissionControllerForm = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: CourseSubmissionController.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
CourseSubmissionControllerForm.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: CourseSubmissionController.url(args, options),
    method: 'post',
})

CourseSubmissionController.form = CourseSubmissionControllerForm

export default CourseSubmissionController