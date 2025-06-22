<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Bid;
use App\Http\Requests\Lot\StoreRequest;
use App\Http\Controllers\DataController;
use Carbon\Carbon;

class LotController extends Controller
{
    protected $dataController;
    protected $breadcrumbsController;

    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }

    public function index()
    {
        $commonData = $this->dataController->getCommonData();
        $lots = Item::where('status', 'active')->get();

        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.index', array_merge($commonData, ['lots' => $lots, 'breadcrumbs' => $breadcrumbs]));
    }

    public function create()
    {
        $commonData = $this->dataController->getCommonData();
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.add', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::where('name', $request->input('category'))->first();

        if (!$category) {
            return redirect()->back()->withErrors(['category' => 'Category not found'])->withInput();
        }

        $imageName = null;
        if ($request->hasFile('lot_img')) {
            $imageName = $request->file('lot_img')->store('lots', 'public');
        }

        Item::create([
            'title' => $validatedData['lot_name'],
            'description' => $validatedData['message'],
            'price' => $validatedData['lot_rate'],
            'min_bid' => $validatedData['lot_step'],
            'img' => $imageName,
            'category_id' => $category->id,
            'timer' => $validatedData['timer'],
            'status' => 'active',
        ]);

        return redirect()->route('home')->with('success', 'Lot successfully added!');
    }

    public function placeBid(Request $request, $id)
    {
        $request->validate([
            'cost' => 'required|integer|min:1',
        ]);

        $lot = Item::findOrFail($id);

        if (!$lot->isActive()) {
            return redirect()->back()->withErrors(['cost' => 'This lot is not active for bidding.']);
        }

        if ($lot->timer && Carbon::parse($lot->timer)->isPast()) {
            return redirect()->back()->withErrors(['cost' => 'Bidding time has expired.']);
        }

        $maxBid = $lot->bids()->max('bid_amount');
        $minBid = $lot->min_bid;

        if ($request->cost <= max($maxBid, $minBid)) {
            return redirect()->back()->withErrors(['cost' => 'The bid must be higher than the current highest bid.']);
        }

        Bid::create([
            'lot_id' => $id,
            'user_id' => auth()->id(),
            'bid_amount' => $request->cost,
            'bid_time' => now(),
        ]);

        return redirect()->back()->with('success', 'Bid placed successfully!');
    }

    public function show($category_slug, $slug)
    {
        $commonData = $this->dataController->getCommonData();

        $category = Category::where('slug', $category_slug)->firstOrFail();

        $lot = Item::where('slug', $slug)
            ->where('category_id', $category->id)
            ->with(['category', 'user'])
            ->firstOrFail();

        if (!$lot) {
            abort(404, 'Lot not found');
        }

        $bids = $lot->bids()->with('user')->latest()->get();

        foreach ($bids as $bid) {
            $bidTime = Carbon::parse($bid->bid_time);
            $now = Carbon::now();

            if ($bidTime->diffInMinutes($now) < 60) {
                $bid->formatted_time = $bidTime->diffInMinutes($now) . ' minutes ago';
            } elseif ($bidTime->isToday()) {
                $bid->formatted_time = $bidTime->diffInHours($now) . ' hours ago';
            } else {
                $bid->formatted_time = $bidTime->format('d.m.Y at H:i');
            }
        }

        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        $isLotActive = $lot->isActive() && Carbon::parse($lot->timer)->isFuture();

        $canManage = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwnerOf($lot));

        return view('pages.lot', array_merge($commonData, [
            'lot' => $lot,
            'bids' => $bids,
            'breadcrumbs' => $breadcrumbs,
            'isLotActive' => $isLotActive,
            'canManage' => $canManage,
        ]));
    }
}
