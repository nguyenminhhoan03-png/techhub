<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\User\Entities\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'TechHub Admin',
            'email' => 'admin@techhub.local',
        ]);

        $this->call([
            ToolSeeder::class,
        ]);
    }
}
