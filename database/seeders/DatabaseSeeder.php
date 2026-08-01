<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default User Bendahara
        User::updateOrCreate(
            ['email' => 'bendahara'],
            [
                'name' => 'Bendahara Madrasah',
                'password' => bcrypt('password123#'),
            ]
        );

        $this->call([
            SettingSeeder::class,
        ]);
    }
}
