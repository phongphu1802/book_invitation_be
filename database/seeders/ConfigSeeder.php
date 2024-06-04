<?php

namespace Database\Seeders;

use App\Enums\ConfigEnum;
use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $configs = [
            [
                'key' => 'url_facebook',
                'value' => 'https://www.facebook.com/TopCVServices',
                'datatype' => ConfigEnum::TEXT,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'url facebook',
            ],
            [
                'key' => 'phone_number',
                'value' => '0123456789',
                'datatype' => ConfigEnum::NUMBER,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'phone number',
            ],
            [
                'key' => 'email',
                'value' => 'bookinvitation@gmail.com',
                'datatype' => ConfigEnum::TEXT,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'email',
            ],
            [
                'key' => 'company_name',
                'value' => 'Dev FreeLancer',
                'datatype' => ConfigEnum::TEXT,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'company name',
            ],
            [
                'key' => 'status_order',
                'value' => "true",
                'datatype' => ConfigEnum::BOOLEAN,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'status order',
            ],
            [
                'key' => 'logo',
                'value' => "https://apis.book-invitation.encacap.com/images/systems/1714804981.png",
                'datatype' => ConfigEnum::IMAGE,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'status order',
            ],
            [
                'key' => 'image_header_home',
                'value' => "[\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714651707.jpg\",\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714651707.jpg\",\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714651707.jpg\",\"https:\\/\\/apis.book-invitation.encacap.com\\/images\\/systems\\/1714651707.jpg\"]",
                'datatype' => ConfigEnum::IMAGES,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'status order',
            ],

            [
                'key' => 'url_address',
                'value' => "https://maps.app.goo.gl/hzvz1ERfsxCejXSs7",
                'datatype' => ConfigEnum::TEXT,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'status order',
            ],
            [
                'key' => 'app_name',
                'value' => "Demariage",
                'datatype' => ConfigEnum::TEXT,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'status order',
            ],
            [
                'key' => 'pagenation_page_size',
                'value' => "10",
                'datatype' => ConfigEnum::NUMBER,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'status order',
            ],
            [
                'key' => 'app_description',
                'value' => "Website for booking invitation card",
                'datatype' => ConfigEnum::TEXT,
                'type' => ConfigEnum::PUBLIC ,
                'description' => 'status order',
            ]
        ];

        foreach ($configs as $config) {
            Config::updateOrCreate(['key' => $config['key'], 'type' => $config['type'], 'datatype' => $config['datatype']], $config);
        }
    }
}