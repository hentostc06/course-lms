<?php

namespace App\Http\Requests\Instructor;

use App\Domain\Catalog\Enums\CourseLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        // COURSE_SINGLE_LOGIN_V1: otorisasi sebelum validasi data.
        $course = $this->route('course');
        return $course instanceof \App\Domain\Catalog\Models\Course
            ? ($this->user()?->can('update', $course) ?? false)
            : ($this->user()?->can('create', \App\Domain\Catalog\Models\Course::class) ?? false);
    }

    /** @return array<string, list<mixed>> */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('is_active', true)],
            'title' => ['required', 'string', 'min:8', 'max:180'],
            'summary' => ['required', 'string', 'min:20', 'max:320'],
            'description' => ['required', 'string', 'min:50', 'max:20000'],
            'price_idr' => ['required', 'integer', 'min:0', 'max:50000000'],
            'level' => ['required', Rule::enum(CourseLevel::class)],
            'language' => ['required', Rule::in(['id', 'en'])],
            'outcomes' => ['required', 'array', 'min:1', 'max:15'],
            'outcomes.*' => ['required', 'string', 'min:5', 'max:250'],
            'requirements' => ['nullable', 'array', 'max:15'],
            'requirements.*' => ['required', 'string', 'max:250'],
            'audience' => ['nullable', 'array', 'max:15'],
            'audience.*' => ['required', 'string', 'max:250'],
            'thumbnail_url' => ['nullable', 'url:http,https', 'max:255'],
        ];
    }
}
