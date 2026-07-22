<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(100, 1000),
            'min_bid' => fake()->numberBetween(10, 100),
            'img' => null,
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
            'timer' => now()->addDay(),
            'status' => 'active',
            'winner_id' => null,
        ];
    }

    /**
     * A lot whose bidding window has already closed.
     */
    public function expired(): static
    {
        return $this->state(fn () => ['timer' => now()->subHour()]);
    }

    public function completed(): static
    {
        return $this->state(fn () => ['status' => 'completed']);
    }
}
