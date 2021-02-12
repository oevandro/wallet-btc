<?php

use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\TransactionBTC;
use App\Models\User;
use App\Models\Wallet;

class TransactionBTCTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_buy_btc()
    {
        $amountBTC = 1;
        $amount = 9999999.99;
        $user = User::factory()->create();
        $this->actingAs($user)->get('/api/profile');
        Wallet::factory()->create(['user_id' => $user->id, 'amount' => $amount]);
        $response = $this->call('POST', '/api/btc/buy/', ['amount_btc' => $amountBTC]);
        $this->assertEquals(200, $response->status());
    }

    public function test_can_sell_btc()
    {
        $amountBTC = 1;
        $amount = 0;
        $user = User::factory()->create();
        $this->actingAs($user)->get('/api/profile');
        Wallet::factory()->create(['user_id' => $user->id, 'amount' => $amount]);

        TransactionBTC::factory()
            ->create([
                'user_id' => $user->id,
                'amount' => $amountBTC,
                'type' => TransactionBTC::TYPE_DEPOSIT
            ]);

        $response = $this->call('POST', '/api/btc/sell/', ['amount_btc' => $amountBTC]);
        $this->assertEquals(200, $response->status());
    }



    public function test_can_get_btc_balance()
    {
        $amountBTC = 1;
        $user = User::factory()->create();
        $this->actingAs($user)->get('/api/profile');
        TransactionBTC::factory()
            ->create([
                'user_id' => $user->id,
                'amount' => $amountBTC,
                'type' => TransactionBTC::TYPE_DEPOSIT
            ]);
        $response = $this->call('GET', 'api/btc/balance/');
        $this->assertEquals(200, $response->status());
    }


    public function test_can_get_btc_positions()
    {
        $amountBTC = 1;
        $user = User::factory()->create();
        $this->actingAs($user)->get('/api/profile');
        TransactionBTC::factory()
            ->create([
                'user_id' => $user->id,
                'amount' => $amountBTC,
                'type' => TransactionBTC::TYPE_DEPOSIT
            ]);
        $response = $this->call('GET', 'api/btc/positions/');
        $this->assertEquals(200, $response->status());
    }


    public function test_can_get_btc_history()
    {
        $amountBTC = 1;
        $user = User::factory()->create();
        $this->actingAs($user)->get('/api/profile');
        TransactionBTC::factory()
            ->create([
                'user_id' => $user->id,
                'amount' => $amountBTC,
                'type' => TransactionBTC::TYPE_DEPOSIT
            ]);
        $response = $this->call('GET', 'api/btc/history/');
        $this->assertEquals(200, $response->status());
    }



}
