<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\BookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return BookmarkResource::collection(
            $request->user()->bookmarks()->latest()->paginate()
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookmarkRequest $request)
    {
        $bookmark = $request->user()->bookmarks()->create(
            $request->validated()
        );

        return new BookmarkResource($bookmark);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bookmark $bookmark)
    {
        return new BookmarkResource(
            $bookmark->load('user')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookmarkRequest $request, Bookmark $bookmark)
    {
        $bookmark->update($request->validated());

        return new BookmarkResource($bookmark);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookmark $bookmark)
    {
        $bookmark->delete();
        return $this->success(message: 'Deleted successfully');
    }
}
