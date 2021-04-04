<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ImageStorageTrait;
use App\Traits\UriTrait;
use Google\Cloud\Storage\Connection\Rest;

class CommentController extends Controller
{

    use ImageStorageTrait;
    use UriTrait;

    const TABLE = 'comment';

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
     * Create a new comment.
     *
     * @param  Request  $request
     * @param  Int      $id
     * @return Response
     */
    public function create(Request $request, $id)
    {
        $this->ValidateComment($request, true);
        $user = $request->user();
        $post = Post::find($id);
        if (empty($post)) {
            return new Response('Post not found', 404);
        }
        $comment = new Comment(array_merge($request->all(), ['post_id' => $id, 'user_id' => $user->id]));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $comment->image = $this->saveImageToStorage($image);
        }

        $comment->save();
        $uri = $this->getUri($request, $comment->id);
        return (new Response(''))->header('Resource-Location', $uri);
    }

    /**
     * Get a list of all comments.
     *
     * @param  Int      $id
     * @return Response|JsonResponse
     */
    public function find($id)
    {
        $comments = Comment::all()->where('post_id', '=', $id);
        if (count($comments) == 0) {
            return new Response('Comments not found.', 404);
        }
        return new JsonResponse($comments, 200);
    }

    /**
     * Update a comment by id.
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->ValidateComment($request);
        $comment = Comment::find($id);

        if (empty($comment)) {
            return new Response('Comment not found.', 404);
        }

        // If an image was provided, save it to cloud storage and delete the previous image.        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageLocation = $this->saveImageToStorage($image, true, $comment->image);
        }

        // Update the array with the request fillables and the new image location if there is one.
        $comment->update(array_merge($request->all(), ['image' => $imageLocation ?? $comment->image]));
        return new Response('', 204);
    }

    /**
     * Delete a comment by id.
     *
     * @param  int   $id
     * @return Response
     */
    public function delete($id)
    {

        $comment = Comment::find($id);
        if (!$comment) {
            return new Response('Comment not found.', 404);
        }

        $comment->delete();
        return new Response('', 204);
    }

        /**
     * Validate the fields in a comment.
     * 
     * @param Request $request
     */
    protected function ValidateComment($request, $textFieldsRequired = false)
    {
        $required = $textFieldsRequired ? 'required|' : '';
        $this->validate($request, [
            'body' => $required . 'min:1|max:16777215',        // mediumText
            'image' => 'nullable|image',
        ]);
    }

}
