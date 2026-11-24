<?php

namespace App\Http\Requests\Admin;

use App\Enums\CategoryType;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends AdminRequest
{
    public function rules(): array
    {
        $category = $this->route('category');
        $type = $category?->type->value ?? $this->input('type');

        return [
            'name' => ['required', 'string', 'max:100'],
            'type' => [$category ? 'prohibited' : 'required', Rule::enum(CategoryType::class)],
            'slug' => [
                Rule::unique('categories', 'slug')->where('type', $type)->ignore($category),
            ],
        ];
    }

    /** Validate the slug that will actually be stored. */
    protected function prepareForValidation(): void
    {
        $this->merge(['slug' => Str::slug((string) $this->input('name'))]);
    }
}
