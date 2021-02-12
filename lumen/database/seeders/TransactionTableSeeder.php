<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;

use Illuminate\Database\Seeder;

class TransactionTableSeeder extends Seeder
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
            Transaction::factory(Transaction::class)->create([
                'user_id' => $data->id,
            ]);
        }
    }
}
