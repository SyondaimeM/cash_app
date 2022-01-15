<?php

use Illuminate\Database\Seeder;

class BankSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [

            ['id' => 1, 'name' => 'Swiss Bank', 'status' => '1'],
            ['id' => 2, 'name' => 'World Bank', 'status' => '1'],

        ];

        foreach ($items as $item) {
            \App\Bank::create($item);
        }
    }
}