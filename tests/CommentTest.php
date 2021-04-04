<?php

use App\Models\Comment;
use App\Traits\ImageStorageTrait;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Post;
use App\Models\User;

// class CommentTest extends TestCase
// {
//     const POST_ENDPOINT = '/api/post/';
//     const COMMENT_ENDPOINT = '/api/comment/';

//     protected $post;

//     use DatabaseMigrations;
//     use DatabaseTransactions;
//     use ImageStorageTrait;

//     public function setUp(): void
//     {
//         parent::setUp();

//         $this->post = Post::factory()->create();
//     }

//     public function testCreateComment_Success()
//     {
//         $this->actingAs($this->loggedInUser);
//         $commentData = [
//             'body' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Suscipit iure quasi recusandae vitae cupiditate odio reiciendis eos id, tenetur facilis enim quod, perspiciatis distinctio repellendus aperiam optio, illo in delectus.'
//         ];

//         $this->postMultipartFormData($this->getPostCommentEndpoint(), $commentData);
//         $this->assertResponseOk();
//     }

//     public function testCreateComment_Fail_EmptyBody() {
//         $this->actingAs($this->loggedInUser);
//         $commentData = [
//             'post_id' => $this->post->id,
//             'body' => '',
//         ];
//         $this->postMultipartFormData($this->getPostCommentEndpoint(), $commentData, []);
//         $this->assertResponseStatus(422);
//     }

//     public function testUpdateComment_Success() {
//         $this->actingAs($this->loggedInUser);
//         $comment = Comment::factory()->create(['post_id' => $this->post->id, 'user_id' => $this->loggedInUser->id]);

//         $this->putMultipartFormData(self::COMMENT_ENDPOINT . $comment->id, ['body' => 'Hello! This is the new body!']);
//         $this->assertResponseStatus(204);
//     }

//     public function testUpdateComment_Fail_NotFound() {
//         $this->actingAs($this->loggedInUser);
//         $commentId = 12312312312;
//         $this->putMultipartFormData($this->getPostCommentEndpoint() . $commentId, []);
//         $this->assertResponseStatus(404);
//     }

//     private function getPostCommentEndpoint() {
//         return self::POST_ENDPOINT . $this->post->id . '/comment/';
//     }
// }
