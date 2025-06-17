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
        $lots = Item::all();

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
            return redirect()->back()->withErrors(['category' => 'Категория не найдена'])->withInput();
        }

        $imageName = null;
        if ($request->hasFile('lot-img')) {
            $imageName = uniqid() . '.' . $request->file('lot-img')->extension();
            $request->file('lot-img')->move(public_path('img'), $imageName);
        }

        Item::create([
            'title' => $validatedData['lot-name'],
            'description' => $validatedData['message'],
            'price' => $validatedData['lot-rate'],
            'min_bid' => $validatedData['lot-step'],
            'img' => $imageName ? 'img/' . $imageName : null,
            'category_id' => $category->id,
            'timer' => $validatedData['timer'],
        ]);

        return redirect()->route('home')->with('success', 'Лот успешно добавлен!');
    }

    public function placeBid(Request $request, $id)
    {
        $request->validate([
            'cost' => 'required|integer|min:1',
        ]);

        $lot = Item::findOrFail($id);

        $maxBid = $lot->bids()->max('bid_amount');
        $minBid = $lot->min_bid;

        if ($request->cost <= max($maxBid, $minBid)) {
            return redirect()->back()->withErrors(['cost' => 'Ставка должна быть выше текущей максимальной ставки.']);
        }

        Bid::create([
            'lot_id' => $id,
            'user_id' => auth()->id(),
            'bid_amount' => $request->cost,
            'bid_time' => now(),
        ]);

        return redirect()->back()->with('success', 'Ставка успешно сделана!');
    }

    public function show($category_slug, $slug)
    {
        $commonData = $this->dataController->getCommonData();

        $category = Category::where('slug', $category_slug)->firstOrFail();

        $lot = Item::where('slug', $slug)
                   ->where('category_id', $category->id)
                   ->with('category')
                   ->firstOrFail();

        if (!$lot) {
            abort(404, 'Лот не найден');
        }

        $bids = $lot->bids()->with('user')->latest()->get();

        foreach ($bids as $bid) {
            $bidTime = Carbon::parse($bid->bid_time);
            $now = Carbon::now();

            if ($bidTime->diffInMinutes($now) < 60) {
                $bid->formatted_time = $bidTime->diffInMinutes($now) . ' минут назад';
            } elseif ($bidTime->isToday()) {
                $bid->formatted_time = $bidTime->diffInHours($now) . ' часов назад';
            } else {
                $bid->formatted_time = $bidTime->format('d.m.Y в H:i');
            }
        }

        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.lot', array_merge($commonData, [
            'lot' => $lot,
            'bids' => $bids,
            'breadcrumbs' => $breadcrumbs,
        ]));
    }


    // Методы для редактирования, удаления и обновления лотов (закомментированы на будущее)

    // public function edit($id)
    // {
    //     $commonData = $this->dataController->getCommonData();
    //     $lot = Item::find($id);
    //
    //     if (!$lot) {
    //         abort(404, 'Лот не найден');
    //     }
    //
    //     return view('pages.edit', array_merge($commonData, ['lot' => $lot]));
    // }

    // public function update(StoreRequest $request, $id)
    // {
    //     $validatedData = $request->validated();
    //
    //     $lot = Item::find($id);
    //
    //     if (!$lot) {
    //         abort(404, 'Лот не найден');
    //     }
    //
    //     $lot->title = $validatedData['lot-name'];
    //     $lot->description = $validatedData['message'];
    //     $lot->price = $validatedData['lot-rate'];
    //     $lot->min_bid = $validatedData['lot-step'];
    //     $lot->timer = $validatedData['timer'];
    //
    //     if ($request->hasFile('lot-img')) {
    //         $imageName = uniqid() . '.' . $request->file('lot-img')->extension();
    //         $request->file('lot-img')->move(public_path('img'), $imageName);
    //         $lot->img = 'img/' . $imageName;
    //     }
    //
    //     $lot->save();
    //
    //     return redirect()->route('lots.show', $id)->with('success', 'Лот успешно обновлен!');
    // }

    // public function destroy($id)
    // {
    //     $lot = Item::find($id);
    //
    //     if (!$lot) {
    //         abort(404, 'Лот не найден');
    //     }
    //
    //     $lot->delete();
    //
    //     return redirect()->route('lots.index')->with('success', 'Лот успешно удален!');
    // }
}
