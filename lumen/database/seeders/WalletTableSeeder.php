<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;

use Illuminate\Database\Seeder;

class WalletTableSeeder extends Seeder
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
            Wallet::factory(Wallet::class)->create([
                'user_id' => $data->id,
            ]);
        }
    }
}
