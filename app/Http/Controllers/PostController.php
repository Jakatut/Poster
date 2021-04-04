<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ImageStorageTrait;
use App\Traits\UriTrait;


class PostController extends Controller
{
    use ImageStorageTrait;
    use UriTrait;

    const TABLE = 'post';

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
     * Create a new post.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        $this->ValidatePost($request, true);
        $post = new Post($request->all());

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $post->image = $this->saveImageToStorage($image);
        }

        $post->save();
        $uri = $this->getUri($request, $post->id);
        return (new Response(''))->header('Resource-Location', $uri);
    }

    /**
     * Get a list of all posts.
     *
     * @return Response|JsonResponse
     */
    public function find()
    {
        $posts = Post::all();
        if (count($posts) == 0) {
            return new Response('Posts not found.', 404);
        }
        return new JsonResponse($posts, 200);
    }

    /**
     * Get a post by id.
     *
     * @param  string   $id
     * @return Response|JsonResponse
     */
    public function get(string $id)
    {
        $post = Post::find($id);
        if (empty($post)) {
            return new Response('Post not found.', 404);
        }

        return new JsonResponse($post, 200);
    }

    /**
     * Update a post by id.
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->ValidatePost($request);
        $post = Post::find($id);

        if (empty($post)) {
            return new Response('Post not found.', 404);
        }

        // If an image was provided, save it to cloud storage and delete the previous image.        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageLocation = $this->saveImageToStorage($image, true, $post->image);
        }

        // Update the array with the request fillables and the new image location if there is one.
        $post->update(array_merge($request->all(), ['image' => $imageLocation ?? $post->image]));
        return new Response('', 204);
    }

    /**
     * Delete a post by id.
     *
     * @param  int   $id
     * @return Response
     */
    public function delete($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return new Response('Post not found.', 404);
        }

        $post->delete();
        return new Response('', 204);
    }

    /**
     * Validate the fields in a post.
     * 
     * @param Request $request
     */
    protected function ValidatePost($request, $textFieldsRequired = false)
    {
        $required = $textFieldsRequired ? 'required|' : '';
        $this->validate($request, [
            'body' => $required . 'min:1|max:16777215',        // mediumText
            'image' => 'nullable|image',
        ]);
    }
}
