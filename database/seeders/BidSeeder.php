<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Bid;
use App\Models\Item;
use App\Models\User;

class BidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получение данных из конфига
        $bidsConfig = config('bids.history');
        
        foreach ($bidsConfig as $bidData) {
            $user = User::where('name', $bidData['name'])->first();
            $lot = Item::find($bidData['lot_id']); // Идентификатор лота
            
            if ($user && $lot) {
                $bidTime = Carbon::parse($bidData['time']);
                $now = Carbon::now();
                
                // Рассчитываем форматированное время
                if ($bidTime->diffInMinutes($now) < 60) {
                    $formattedTime = $bidTime->diffInMinutes($now) . ' минут назад';
                } elseif ($bidTime->isToday()) {
                    $formattedTime = $bidTime->diffInHours($now) . ' часов назад';
                } else {
                    $formattedTime = $bidTime->format('d.m.Y в H:i');
                }
                
                // Сохраняем ставку
                Bid::create([
                    'lot_id' => $lot->id,
                    'user_id' => $user->id,
                    'bid_amount' => $bidData['price'],
                    'bid_time' => $bidTime,
                    'formatted_time' => $formattedTime,
                ]);
            }
        }
    }
}
