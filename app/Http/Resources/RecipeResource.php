<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'instructions' => $this->instructions,
            'cuisine'      => $this->cuisine,
            'prep_time'    => $this->prep_time,
            'cook_time'    => $this->cook_time,
            'servings'     => $this->servings,
            'dietary_info' => $this->dietary_info,

            'user' => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],

            'ingredients' => IngredientResource::collection(
                $this->whenLoaded('ingredients')
            ),

            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),

        ];
    }
}
