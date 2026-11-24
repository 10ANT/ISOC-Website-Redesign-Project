<?php

namespace App\Http\Requests\Admin;

use App\Enums\PersonGroup;
use Illuminate\Validation\Rule;

class PersonRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'position' => ['nullable', 'string', 'max:120'],
            'group' => ['required', Rule::enum(PersonGroup::class)],
            'sector' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:3000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'photo' => self::IMAGE_RULES,
        ];
    }

    public function payload(): array
    {
        return [...parent::payload(), 'sort_order' => (int) $this->input('sort_order', 0)];
    }
}
