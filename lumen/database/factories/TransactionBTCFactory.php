<?php

namespace Database\Factories;

use App\Models\TransactionBTC;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionBTCFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionBTC::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'amount' => rand(1111,9999),
            'amount_btc' => 4,
            'amount_btc_current' => rand(1111,9999),
            'type' => TransactionBTC::TYPE_DEPOSIT
        ];
    }
}
