<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Recipe extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'instructions',
        'cuisine',
        'prep_time',
        'cook_time',
        'servings',
        'dietary_info',
    ];

    protected $casts = [
        'dietary_info' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }


    public function scopeCuisine(Builder $query, string $cuisine)
    {
        return $query->where('cuisine', $cuisine);
    }

    /* ⏱ Max total time (prep + cook) */
    public function scopeMaxTotalTime(Builder $query, int $minutes)
    {
        return $query->whereRaw('(prep_time + cook_time) <= ?', [$minutes]);
    }

    /* 🥗 Dietary requirements */
    public function scopeDietary(Builder $query, array $requirements)
    {
        foreach ($requirements as $requirement) {
            $query->whereJsonContains('dietary_info', $requirement);
        }

        return $query;
    }

    /* 🔍 Text search */
    public function scopeSearch(Builder $query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /* 🧄 Filter by ingredient */
    public function scopeWithIngredient(Builder $query, int $ingredientId)
    {
        return $query->whereHas('ingredients', function ($q) use ($ingredientId) {
            $q->where('ingredients.id', $ingredientId);
        });
    }


    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'cuisine' => $this->cuisine,
            'ingredients' => $this->ingredients->pluck('name')->toArray(),
        ];
    }
}
