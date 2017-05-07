<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);



        $configs = [
            ['key' => \App\Model\Config::AUTO_BUY_LTC_WHEN_NOTICE, 'value' => 0, 'name' => '莱特币讲价时候自动购买', 'type' => \App\Model\Config::TYPE_CHECKBOX],
            ['key' => \App\Model\Config::ENABLED_SMS, 'value' => 0, 'name' => '短信提示', 'type' => \App\Model\Config::TYPE_CHECKBOX],
            ['key' => \App\Model\Config::ENABLED_PRICE_WATCH, 'value' => 0, 'name' => '降价提示', 'type' => \App\Model\Config::TYPE_CHECKBOX],
        ];

        foreach ($configs as $row) {
            if (!\App\Model\Config::where('key', $row['key'])->count()) {
                \App\Model\Config::forceCreate($row);
            }
        }
    }
}
