<?php

use Faker\Provider\Base;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    private $catalogSchematic = [
        'iPhone' => [
            'models' => ['6S','7','8','X','XS'],
            'options' => [
                'vendor' => 'iPhone',
                'type' => 'Smartphone',
                'platform' => 'iOS',
            ]
        ],
        'HUAWEI' => [
            'models' => ['Nova','P30','Mate','Y5','Y7'],
            'options' => [
                'vendor' => 'HUAWEI',
                'type' => 'Smartphone',
                'platform' => 'Android',
            ]
        ],
        'Samsung' => [
            'models' => ['Galaxy A50','Galaxy S10','Galaxy M30S','Galaxy Note 10','Galaxy S9'],
            'options' => [
                'vendor' => 'Samsung',
                'type' => 'Smartphone',
                'platform' => 'Android',
            ]
        ],
        'Meizu' => [
            'models' => ['16Xs','Note 9','16','X8','M8'],
            'options' => [
                'vendor' => 'Meizu',
                'type' => 'Smartphone',
                'platform' => 'Android',
            ]
        ],
        'BQ' => [
            'models' => ['2814 Shell','3595 Elegant','2436 Fortune','2432 Tank','2435 Shell'],
            'options' => [
                'vendor' => 'BQ',
                'type' => 'Telephone',
            ]
        ],
        'DEXP' => [
            'models' => ['Larus Z8','V242','B281','SD2810','C241'],
            'options' => [
                'vendor' => 'DEXP',
                'type' => 'Telephone',
            ]
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->catalogSchematic as $catalog) {
            foreach ($catalog['models'] as $modelName) {
                $json = [];
                foreach ($catalog['options'] as $optionName => $optionValue) {
                    $json[$optionName] = $optionValue;
                }

                DB::table('products')->insert([
                    'name' => $modelName,
                    'picture' => $this->getPicture($catalog['options']['type']),
                    'price' => (float) mt_rand(1000,90000) . '.' . mt_rand(1,99),
                    'data' => json_encode($json)
                ]);
            }
        }

    }

    /**
     *
     * @param string $type
     * @return string
     */
    private function getPicture($type = 'Smartphone')
    {
        $name = '';
        switch ($type) {
            case 'Smartphone':
                $name = 's' . mt_rand(1,10) . '.jpg';
                break;
            case 'Telephone':
                $name = 't' . mt_rand(1,5) . '.jpg';
                break;
            default:
                $name = '0.jpg';
        }

        return $name;
    }
}
