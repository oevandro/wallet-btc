<?php

use App\Models\Transaction;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Wallet;

class TransactionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_make_deposit()
    {
        $radom_amount = rand(1,9999);
        $user = User::factory()->create();
        $this->actingAs($user)->get('/api/profile');

        Wallet::factory()->create(['user_id' => $user->id]);

        $transaction = Transaction::factory()->make(['amount' => $radom_amount]);

        $response = $this->call('POST', '/api/wallet/deposit', $transaction->toArray());
        $this->assertEquals(200, $response->status());
    }

    public function test_can_make_withdraw()
    {
        $amount = 999;
        $user = User::factory()->create();
        $this->actingAs($user)->get('/api/profile');

        Wallet::factory()->create(['user_id' => $user->id, 'amount' => $amount]);

        $transaction = Transaction::factory()->make(['amount' => $amount]);

        $response = $this->call('POST', '/api/wallet/withdraw', $transaction->toArray());
        $this->assertEquals(200, $response->status());
    }

}
