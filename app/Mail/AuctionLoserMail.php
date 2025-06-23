<?php

namespace App\Mail;

use App\Models\Item;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuctionLoserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Item $auction,
        public User $user
    ) {}

    public function build()
    {
        return $this->subject('Auction ended - Better luck next time!')
            ->view('emails.auction-loser')
            ->with([
                'auction' => $this->auction,
                'user' => $this->user,
                'finalPrice' => $this->auction->getCurrentPrice()
            ]);
    }
}
