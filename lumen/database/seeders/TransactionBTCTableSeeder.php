<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TransactionBTC;

use Illuminate\Database\Seeder;

class TransactionBTCTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = User::all();

        foreach ($result as $data) {
            TransactionBTC::factory(TransactionBTC::class)->create([
                'user_id' => $data->id,
            ]);
        }
    }
}
