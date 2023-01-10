<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Column;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Column::factory()
            ->count(6)
            ->has(Card::factory()->count(rand(3, 9)))
            ->create();
    }
}
