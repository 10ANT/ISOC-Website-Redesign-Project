<?php

namespace App\Http\Requests\Admin;

use App\Enums\CategoryType;
use Illuminate\Validation\Rule;

class PostRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'alpha_dash', 'max:200', Rule::unique('posts', 'slug')->ignore($this->route('post'))],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('type', CategoryType::News->value)],
            'programme_id' => ['nullable', 'exists:programmes,id'],
            'tags' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'image' => self::IMAGE_RULES,
        ];
    }

    public function payload(): array
    {
        $data = parent::payload();

        $data['tags'] = collect(explode(',', (string) $this->input('tags')))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();
        $data['published_at'] = $this->publishedAt();
        unset($data['status']);

        return $data;
    }
}
