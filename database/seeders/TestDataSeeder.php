<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use App\Models\SiteConfig;
use App\Models\TplSection;
use App\Models\TplPage;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );

        // Create a test site
        $site = Site::create([
            'user_id' => $user->id,
            'site_name' => 'Test Site',
            'domain' => 'localhost:8000',
            'status' => true
        ]);

        // Create test sections
        $section1 = TplSection::create([
            'site_id' => $site->id,
            'name' => 'Hero Section',
            'content' => json_encode([
                'en' => [
                    'title' => 'Welcome to Our Site',
                    'content' => 'This is the hero section content in English.',
                    'button_text' => 'Learn More'
                ],
                'ar' => [
                    'title' => 'مرحباً بكم في موقعنا',
                    'content' => 'هذا هو محتوى القسم الرئيسي باللغة العربية.',
                    'button_text' => 'اعرف المزيد'
                ]
            ]),
            'position' => 1,
            'lang_id' => '1,2'
        ]);

        $section2 = TplSection::create([
            'site_id' => $site->id,
            'name' => 'About Section',
            'content' => json_encode([
                'en' => [
                    'title' => 'About Us',
                    'content' => 'Learn more about our company and mission.',
                    'button_text' => 'Read More'
                ],
                'ar' => [
                    'title' => 'من نحن',
                    'content' => 'تعرف أكثر على شركتنا ومهمتنا.',
                    'button_text' => 'اقرأ المزيد'
                ]
            ]),
            'position' => 2,
            'lang_id' => '1,2'
        ]);

        $section3 = TplSection::create([
            'site_id' => $site->id,
            'name' => 'Contact Section',
            'content' => json_encode([
                'en' => [
                    'title' => 'Contact Us',
                    'content' => 'Get in touch with us for more information.',
                    'button_text' => 'Contact'
                ],
                'ar' => [
                    'title' => 'اتصل بنا',
                    'content' => 'تواصل معنا للحصول على مزيد من المعلومات.',
                    'button_text' => 'اتصل'
                ]
            ]),
            'position' => 3,
            'lang_id' => '1,2'
        ]);

        // Create test pages
        $homePage = TplPage::create([
            'site_id' => $site->id,
            'name' => 'Home',
            'link' => '/',
            'section_id' => '1,2'
        ]);

        $aboutPage = TplPage::create([
            'site_id' => $site->id,
            'name' => 'About',
            'link' => '/about',
            'section_id' => '2'
        ]);

        $contactPage = TplPage::create([
            'site_id' => $site->id,
            'name' => 'Contact',
            'link' => '/contact',
            'section_id' => '3'
        ]);

        // Create site config
        $config = SiteConfig::create([
            'site_id' => $site->id,
            'data' => [
                'title' => 'Test Site',
                'description' => 'A test site for our template system',
                'keyword' => 'test, template, laravel',
                'logo' => '/logo.png',
                'favicon' => '/favicon.ico'
            ],
            'lang_id' => '1,2'
        ]);

        echo "Test data created successfully!\n";
        echo "Users: " . User::count() . "\n";
        echo "Sites: " . Site::count() . "\n";
        echo "Pages: " . TplPage::count() . "\n";
        echo "Sections: " . TplSection::count() . "\n";
    }
}
