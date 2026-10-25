<?php

namespace App\Http\Requests\Marketplace;

use App\Domain\Catalog\Enums\CourseLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseCatalogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<mixed>> */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100', 'alpha_dash:ascii'],
            'level' => ['nullable', Rule::enum(CourseLevel::class)],
            'price' => ['nullable', Rule::in(['free', 'paid'])],
            'sort' => ['nullable', Rule::in(['newest', 'price_asc', 'price_desc'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'q' => $this->filled('q') ? trim((string) $this->input('q')) : null,
        ]);
    }
}
