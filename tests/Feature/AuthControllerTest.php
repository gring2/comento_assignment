<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterLogin()
    {
        $resp = $this->post(route('api.register'), ['name'=> 'test', 'email'=> 'email@test.com',
                            'password'=> 'password', 'password_confirmation'=> 'password']);
        $resp->assertSuccessful();
        $resp->assertJsonStructure(['token']);

        $emailLoginResp =  $this->post(route('api.login'), ['email'=> 'email@test.com',
            'password'=> 'password']);
        $emailLoginResp->assertSuccessful();
        $emailLoginResp->assertJsonStructure(['token']);

        $nameLoginResp =  $this->post(route('api.login'), ['name'=> 'test',
            'password'=> 'password']);

        $nameLoginResp->assertSuccessful();
        $nameLoginResp->assertJsonStructure(['token']);
    }
}
