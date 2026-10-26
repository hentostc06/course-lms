import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::index
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:21
* @route '/instructor/courses'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/instructor/courses',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::index
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:21
* @route '/instructor/courses'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::index
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:21
* @route '/instructor/courses'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::index
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:21
* @route '/instructor/courses'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::index
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:21
* @route '/instructor/courses'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::index
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:21
* @route '/instructor/courses'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::index
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:21
* @route '/instructor/courses'
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
* @see \App\Http\Controllers\Instructor\InstructorCourseController::create
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:39
* @route '/instructor/courses/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/instructor/courses/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::create
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:39
* @route '/instructor/courses/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::create
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:39
* @route '/instructor/courses/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::create
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:39
* @route '/instructor/courses/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::create
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:39
* @route '/instructor/courses/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::create
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:39
* @route '/instructor/courses/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::create
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:39
* @route '/instructor/courses/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::store
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:50
* @route '/instructor/courses'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instructor/courses',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::store
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:50
* @route '/instructor/courses'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::store
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:50
* @route '/instructor/courses'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::store
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:50
* @route '/instructor/courses'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::store
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:50
* @route '/instructor/courses'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::edit
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:64
* @route '/instructor/courses/{course}/edit'
*/
export const edit = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/instructor/courses/{course}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::edit
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:64
* @route '/instructor/courses/{course}/edit'
*/
edit.url = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::edit
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:64
* @route '/instructor/courses/{course}/edit'
*/
edit.get = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::edit
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:64
* @route '/instructor/courses/{course}/edit'
*/
edit.head = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::edit
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:64
* @route '/instructor/courses/{course}/edit'
*/
const editForm = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::edit
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:64
* @route '/instructor/courses/{course}/edit'
*/
editForm.get = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::edit
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:64
* @route '/instructor/courses/{course}/edit'
*/
editForm.head = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::update
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:74
* @route '/instructor/courses/{course}'
*/
export const update = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/instructor/courses/{course}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::update
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:74
* @route '/instructor/courses/{course}'
*/
update.url = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::update
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:74
* @route '/instructor/courses/{course}'
*/
update.put = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::update
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:74
* @route '/instructor/courses/{course}'
*/
update.patch = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::update
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:74
* @route '/instructor/courses/{course}'
*/
const updateForm = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::update
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:74
* @route '/instructor/courses/{course}'
*/
updateForm.put = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\InstructorCourseController::update
* @see app/Http/Controllers/Instructor/InstructorCourseController.php:74
* @route '/instructor/courses/{course}'
*/
updateForm.patch = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::curriculum
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
export const curriculum = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: curriculum.url(args, options),
    method: 'get',
})

curriculum.definition = {
    methods: ["get","head"],
    url: '/instructor/courses/{course}/curriculum',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::curriculum
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
curriculum.url = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return curriculum.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::curriculum
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
curriculum.get = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: curriculum.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::curriculum
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
curriculum.head = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: curriculum.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::curriculum
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
const curriculumForm = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: curriculum.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::curriculum
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
curriculumForm.get = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: curriculum.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Instructor\CurriculumController::curriculum
* @see app/Http/Controllers/Instructor/CurriculumController.php:18
* @route '/instructor/courses/{course}/curriculum'
*/
curriculumForm.head = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: curriculum.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

curriculum.form = curriculumForm

/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
export const submit = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submit.url(args, options),
    method: 'post',
})

submit.definition = {
    methods: ["post"],
    url: '/instructor/courses/{course}/submit',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
submit.url = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return submit.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
submit.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submit.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
const submitForm = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: submit.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Instructor\CourseSubmissionController::__invoke
* @see app/Http/Controllers/Instructor/CourseSubmissionController.php:15
* @route '/instructor/courses/{course}/submit'
*/
submitForm.post = (args: { course: string | { slug: string } } | [course: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: submit.url(args, options),
    method: 'post',
})

submit.form = submitForm

const courses = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    curriculum: Object.assign(curriculum, curriculum),
    submit: Object.assign(submit, submit),
}

export default courses