<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $posts = Post::with(['category', 'author'])
            ->latest('id')
            ->paginate($perPage);

        return $this->success(PostResource::collection($posts), 'Posts retrieved successfully');
    }

    public function store(PostRequest $request)
    {
        $post = Post::create($request->validated());
        $post->load(['category', 'author']);
        return $this->created(new PostResource($post), 'Post created successfully');
    }

    public function show(Post $post)
    {
        $post->loadMissing(['category', 'author']);
        return $this->success(new PostResource($post), 'Post fetched successfully');
    }

    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->validated());
        $post->load(['category', 'author']);
        return $this->success(new PostResource($post), 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return $this->success(message: 'Post deleted successfully');
    }
}
