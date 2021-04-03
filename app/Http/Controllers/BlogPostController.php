<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    const TABLE = 'blog_post';
    const SEARCH_NEEDLE = "/images/";


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
     * @return Response
     */
    public function create(Request $request)
    {
        $this->ValidateBlogPost($request, true);
        $blog_post = new BlogPost($request->all());

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $blog_post->image = $this->saveImageToStorage($image);
        }

        $blog_post->save();
        $uri = $this->getUri($request, $blog_post->id);
        return (new Response(''))->header('Resource-Location', $uri);
    }

    /**
     * Get a list of all blog posts.
     *
     * @return Response|JsonResponse
     */
    public function find()
    {
        $blog_posts = BlogPost::all();
        if (empty($blog_posts)) {
            return new Response('', 404);
        }
        return new JsonResponse($blog_posts, 200);
    }

    /**
     * Get a blog post by id.
     *
     * @param  string   $id
     * @return Response|JsonResponse
     */
    public function get(string $id)
    {
        $blog_post = BlogPost::find($id);
        if (empty($blog_post)) {
            return new Response('', 404);
        }

        return new JsonResponse($blog_post, 200);
    }

    /**
     * Update a blog post by id.
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->ValidateBlogPost($request);
        $blog_post = BlogPost::find($id);

        if (empty($blog_post)) {
            return new Response('', 404);
        }

        // If an image was provided, save it to cloud storage and delete the previous image.        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageLocation = $this->saveImageToStorage($image, true, $blog_post->image);
        }

        // Update the array with the request fillables and the new image location if there is one.
        $blog_post->update(array_merge($request->all(), ['image' => $imageLocation ?? $blog_post->image]));
        return new Response('', 204);
    }

    /**
     * Delete a blog post by id.
     *
     * @param  int   $id
     * @return Response
     */
    public function delete($id)
    {

        $blog_post = BlogPost::find($id);
        if (!$blog_post) {
            return new Response('', 404);
        }

        $blog_post->delete();
        return new Response('', 204);
    }

    /**
     * Validate the fields in a blog post.
     * 
     * @param Request $request
     */
    protected function ValidateBlogPost($request, $textFieldsRequired = false)
    {
        $required = $textFieldsRequired ? 'required|' : '';
        $this->validate($request, [
            'title' => $required . 'min:1|max:255',            // varchar
            'summary' => $required . 'min:1|max:255',          // varchar
            'body' => $required . 'min:1|max:16777215',        // mediumText
            'image' => 'nullable|image',
        ]);
    }

    /**
     * Get the uri of the current request with the id of the resource.
     * 
     * @param Request   $request
     * @param int       $id
     * @return String
     */
    protected function getUri($request, $id)
    {
        return $request->path() . '/' . $id;
    }


    /**
     * Saves an image to cloud storage with the option to remove the old image.
     * 
     * @param UploadedFile   $image
     * @param Boolean        $removeOld
     * @param String         $oldImage
     * @return String
     */
    protected function saveImageToStorage($image, $removeOld = false, $oldImage = '') {
        $url = '';
        if ($image->isValid()) {
            $disk = Storage::disk('gcs');
            if ($removeOld && !empty($oldImage)) {
                
                $disk->delete($this->getStorageImageName($oldImage));
            }
            $imageName = $disk->put('', $image, 'public');
            $url = $disk->url($imageName);
        }

        return $url;
    }

    /**
     * Gets the file name of the image with the provided name in cloud storage.
     * 
     * @param String $imageName
     * @return String
     */
    protected function getStorageImageName($imageName) {
        if (str_contains($imageName, self::SEARCH_NEEDLE)) {
            return substr($imageName, strpos($imageName, self::SEARCH_NEEDLE) + strlen(self::SEARCH_NEEDLE));
        }

        return $imageName;
    }
}
