<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use HasFactory;
    use DatabaseMigrations;

    const FORM_DATA = 'multipart/form-data;';
    const REGISTER_ENDPOINT = '/api/register';
    const LOGIN_ENDPOINT = '/api/login';

    protected static $migrationsRun = false;

    protected $loggedInUser;

    protected $user;

    protected $headers;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function setUp(): void
    {
        parent::setup();

        if (!static::$migrationsRun) {
            // Run migrations and seed the database on the first test setup only.
            Artisan::call('migrate:refresh');
            Artisan::call('db:seed');
            static::$migrationsRun = true;
        }

        // Setup user data.
        $users = User::factory(\App\Models\User::class)->times(2)->create();
        $this->loggedInUser = $users[0];
        $this->user = $users[1];
        $this->headers = [
            'Authorization' => "Token {$this->loggedInUser->token}"
        ];
        $this->actingAs($this->loggedInUser);
    }

    protected function postMultipartFormData($uri, $data = [], $files = [], $headers = [])
    {
        $server = $this->transformHeadersToServerVars($headers);
        $this->call('POST', $uri, $data, [], $files, $server, self::FORM_DATA);
        return $this;
    }

    protected function putMultipartFormData($uri, $data = [], $files = [], $headers = [])
    {
        if (str_contains($uri, '?')) {
            $uri = $uri . '&_method=PUT';
        } else {
            $uri = $uri . '?_method=PUT';
        }
        
        $this->call('PUT', $uri, $data, [], $files, $headers, self::FORM_DATA);
        return $this;
    }

    protected function authHeaders($user = null)
    {
        $headers = [];

        if (!is_null($user)) {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
            $headers['authorization'] = 'Bearer ' . $token;
        }

        return $headers;
    }
}
