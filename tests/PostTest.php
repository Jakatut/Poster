<?php

use App\Models\Comment;
use App\Models\Like;
use App\Traits\ImageStorageTrait;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Post;
use App\Models\User;

class PostTest extends TestCase {
    const POST_ENDPOINT = '/api/post/';

    use DatabaseMigrations;
    use DatabaseTransactions;
    use ImageStorageTrait;

    private $testPost;

    public function setUp(): void {
        parent::setUp();
        $this->testPost = Post::factory()->create(['body' => 'like test post body.']);
    }

    public function testCreatePost_Success()
    {
        $postData = [
            'body' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Suscipit iure quasi recusandae vitae cupiditate odio reiciendis eos id, tenetur facilis enim quod, perspiciatis distinctio repellendus aperiam optio, illo in delectus.'
        ];

        $user = User::factory()->create(['name' => 'user1', 'email' => 'abc124@acb.com', 'password' => 'abc123']);
        $this->postMultipartFormData(self::POST_ENDPOINT, $postData, [], $this->authHeaders($user));
        $this->assertResponseOk();
    }

    public function testCreatePost_Fail_EmptyBody() {
        $postData = [
            'body' => '',
        ];
        $this->postMultipartFormData(self::POST_ENDPOINT, $postData, []);
        $this->assertResponseStatus(422);
    }

    public function testUpdatePost_Success() {
        $this->putMultipartFormData(self::POST_ENDPOINT . $this->testPost->id, ['body' => 'Hello! This is the new body!'], []);
        $this->assertResponseStatus(204);
    }

    public function testUpdatePost_Fail_EmptyBody() {
        $data = [];
        $this->putMultipartFormData(self::POST_ENDPOINT . $this->testPost->id, $data, []);
        $this->assertResponseStatus(204);
    }

    public function testDeletePost_Success() {
        $post = Post::factory()->create(['body' => 'hello!']); 
        $this->delete(self::POST_ENDPOINT . $post->id);
        $this->assertResponseStatus(204);

        $comments = Comment::find(['post_id' => $post->id]);
        $this->assertCount(0, $comments, "Expected comment count to be 0.");

        $likes = Like::find(['post_id' => $post->id]);
        $this->assertCount(0, $likes, "Expected like count to be 0.");
    }

    public function testDeletePost_Fail_NotFound() {
        $postId = 1000000;
        $this->delete(self::POST_ENDPOINT . $postId);
        $this->assertResponseStatus(404);
    }

    public function testGetAllPosts_Success() {
        $this->get(self::POST_ENDPOINT);
        $this->assertResponseOk();
        $this->assertNotCount(0, $this->response->getData(), "Expected the response data to contain 1 or more values.");
    }
    
    public function testGetPostById_Success() {
        $this->get(self::POST_ENDPOINT . $this->testPost->id);
        $this->assertResponseOk();
    }

    public function testGetPostById_Fail_NotFound() {
        $postId = 1090000;
        $this->get(self::POST_ENDPOINT . $postId);
        $this->assertResponseStatus(404);
    }
}
