<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => $this->whenLoaded('author', function () {
                $author = $this->author;
                return [
                    'id' => $author->id,
                    'name' => $author->name,
                    'email' => $author->email,
                ];
            }),
        ];
    }
}
