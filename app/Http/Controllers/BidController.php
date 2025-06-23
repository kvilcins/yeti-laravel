<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Bid;

class BidController extends Controller
{
    public function store(Request $request, $lot)
    {
        $validatedData = $request->validate([
            'cost' => 'required|numeric|min:1',
        ]);

        $item = Item::find($lot);

        if (!$item) {
            return redirect()->back()->withErrors(['lot' => 'Lot not found.']);
        }

        Bid::create([
            'lot_id' => $item->id,
            'user_id' => auth()->user()->id,
            'price' => $validatedData['cost'],
        ]);

        return redirect()->route('lot.show', $item->id)->with('success', 'Bid successfully placed!');
    }

    public function destroy($id)
    {
        $bid = Bid::findOrFail($id);

        if (!auth()->user()->isAdmin() && $bid->user_id !== auth()->id()) {
            abort(403, 'You can only delete your own bids');
        }

        $lotId = $bid->lot_id;
        $bid->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bid deleted successfully!'
            ]);
        }

        if (request()->has('from_profile')) {
            return redirect()->route('profile.')->with('success', 'Bid deleted successfully!');
        }

        if ($bid->lot) {
            return redirect()->route('lot.show', [$bid->lot->category->slug, $bid->lot->slug])
                ->with('success', 'Bid deleted successfully!');
        }

        return redirect()->route('profile.')->with('success', 'Bid deleted successfully!');
    }
}
