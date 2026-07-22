<?php

namespace Database\Factories;

use App\Models\Bid;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bid>
 */
class BidFactory extends Factory
{
    protected $model = Bid::class;

    public function definition(): array
    {
        return [
            'lot_id' => Item::factory(),
            'user_id' => User::factory(),
            'bid_amount' => fake()->numberBetween(100, 5000),
            'bid_time' => now(),
        ];
    }
}
