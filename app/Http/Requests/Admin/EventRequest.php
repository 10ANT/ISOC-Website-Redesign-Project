<?php

namespace App\Http\Requests\Admin;

use App\Enums\CategoryType;
use Illuminate\Validation\Rule;

class EventRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'alpha_dash', 'max:200', Rule::unique('events', 'slug')->ignore($this->route('event'))],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'venue' => ['nullable', 'string', 'max:255'],
            'online_url' => ['nullable', 'url', 'max:255'],
            'registration_url' => ['nullable', 'url', 'max:255'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('type', CategoryType::Event->value)],
            'programme_id' => ['nullable', 'exists:programmes,id'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
            'image' => self::IMAGE_RULES,
        ];
    }

    public function payload(): array
    {
        return [
            ...parent::payload(),
            'is_published' => $this->boolean('is_published'),
            'is_featured' => $this->boolean('is_featured'),
        ];
    }
}
