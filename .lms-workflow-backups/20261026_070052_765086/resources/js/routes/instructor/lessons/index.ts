import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Instructor\CurriculumController::store
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
export const store = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instructor/courses/{course}/curriculum/sections/{section}/lessons',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::store
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
store.url = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace('{section}', parsedArgs.section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::store
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
store.post = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::store
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
const storeForm = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::store
* @see app/Http/Controllers/Instructor/CurriculumController.php:60
* @route '/instructor/courses/{course}/curriculum/sections/{section}/lessons'
*/
storeForm.post = (args: { course: string | { slug: string }, section: number | { id: number } } | [course: string | { slug: string }, section: number | { id: number } ], options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

const lessons = {
    store: Object.assign(store, store),
}

export default lessons