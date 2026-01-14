<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BookmarkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'url' => [
                'required',
                'url',
                'max:2048',
                'unique:bookmarks,url,NULL,id,user_id,' . auth()->id(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'Bookmark URL is required',
            'url.url' => 'Please provide a valid URL',
            'url.unique' => 'This bookmark already exists',
        ];
    }
}
