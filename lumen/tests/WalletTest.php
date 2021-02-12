<?php

use App\Models\User;
use App\Models\Wallet;
use Laravel\Lumen\Testing\DatabaseTransactions;

class WalletTest extends TestCase
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

    public function test_can_get_balance()
    {

        $radom_amount = rand(1,9999);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/api/profile');

        $wallet = Wallet::factory()->make(['user_id' =>$user->id, 'amount' => $radom_amount]);

        Wallet::create($wallet->toArray());

        $response = $this->call('GET', '/api/wallet/balance');
        $response_amount = $response->getContent();

        $this->assertEquals($wallet->amount, $response_amount);
    }

}
