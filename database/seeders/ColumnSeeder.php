<?php

namespace Database\Seeders;

use App\Models\Column;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Str;

class ColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $columnNames = ['Backlog', 'Ready', 'Doing', 'Review', 'Blocked', 'Done'];

        $faker = Factory::create();

        foreach ($columnNames as $columnName) {
            $cards = [];

            foreach (range(1, 7) as $i) {
                $cards[] = [
                    'id' => Str::uuid(),
                    'title' => $faker->word,
                    'description' => $faker->paragraph,
                ];
            }

            Column::create(['title' => $columnName, 'cards' => $cards]);
        }
    }
}
