<?php

use App\Traits\ImageStorageTrait;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;

class LikeTest extends TestCase {
    const LIKE_ENDPOINT = '/api/like/';
    const LIKE_DELETE_ENDPOINT = '/api/post/like/';
    const POST_ENDPOINT = '/api/post/';

    use DatabaseMigrations;
    use DatabaseTransactions;
    use ImageStorageTrait;

    public function setUp(): void {
        parent::setUp();
        $this->post = Post::factory()->create(['body' => 'like test post body.']);
        User::factory()->times(10)->create();
        Like::factory()->times(10)->create(['post_id' => $this->post->id, 'user_id' => random_int(1, 10)]);
    }

    public function testCreateLike_Success() {
        $this->post($this->getCommentLikeEndpoint());
        $this->assertResponseOk();
    }

    public function testCreateLike_Fail_Post_Not_Exist() {
        $this->post($this->getCommentLikeEndpoint(100000));
        $this->assertResponseStatus(404);
    }

    public function testDeleteLike_Success() {
        $like = Like::factory()->create(['post_id' => $this->post->id]);
        $this->delete(self::LIKE_DELETE_ENDPOINT . $like->id);
        $this->assertResponseStatus(204);
    }

    public function testGetAllLikesOnPost_Success() {
        $this->get($this->getCommentLikeEndpoint());
        $this->assertResponseOk();
        $this->assertCount(10, json_decode($this->response->content()));
    }

    public function testGetAllLikesOnPost_Fail_NotFound() {
        $this->get($this->getCommentLikeEndpoint(13123123));
        $this->assertResponseStatus(404);
    }

    private function getCommentLikeEndpoint($postId = null) {
        return self::POST_ENDPOINT . ($postId ?? $this->post->id) . '/like/';
    }
}