<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * A bid is only accepted when the lot is still active, the timer has not run out,
 * the bidder does not own the lot, and the amount beats both the current highest
 * bid and the seller's minimum step.
 */
class BidRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_valid_bid_is_stored(): void
    {
        $seller = User::factory()->create();
        $bidder = User::factory()->create();
        $lot = Item::factory()->create(['user_id' => $seller->id, 'min_bid' => 100]);

        $this->actingAs($bidder)
            ->post("/lots/{$lot->id}/bid", ['cost' => 250])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bids', [
            'lot_id' => $lot->id,
            'user_id' => $bidder->id,
            'bid_amount' => 250,
        ]);
    }

    public function test_a_seller_cannot_bid_on_their_own_lot(): void
    {
        $seller = User::factory()->create();
        $lot = Item::factory()->create(['user_id' => $seller->id, 'min_bid' => 100]);

        $this->actingAs($seller)
            ->post("/lots/{$lot->id}/bid", ['cost' => 500])
            ->assertSessionHasErrors('cost');

        $this->assertDatabaseCount('bids', 0);
    }

    public function test_a_bid_must_beat_the_current_highest_bid(): void
    {
        $seller = User::factory()->create();
        $bidder = User::factory()->create();
        $lot = Item::factory()->create(['user_id' => $seller->id, 'min_bid' => 100]);

        Bid::factory()->create(['lot_id' => $lot->id, 'bid_amount' => 500]);

        $this->actingAs($bidder)
            ->post("/lots/{$lot->id}/bid", ['cost' => 400])
            ->assertSessionHasErrors('cost');

        $this->assertDatabaseCount('bids', 1);
    }

    public function test_a_bid_equal_to_the_current_highest_bid_is_rejected(): void
    {
        $seller = User::factory()->create();
        $bidder = User::factory()->create();
        $lot = Item::factory()->create(['user_id' => $seller->id, 'min_bid' => 100]);

        Bid::factory()->create(['lot_id' => $lot->id, 'bid_amount' => 500]);

        $this->actingAs($bidder)
            ->post("/lots/{$lot->id}/bid", ['cost' => 500])
            ->assertSessionHasErrors('cost');

        $this->assertDatabaseCount('bids', 1);
    }

    public function test_the_first_bid_must_clear_the_minimum_step(): void
    {
        $seller = User::factory()->create();
        $bidder = User::factory()->create();
        $lot = Item::factory()->create(['user_id' => $seller->id, 'min_bid' => 300]);

        $this->actingAs($bidder)
            ->post("/lots/{$lot->id}/bid", ['cost' => 250])
            ->assertSessionHasErrors('cost');

        $this->assertDatabaseCount('bids', 0);
    }

    public function test_bidding_is_closed_once_the_timer_has_passed(): void
    {
        $seller = User::factory()->create();
        $bidder = User::factory()->create();
        $lot = Item::factory()->expired()->create(['user_id' => $seller->id, 'min_bid' => 100]);

        $this->actingAs($bidder)
            ->post("/lots/{$lot->id}/bid", ['cost' => 500])
            ->assertSessionHasErrors('cost');

        $this->assertDatabaseCount('bids', 0);
    }

    public function test_bidding_is_closed_on_a_lot_that_is_no_longer_active(): void
    {
        $seller = User::factory()->create();
        $bidder = User::factory()->create();
        $lot = Item::factory()->completed()->create(['user_id' => $seller->id, 'min_bid' => 100]);

        $this->actingAs($bidder)
            ->post("/lots/{$lot->id}/bid", ['cost' => 500])
            ->assertSessionHasErrors('cost');

        $this->assertDatabaseCount('bids', 0);
    }

    public function test_the_bid_amount_is_validated(): void
    {
        $bidder = User::factory()->create();
        $lot = Item::factory()->create(['min_bid' => 100]);

        $this->actingAs($bidder)
            ->post("/lots/{$lot->id}/bid", ['cost' => 0])
            ->assertSessionHasErrors('cost');

        $this->actingAs($bidder)
            ->post("/lots/{$lot->id}/bid", ['cost' => 'not-a-number'])
            ->assertSessionHasErrors('cost');

        $this->assertDatabaseCount('bids', 0);
    }

    public function test_a_guest_cannot_bid(): void
    {
        $lot = Item::factory()->create(['min_bid' => 100]);

        $this->post("/lots/{$lot->id}/bid", ['cost' => 500])
            ->assertRedirect('/login');

        $this->assertDatabaseCount('bids', 0);
    }
}
