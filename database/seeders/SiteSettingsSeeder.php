<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Farzin',
            'site_description' => 'فروشگاه آنلاین فرزین',
            'default_meta_title' => 'Farzin | فروشگاه آنلاین',
            'default_meta_description' => 'فروشگاه آنلاین فرزین',
            'phone' => '',
            'email' => '',
            'address' => '',
            'instagram' => '',
            'telegram' => '',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::setValue(
                $key,
                $value,
                'string'
            );
        }
    }
}
