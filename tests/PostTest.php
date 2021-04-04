<?php

use App\Traits\ImageStorageTrait;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Post;
use App\Models\User;

// class PostTest extends TestCase
// {
//     const POST_ENDPOINT = '/api/post/';

//     use DatabaseMigrations;
//     use DatabaseTransactions;
//     use ImageStorageTrait;

//     public function testCreatePost_Success()
//     {
//         $this->actingAs($this->loggedInUser);
//         $postData = [
//             'body' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Suscipit iure quasi recusandae vitae cupiditate odio reiciendis eos id, tenetur facilis enim quod, perspiciatis distinctio repellendus aperiam optio, illo in delectus.'
//         ];

//         $user = User::factory()->create(['name' => 'user1', 'email' => 'abc124@acb.com', 'password' => 'abc123']);
//         $this->postMultipartFormData(self::POST_ENDPOINT, $postData, [], $this->authHeaders($user));
//         $this->assertResponseOk();
//     }

//     public function testCreatePost_Fail_EmptyBody() {
//         $this->actingAs($this->loggedInUser);
//         $postData = [
//             'body' => '',
//         ];
//         $this->postMultipartFormData(self::POST_ENDPOINT, $postData, []);
//         $this->assertResponseStatus(422);
//     }

//     public function testUpdatePost_Success() {
//         $this->actingAs($this->loggedInUser);
//         $post = Post::factory()->create();
//         $this->putMultipartFormData(self::POST_ENDPOINT . $post->id, ['body' => 'Hello! This is the new body!'], []);
//         $this->assertResponseStatus(204);
//     }

//     public function testUpdatePost_Fail_EmptyBody() {
//         $this->actingAs($this->loggedInUser);
//         $post = Post::factory()->create(['body' => 'Hello!']);
//         $data = [];
//         $this->putMultipartFormData(self::POST_ENDPOINT . $post->id, $data, []);
//         $this->assertResponseStatus(204);
//     }
// }
