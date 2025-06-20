<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Bid;
use App\Models\Item;
use App\Models\User;

class BidSeeder extends Seeder
{
    public function run(): void
    {
        $bidsConfig = config('bids.history', []);

        foreach ($bidsConfig as $bidData) {
            $user = User::where('name', $bidData['name'])->first();
            $lot = Item::find($bidData['lot_id']);

            if ($user && $lot) {
                Bid::updateOrCreate(
                    [
                        'lot_id' => $lot->id,
                        'user_id' => $user->id,
                        'bid_time' => Carbon::parse($bidData['time'])
                    ],
                    [
                        'bid_amount' => $bidData['price'],
                    ]
                );
            }
        }
    }
}
