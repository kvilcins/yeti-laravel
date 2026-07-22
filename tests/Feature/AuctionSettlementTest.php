<?php

namespace Tests\Feature;

use App\Mail\AuctionLoserMail;
use App\Mail\AuctionWinnerMail;
use App\Models\Bid;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * `auctions:complete` runs on a schedule and settles every lot whose timer has
 * run out: it picks the winner, closes the lot, and notifies both sides.
 */
class AuctionSettlementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
    }

    public function test_it_awards_the_lot_to_the_highest_bidder(): void
    {
        $lot = Item::factory()->expired()->create();

        $loser = User::factory()->create();
        $winner = User::factory()->create();

        Bid::factory()->create(['lot_id' => $lot->id, 'user_id' => $loser->id, 'bid_amount' => 400]);
        Bid::factory()->create(['lot_id' => $lot->id, 'user_id' => $winner->id, 'bid_amount' => 900]);

        $this->artisan('auctions:complete')->assertSuccessful();

        $lot->refresh();

        $this->assertSame('completed', $lot->status);
        $this->assertSame($winner->id, $lot->winner_id);
    }

    public function test_it_notifies_the_winner_and_every_losing_bidder(): void
    {
        $lot = Item::factory()->expired()->create();

        $loserOne = User::factory()->create();
        $loserTwo = User::factory()->create();
        $winner = User::factory()->create();

        Bid::factory()->create(['lot_id' => $lot->id, 'user_id' => $loserOne->id, 'bid_amount' => 300]);
        Bid::factory()->create(['lot_id' => $lot->id, 'user_id' => $loserTwo->id, 'bid_amount' => 500]);
        Bid::factory()->create(['lot_id' => $lot->id, 'user_id' => $winner->id, 'bid_amount' => 900]);

        $this->artisan('auctions:complete');

        Mail::assertSent(AuctionWinnerMail::class, fn ($mail) => $mail->hasTo($winner->email));
        Mail::assertSent(AuctionLoserMail::class, 2);
        Mail::assertNotSent(AuctionLoserMail::class, fn ($mail) => $mail->hasTo($winner->email));
    }

    public function test_a_losing_bidder_is_emailed_once_however_many_bids_they_placed(): void
    {
        $lot = Item::factory()->expired()->create();

        $loser = User::factory()->create();
        $winner = User::factory()->create();

        Bid::factory()->create(['lot_id' => $lot->id, 'user_id' => $loser->id, 'bid_amount' => 200]);
        Bid::factory()->create(['lot_id' => $lot->id, 'user_id' => $loser->id, 'bid_amount' => 400]);
        Bid::factory()->create(['lot_id' => $lot->id, 'user_id' => $loser->id, 'bid_amount' => 600]);
        Bid::factory()->create(['lot_id' => $lot->id, 'user_id' => $winner->id, 'bid_amount' => 900]);

        $this->artisan('auctions:complete');

        Mail::assertSent(AuctionLoserMail::class, 1);
    }

    public function test_a_lot_that_closed_without_bids_is_marked_expired(): void
    {
        $lot = Item::factory()->expired()->create();

        $this->artisan('auctions:complete');

        $lot->refresh();

        $this->assertSame('expired', $lot->status);
        $this->assertNull($lot->winner_id);

        Mail::assertNothingSent();
    }

    public function test_a_lot_whose_timer_is_still_running_is_left_alone(): void
    {
        $lot = Item::factory()->create(['timer' => now()->addDay()]);

        Bid::factory()->create(['lot_id' => $lot->id, 'bid_amount' => 900]);

        $this->artisan('auctions:complete');

        $lot->refresh();

        $this->assertSame('active', $lot->status);
        $this->assertNull($lot->winner_id);

        Mail::assertNothingSent();
    }

    public function test_a_lot_that_was_already_settled_is_not_processed_again(): void
    {
        $lot = Item::factory()->expired()->completed()->create();

        Bid::factory()->create(['lot_id' => $lot->id, 'bid_amount' => 900]);

        $this->artisan('auctions:complete');

        Mail::assertNothingSent();
    }
}
