<?php

namespace App\Http\Requests\Admin;

use App\Enums\CategoryType;
use App\Enums\ResourceType;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ResourceRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'alpha_dash', 'max:200', Rule::unique('resources', 'slug')->ignore($this->route('resource'))],
            'description' => ['required', 'string', 'max:3000'],
            'type' => ['required', Rule::enum(ResourceType::class)],
            'author' => ['nullable', 'string', 'max:150'],
            'published_on' => ['nullable', 'date'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('type', CategoryType::Resource->value)],
            'programme_id' => ['nullable', 'exists:programmes,id'],
            'external_url' => ['nullable', 'url', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,csv,txt,zip', 'max:20480'],
            'is_members_only' => ['boolean'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }

    /** A resource needs either an uploaded file or an external link. */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $hasExistingFile = filled($this->route('resource')?->file_path) && ! $this->boolean('remove_file');

                if (! $this->hasFile('file') && ! $hasExistingFile && blank($this->input('external_url'))) {
                    $validator->errors()->add('file', 'Upload a file or provide an external link.');
                }
            },
        ];
    }

    public function payload(): array
    {
        return [
            ...parent::payload(),
            'is_members_only' => $this->boolean('is_members_only'),
            'is_published' => $this->boolean('is_published'),
            'is_featured' => $this->boolean('is_featured'),
        ];
    }
}
