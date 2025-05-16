<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'jointheteam@aglet.co.za'],
            [
                'name' => 'Join The Team',
                'email' => 'jointheteam@aglet.co.za',
                'password' => Hash::make('@TeamAglet')
            ]
        );
    }
}
