<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wallet;
use App\Models\User;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang belum memiliki wallet
        $users = User::whereDoesntHave('wallet')->get();
        
        foreach ($users as $user) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => rand(100000, 2000000), // Saldo acak antara 100rb - 2jt
                'is_active' => true
            ]);
        }
    }
}
