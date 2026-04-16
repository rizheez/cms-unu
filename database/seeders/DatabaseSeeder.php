<?php

namespace Database\Seeders;

use App\Models\Setting;
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
        User::factory()->create([
            'name' => 'Administrator UNU',
            'email' => 'admin@unu.test',
        ]);

        collect([
            ['key' => 'site_name', 'value' => 'Universitas Nahdlatul Ulama', 'group' => 'general'],
            ['key' => 'site_address', 'value' => 'Jl. Pendidikan No. 1, Indonesia', 'group' => 'contact'],
            ['key' => 'site_phone', 'value' => '+62 812 0000 0000', 'group' => 'contact'],
            ['key' => 'site_email', 'value' => 'info@unu.ac.id', 'group' => 'contact'],
            ['key' => 'site_logo', 'value' => null, 'group' => 'appearance'],
            ['key' => 'site_favicon', 'value' => null, 'group' => 'appearance'],
            ['key' => 'social_facebook', 'value' => null, 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => null, 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => null, 'group' => 'social'],
            ['key' => 'vision', 'value' => 'Menjadi universitas unggul yang berkarakter Ahlussunnah wal Jamaah.', 'group' => 'profile'],
            ['key' => 'mission', 'value' => 'Menyelenggarakan pendidikan, penelitian, dan pengabdian masyarakat yang berdampak.', 'group' => 'profile'],
            ['key' => 'accreditation', 'value' => 'Baik Sekali', 'group' => 'profile'],
            ['key' => 'meta_title', 'value' => 'Universitas Nahdlatul Ulama', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'Website resmi Universitas Nahdlatul Ulama.', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => 'UNU, Universitas Nahdlatul Ulama, Kampus NU', 'group' => 'seo'],
        ])->each(fn (array $setting): Setting => Setting::query()->updateOrCreate(
            ['key' => $setting['key']],
            ['value' => $setting['value'], 'group' => $setting['group']],
        ));
    }
}
