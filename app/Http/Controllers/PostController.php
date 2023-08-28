<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Controllers\FileController;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(Post::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $post = Post::create($validated);

        if(!empty($request->files)){
            $files = [];
            foreach($request->files as $file){
                $file_controller = new FileController();
                $newFile = $file_controller->attachFileToModel($file, 'App\Models\Post', $post);

                $files[] = $newFile;
            }

        }
        return response()
            ->json([
                'message' => 'Post created successfully',
                'data' => $post
            ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()
            ->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();

        $post->update($validated);

        return response()
            ->json([
                'message' => 'Post updated successfully',
                'data' => $post
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if($post->comments()->count() > 0) {
            return response()
                ->json([
                    'message' => 'Cannot delete post with comments'
                ], 400);
        }
        $post->delete();

        return response()
            ->json([
                'message' => 'Post deleted successfully'
            ], 200);
    }
}
