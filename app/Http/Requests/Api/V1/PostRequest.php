<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $post = $this->route('post');
        $postId = $post instanceof Post ? $post->id : (is_numeric($post) ? (int)$post : null);

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($postId),
            ],
            'content' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
