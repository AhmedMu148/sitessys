<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\TplLang;
use App\Models\TplColorPalette;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create languages
        $english = TplLang::create([
            'name' => 'English',
            'code' => 'en',
            'dir' => 'ltr',
            'status' => true
        ]);
        
        $arabic = TplLang::create([
            'name' => 'العربية',
            'code' => 'ar',
            'dir' => 'rtl',
            'status' => true
        ]);

        // Create site
        $site = Site::create([
            'name' => 'TechCorp',
            'domain' => 'localhost',
            'default_lang_id' => $english->id,
            'description' => 'Empowering businesses with innovative technology solutions that drive growth and success.',
            'status' => true
        ]);
        
        // Create site configurations
        $configs = [
            [
                'key' => 'logo',
                'value' => 'img/logo.svg'
            ],
            [
                'key' => 'contact_info',
                'value' => json_encode([
                    'email' => 'contact@techcorp.com',
                    'phone' => '+1 (555) 123-4567',
                    'address' => '123 Tech Street, Silicon Valley, CA 94025'
                ])
            ],
            [
                'key' => 'social_links',
                'value' => json_encode([
                    'facebook' => 'https://facebook.com/techcorp',
                    'twitter' => 'https://twitter.com/techcorp',
                    'linkedin' => 'https://linkedin.com/company/techcorp',
                    'instagram' => 'https://instagram.com/techcorp'
                ])
            ],
            [
                'key' => 'language_config',
                'value' => json_encode([
                    'direction' => 'ltr',
                    'date_format' => 'Y-m-d',
                    'time_format' => 'H:i',
                    'timezone' => 'America/Los_Angeles'
                ]),
                'lang_id' => $english->id
            ]
        ];
        
        foreach ($configs as $config) {
            SiteConfig::create(array_merge($config, ['site_id' => $site->id]));
        }

        // Create color palette records
        $colors = [
            ['name' => 'Primary', 'color_code' => '#4361ee', 'is_primary' => true],
            ['name' => 'Secondary', 'color_code' => '#3f4e66', 'is_primary' => false],
            ['name' => 'Success', 'color_code' => '#2ec971', 'is_primary' => false],
            ['name' => 'Danger', 'color_code' => '#ef476f', 'is_primary' => false],
            ['name' => 'Info', 'color_code' => '#4cc9f0', 'is_primary' => false],
            ['name' => 'Warning', 'color_code' => '#ffd166', 'is_primary' => false],
            ['name' => 'Light', 'color_code' => '#f8f9fa', 'is_primary' => false],
            ['name' => 'Dark', 'color_code' => '#212529', 'is_primary' => false],
            ['name' => 'Accent', 'color_code' => '#7209b7', 'is_primary' => false],
        ];

        foreach ($colors as $color) {
            TplColorPalette::create([
                'site_id' => $site->id,
                'name' => $color['name'],
                'color_code' => $color['color_code'],
                'is_primary' => $color['is_primary'],
                'status' => true
            ]);
        }

        // Create site configurations
        $configs = [
            [
                'key' => 'logo',
                'value' => 'img/logo.svg'
            ],
            [
                'key' => 'favicon',
                'value' => 'img/favicon.ico'
            ],
            [
                'key' => 'meta_description',
                'value' => 'TechCorp - Innovating the future through technology solutions. We help businesses transform and grow in the digital age.'
            ],
            [
                'key' => 'social_links',
                'value' => json_encode([
                    'twitter' => 'https://twitter.com/techcorp',
                    'facebook' => 'https://facebook.com/techcorp',
                    'linkedin' => 'https://linkedin.com/company/techcorp',
                    'github' => 'https://github.com/techcorp'
                ])
            ],
            [
                'key' => 'contact_info',
                'value' => json_encode([
                    'email' => 'info@techcorp.com',
                    'phone' => '+1 (555) 123-4567',
                    'address' => '123 Innovation Drive, Tech City, TC 12345'
                ])
            ]
        ];

        foreach ($configs as $config) {
            SiteConfig::create([
                'site_id' => $site->id,
                'key' => $config['key'],
                'value' => $config['value']
            ]);
        }

        // Create language-specific configurations
        foreach ([$english, $arabic] as $lang) {
            SiteConfig::create([
                'site_id' => $site->id,
                'key' => 'language_config',
                'value' => json_encode([
                    'direction' => $lang->dir,
                    'is_default' => ($lang->id === $english->id)
                ]),
                'lang_id' => $lang->id
            ]);
        }
    }
}
