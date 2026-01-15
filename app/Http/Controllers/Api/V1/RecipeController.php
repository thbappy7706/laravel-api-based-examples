<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $search = Recipe::search($request->search);

        /* -------------------------
         | Scout Filters (Indexed)
         |--------------------------*/

        if ($request->filled('cuisine')) {
            $search->where('cuisine', $request->cuisine);
        }

        if ($request->filled('dietary')) {
            foreach (explode(',', $request->dietary) as $diet) {
                $search->where('dietary_info', $diet);
            }
        }

        if ($request->filled('max_time')) {
            $search->where('total_time', '<=', (int) $request->max_time);
        }

        /* -------------------------
         | Eloquent Query Modifiers
         |--------------------------*/

//        $recipes = Recipe::search($request->search)
//            ->query(fn ($query) => $query->with('ingredients'))
//            ->paginate();


        $recipes = $search
            ->query(fn ($query) =>
            $query->with(['user', 'ingredients'])
                ->when(
                    $request->filled('ingredient_id'),
                    fn ($q) => $q->withIngredient((int) $request->ingredient_id)
                )
            )
            ->paginate(10);

        return RecipeResource::collection($recipes);
    }
}
