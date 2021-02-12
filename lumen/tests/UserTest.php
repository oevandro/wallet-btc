<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    private static function make_user()
    {
        $randPassword = (string) rand(11111111,99999999);

        return [
            'name' => 'Nome Teste',
            'email' => 'email@teste.com',
            'password' => $randPassword,
            'password_confirmation' => $randPassword,
        ];
    }

    public function test_can_create_new_user()
    {
       // $user = factory(User::class, 1)->make()->toArray();
        $route_path = '/api/register';

        $user = self::make_user();
        $response = $this->call('POST', $route_path, $user);
        $this->assertEquals(201, $response->status());
    }

    public function test_cant_create_new_user_with_same_email()
    {

        $route_path = '/api/register';
        $user = self::make_user();

        $responseFirstUser = $this->call('POST', $route_path, $user);
        $this->assertEquals(201, $responseFirstUser->status());

        $responseSecondUser = $this->call('POST', $route_path, $user);
        $this->assertEquals(422, $responseSecondUser->status());
    }

    public function test_can_login_user_and_get_logged_user()
    {

        $route_path = '/api/register';
        $route_path_login = '/api/login';
        $route_path_profile = '/api/profile';

        $user = self::make_user();

        $response = $this->call('POST', $route_path, $user);
        $this->assertEquals(201, $response->status());

        $userLoginData = [
            'email' => $user['email'],
            'password' => $user['password']
        ];

        $response = $this->call('POST', $route_path_login, $userLoginData);
        $this->assertEquals(200, $response->status());

        $response = $this->call('GET', $route_path_profile);
        $this->assertEquals(200, $response->status());
    }

    public function test_cant_access_autorized_route()
    {
        $route_path_profile = '/api/profile';

        $response = $this->call('GET', $route_path_profile);
        $this->assertEquals(401, $response->status());
    }
}
