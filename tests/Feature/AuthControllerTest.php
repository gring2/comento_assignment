<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterLogin()
    {
        $resp = $this->postJson(route('api.register'), ['name'=> 'test', 'email'=> 'email@test.com',
                            'password'=> 'password', 'password_confirmation'=> 'password']);
        $resp->assertSuccessful();
        $resp->assertJsonStructure(['token']);

        $emailLoginResp =  $this->postJson(route('api.login'), ['email'=> 'email@test.com',
            'password'=> 'password']);
        $emailLoginResp->assertSuccessful();
        $emailLoginResp->assertJsonStructure(['token']);

        $nameLoginResp =  $this->postJson(route('api.login'), ['name'=> 'test',
            'password'=> 'password']);

        $nameLoginResp->assertSuccessful();
        $nameLoginResp->assertJsonStructure(['token']);
    }

    public function testAuth()
    {
        $fail = $this->getJson(route('api.test'));
        $fail->assertUnauthorized();

        $singUp = $this->postJson(route('api.register'), ['name'=> 'test', 'email'=> 'email@test.com',
            'password'=> 'password', 'password_confirmation'=> 'password']);

        $token = $singUp->decodeResponseJson()['token'];

        $success = $this->withHeader('Authorization', 'Bearer ' . $token)->getJson(route('api.test'));

        $success->assertSuccessful();
    }
}
