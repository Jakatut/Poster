<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\UriTrait;

class LikeController extends Controller
{

    use UriTrait;

    const TABLE = 'like';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create a new like.
     *
     * @param  Request  $request
     * @param  Int      $postId
     * @return Response
     */
    public function create(Request $request, $postId)
    {
        $user = $request->user();
        $post = Post::find($postId);
        if (empty($post)) {
            return new Response('Post not found.', 404);
        }

        // Check if the like already exists.
        $likeData = ['user_id' => $user->id, 'post_id' => $postId];
        $exists = Like::select('id')->where([['user_id', '=', $user->id], ['post_id', '=', $postId]])->exists();
        if ($exists) {
            return new Response('', 200);
        }

        $like = new Like($likeData);
        $like->save();
        $uri = $this->getUri($request, $like->id);
        return (new Response(''))->header('Resource-Location', $uri);
    }

    /**
     * Get a list of all likes.
     *
     * @param Int   $postId
     * @return Response|JsonResponse
     */
    public function find($postId)
    {
        $likes = Like::all()->where('post_id', '=', $postId);
        if (count($likes) == 0) {
            return new Response('Likes not found.', 404);
        }
        return new JsonResponse($likes, 200);
    }

    /**
     * Delete a like by id.
     *
     * @param  int   $id
     * @return Response
     */
    public function delete($id)
    {
        $like = Like::find($id);
        if (!$like) {
            return new Response('Like not found.', 404);
        }

        $like->delete();
        return new Response('', 204);
    }
}
