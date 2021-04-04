<?php

use Database\Factories\UserFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;


class PostTest extends TestCase
{
    const POST_ENDPOINT = '/api/post/';

    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreatePost_Success()
    {
        $this->getToken();
    }


}
