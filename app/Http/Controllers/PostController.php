<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
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

        //check if there is any key in request with only number
        if(preg_match('/[0-9]/', implode('', array_keys($request->all())))){
            $file_controller = new FileController();
            foreach($request->all() as $key => $value){
                if(is_numeric($key)){
                    $file_controller->attachFileToModel($value, 'App\Models\Post', $post);
                }
            }
        }
        if($request->mentions){
            $post->mentionedUsers()->sync($request->mentions);
        }
        return response()
            ->json([
                'message' => 'Post created successfully',
                'data' => $post->load('files','mentionedUsers')
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

    public function repost(Post $post)
    {
        $user = User::find(auth()->user()->id);
        $post_was_reposted = $user->reposts()->where('post_id', $post->id)->exists();
        if($post_was_reposted){
            $post->repostedByUsers()
            ->where('user_id', $user->id)
            ->updateExistingPivot($user->id,
                ['reposted_count' => $post->repostedByUsers()
                    ->where('user_id', $user->id)
                    ->reposted_count + 1
                ]
            );
        }
        $post->reposted_count = $post->reposted_count + 1;
        $post->save();
        return response()
            ->json([
                'message' => 'Post reposted successfully',
                'data' => $post
            ], 200);
    }

    public function commentPost(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = Comment::create([
            'body' => $validated['body'],
            'user_id' => auth()->user()->id,
            'post_id' => $post->id
        ]);

        return response()
            ->json([
                'message' => 'Comment created successfully',
                'data' => $comment
            ], 200);
    }
}
