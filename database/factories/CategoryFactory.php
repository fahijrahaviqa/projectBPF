<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Model yang terkait dengan factory.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake('id_ID')->unique()->word(),
            'icon' => fake()->randomElement([
                'fa fa-hamburger',
                'fa fa-coffee',
                'fa fa-cookie',
                'fa fa-utensils',
                'fa fa-ice-cream'
            ])
        ];
    }
} 