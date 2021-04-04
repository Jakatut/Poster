<?php

use App\Models\Comment;
use App\Traits\ImageStorageTrait;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Post;
use App\Models\User;

class CommentTest extends TestCase {
    const POST_ENDPOINT = '/api/post/';
    const COMMENT_ENDPOINT = '/api/comment/';
    const COMMENT_CHANGE_ENDPOINT = '/api/post/comment/';
 

    protected $post;

    use DatabaseMigrations;
    use DatabaseTransactions;
    use ImageStorageTrait;

    public function setUp(): void {
        parent::setUp();
        $this->post = Post::factory()->create();
        $this->comments = Comment::factory()->count(10)->create(['post_id' => $this->post->id, 'user_id' => $this->loggedInUser->id]);
    }

    public function testCreateComment_Success() {
        $commentData = [
            'body' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Suscipit iure quasi recusandae vitae cupiditate odio reiciendis eos id, tenetur facilis enim quod, perspiciatis distinctio repellendus aperiam optio, illo in delectus.'
        ];

        $this->postMultipartFormData($this->getPostCommentEndpoint(), $commentData);
        $this->assertResponseOk();
    }

    public function testCreateComment_Fail_EmptyBody() {
        $commentData = [
            'post_id' => $this->post->id,
            'body' => '',
        ];
        $this->postMultipartFormData($this->getPostCommentEndpoint(), $commentData, []);
        $this->assertResponseStatus(422);
    }

    public function testUpdateComment_Success() {
        $comment = Comment::factory()->create($this->getCommentData());
        $this->putMultipartFormData(self::COMMENT_CHANGE_ENDPOINT . $comment->id, ['body' => 'Hello! This is the new body!']);
        $this->assertResponseStatus(204);
    }

    public function testUpdateComment_Fail_NotFound() {
        $commentId = 12312312312;
        $this->putMultipartFormData($this->getPostCommentEndpoint() . $commentId, []);
        $this->assertResponseStatus(404);
    }

    public function testDeleteComment_Success() {
        $comment = Comment::factory()->create($this->getCommentData());
        $this->delete(self::COMMENT_CHANGE_ENDPOINT . $comment->id);
        $this->assertResponseStatus(204);
    }

    public function testGetAllCommentsOnPost_Success() {
        $this->get($this->getPostCommentEndpoint());
        $this->assertResponseOk();
    }

    public function testGetAllCommentsOnPost_Fail_NotFound() {
        $postId = 1231312321;
        $this->get($this->getPostCommentEndpoint($postId));
        $this->assertResponseStatus(404);
    }

    private function getPostCommentEndpoint($postId = null) {
        return self::POST_ENDPOINT . ($postId ?? $this->post->id) . '/comment/';
    }

    private function getCommentData() {
        return ['post_id' => $this->post->id, 'user_id' => $this->loggedInUser->id];
    }
}
