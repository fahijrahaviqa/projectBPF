<?php

namespace Database\Factories;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Model yang terkait dengan factory.
     *
     * @var string
     */
    protected $model = MenuItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake('id_ID')->words(2, true),
            'price' => fake()->numberBetween(10000, 50000),
            'description' => fake('id_ID')->sentence(),
            'image' => 'menu-images/default-' . fake()->numberBetween(1, 5) . '.jpg',
        ];
    }
} 