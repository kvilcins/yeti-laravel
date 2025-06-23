<?php

namespace App\Mail;

use App\Models\Item;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuctionWinnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Item $auction,
        public User $winner
    ) {}

    public function build()
    {
        $winningBid = $this->auction->getHighestBid();

        return $this->subject('Congratulations! You won the auction!')
            ->view('emails.auction-winner')
            ->with([
                'auction' => $this->auction,
                'winner' => $this->winner,
                'winningBid' => $winningBid ? $winningBid->bid_amount : $this->auction->getCurrentPrice()
            ]);
    }
}
