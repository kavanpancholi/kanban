<?php

namespace Database\Factories;

use App\Models\Column;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    private static int $order = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'column_id' => Column::factory(),
            'title' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'order' => self::$order++,
        ];
    }
}
