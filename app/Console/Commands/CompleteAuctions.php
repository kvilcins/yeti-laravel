<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\User;
use App\Mail\AuctionWinnerMail;
use App\Mail\AuctionLoserMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CompleteAuctions extends Command
{
    protected $signature = 'auctions:complete';
    protected $description = 'Complete expired auctions and notify winners/losers';

    public function handle()
    {
        $expiredAuctions = Item::where('status', 'active')
            ->where('timer', '<=', Carbon::now())
            ->get();

        if ($expiredAuctions->isEmpty()) {
            $this->info('No expired auctions found.');
            return;
        }

        foreach ($expiredAuctions as $auction) {
            $this->processAuction($auction);
        }

        $this->info("Processed {$expiredAuctions->count()} expired auctions.");
    }

    private function processAuction(Item $auction)
    {
        $highestBid = $auction->getHighestBid();

        if ($highestBid) {
            $auction->winner_id = $highestBid->user_id;
            $auction->status = 'completed';
            $auction->save();

            $this->notifyWinner($auction, $highestBid->user);
            $this->notifyLosers($auction, $highestBid->user_id);

            $this->info("Auction '{$auction->title}' completed. Winner: {$highestBid->user->name}");
        } else {
            $auction->status = 'expired';
            $auction->save();

            $this->info("Auction '{$auction->title}' expired with no bids.");
        }
    }

    private function notifyWinner(Item $auction, User $winner)
    {
        try {
            Mail::to($winner->email)->send(new AuctionWinnerMail($auction, $winner));
            $this->info("Winner notification sent to {$winner->email}");
        } catch (\Exception $e) {
            $this->error("Failed to send winner notification: {$e->getMessage()}");
        }
    }

    private function notifyLosers(Item $auction, int $winnerId)
    {
        $losers = $auction->bids()
            ->where('user_id', '!=', $winnerId)
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id');

        foreach ($losers as $loser) {
            try {
                Mail::to($loser->email)->send(new AuctionLoserMail($auction, $loser));
                $this->info("Loser notification sent to {$loser->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send loser notification: {$e->getMessage()}");
            }
        }
    }
}
