<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class PageRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'alpha_dash', 'max:150', Rule::unique('pages', 'slug')->ignore($this->route('page'))],
            'summary' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'is_published' => ['boolean'],
            'image' => self::IMAGE_RULES,
        ];
    }

    public function payload(): array
    {
        return [...parent::payload(), 'is_published' => $this->boolean('is_published')];
    }
}
