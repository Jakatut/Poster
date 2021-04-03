<?php

namespace App\Http\Controllers;

use App\Models\Like;
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
     * Create a new blog post.
     *
     * @param  Request  $request
     * @param  Int      $id
     * @return Response
     */
    public function create(Request $request, $id)
    {
        $user = $request->user();
        $like = new Like(['user_id' => $user->id, 'post_id' => $id]);
        $like->save();
        $uri = $this->getUri($request, $like->id);
        return (new Response(''))->header('Resource-Location', $uri);
    }

    /**
     * Get a list of all likes.
     *
     * @param Int   $id
     * @return Response|JsonResponse
     */
    public function find($id)
    {
        $likes = Like::all()->where('post_id', '=', $id);
        if (count($likes) == 0) {
            return new Response('', 404);
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
            return new Response('', 404);
        }

        $like->delete();
        return new Response('', 204);
    }
}
