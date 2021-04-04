<?php

use App\Traits\ImageStorageTrait;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Post;

class PostTest extends TestCase
{
    const POST_ENDPOINT = '/api/post/';

    use DatabaseMigrations;
    use DatabaseTransactions;
    use ImageStorageTrait;

    public function testCreatePost_Success()
    {
        $postData = [
            'body' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Suscipit iure quasi recusandae vitae cupiditate odio reiciendis eos id, tenetur facilis enim quod, perspiciatis distinctio repellendus aperiam optio, illo in delectus.'
        ];

        $this->postMultipartFormData(self::POST_ENDPOINT, $postData, [], $this->getToken());
        $this->assertResponseOk();
    }

    public function testCreatePost_Fail_Invalid_Body() {
        $postData = [
            'body' => '',
        ];
        $this->postMultipartFormData(self::POST_ENDPOINT, $postData, [], $this->getToken());
        $this->assertResponseStatus(422);
    }

    public function testUpdatePost_Success() {
        $post = Post::factory()->create();
        $this->putMultipartFormData(self::POST_ENDPOINT . $post->id . '?', ['body' => 'Hello! This is the new body!'], [], $this->getToken());
        $this->assertResponseStatus(204);
    }
}
