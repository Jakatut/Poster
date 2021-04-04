<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    const FORM_DATA = 'multipart/form-data;';
    const REGISTER_ENDPOINT = '/api/register';
    const LOGIN_ENDPOINT = '/api/login';


    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function getToken() {
        $registrationFormData = [
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $this->postMultipartFormData(self::REGISTER_ENDPOINT, $registrationFormData);
        $this->postMultipartFormData(self::LOGIN_ENDPOINT, $registrationFormData);
        $token = json_decode($this->response->getContent())->token;
        return $token;
    }

    public function postMultipartFormData($uri, $data, $token = '') {
        $this->call('POST', $uri, $data, [], [], [], self::FORM_DATA)->header('Authorization', 'bearer ' . $token);
    }
}
