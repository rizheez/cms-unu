<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ComprehensiveDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DemoUsersSeeder::class,
            DemoSettingsSeeder::class,
            DemoMenusSeeder::class,
            DemoPagesSeeder::class,
            DemoPostsSeeder::class,
            DemoAcademicsSeeder::class,
            DemoContentSeeder::class,
        ]);
    }
}
