<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Column>
 */
class ColumnFactory extends Factory
{
    private static array $titleArray = ['Backlog', 'Ready', 'Doing', 'Review', 'Blocked', 'Done'];
    private static int $order = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => self::$titleArray[self::$order++],
        ];
    }
}
