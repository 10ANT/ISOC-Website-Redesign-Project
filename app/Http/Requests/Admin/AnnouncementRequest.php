<?php

namespace App\Http\Requests\Admin;

class AnnouncementRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:10000'],
            'is_pinned' => ['boolean'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', $this->filled('published_at') ? 'after:published_at' : 'after:now'],
        ];
    }

    public function payload(): array
    {
        $data = parent::payload();
        $data['is_pinned'] = $this->boolean('is_pinned');
        $data['published_at'] = $this->publishedAt();
        unset($data['status']);

        return $data;
    }
}
