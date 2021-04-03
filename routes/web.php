<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return 'running';
});


$router->group(['prefix' => 'api'], function () use ($router) {

    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');

    $router->group(['prefix' => 'post'], function () use ($router) {

        // Create a post.
        $router->post('/', 'PostController@create');

        // Get a list of all posts
        $router->get('/', 'PostController@find');

        // Get a post by id.
        $router->get('{id}', 'PostController@get');

        // Update a post by id.
        $router->put('{id}', 'PostController@update');

        // Delete a post by id.
        $router->delete('{id}', 'PostController@delete');

        // Create a comment on a post.
        $router->post('/{id}/comment', 'CommentController@create');

        // Get all comments on a post.
        $router->get('/{id}/comments', 'CommentController@find');

        // Create a like on a post.
        $router->post('/{id}/like', 'LikeController@create');

        // Get all likes on a post.
        $router->get('/{id}/likes', 'LikeController@find');

    });

    $router->group(['prefix' => 'like'], function () use ($router) {
        
        // Delete a like.
        $router->delete('/{id}', 'LikeController@delete');
    });

    $router->group(['prefix' => 'comment'], function () use ($router) {

        // Update a comment.
        $router->put('/{id}', 'CommentController@update');

        // Delete a comment.
        $router->delete('/{id}', 'CommentController@delete');
    });
});
