<?php

namespace App\Http\Requests\Admin;

use App\Enums\ProgrammeStatus;
use Illuminate\Validation\Rule;

class ProgrammeRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'alpha_dash', 'max:150', Rule::unique('programmes', 'slug')->ignore($this->route('programme'))],
            'icon' => ['nullable', 'string', 'max:60', 'regex:/^[a-z0-9\- ]+$/'],
            'summary' => ['required', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(ProgrammeStatus::class)],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'image' => self::IMAGE_RULES,
        ];
    }

    public function payload(): array
    {
        return [
            ...parent::payload(),
            'is_featured' => $this->boolean('is_featured'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
    }
}
