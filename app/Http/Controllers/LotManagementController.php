<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Lot\StoreRequest;
use App\Http\Requests\Lot\UpdateLotRequest;
use Illuminate\Support\Facades\Storage;

class LotManagementController extends Controller
{
    public function __construct(
        protected DataController $dataController,
        protected BreadcrumbsController $breadcrumbsController
    ) {}

    public function edit($id)
    {
        $lot = Item::findOrFail($id);

        if (!auth()->user()->isAdmin() && !auth()->user()->isOwnerOf($lot)) {
            abort(403, 'Access denied');
        }

        $commonData = $this->dataController->getCommonData();
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.edit-lot', array_merge($commonData, [
            'lot' => $lot,
            'breadcrumbs' => $breadcrumbs
        ]));
    }

    public function update(UpdateLotRequest $request, $id)
    {
        $lot = Item::findOrFail($id);

        if (!auth()->user()->isAdmin() && !auth()->user()->isOwnerOf($lot)) {
            abort(403, 'Access denied');
        }

        $validatedData = $request->validated();

        $category = Category::where('name', $request->input('category'))->first();
        if (!$category) {
            return redirect()->back()->withErrors(['category' => 'Category not found'])->withInput();
        }

        $lot->title = $validatedData['lot_name'];
        $lot->description = $validatedData['message'];
        $lot->price = $validatedData['lot_rate'];
        $lot->min_bid = $validatedData['lot_step'];
        $lot->category_id = $category->id;

        if ($request->filled('timer')) {
            $lot->timer = $validatedData['timer'];
        } else {
            $lot->timer = null;
        }

        if ($request->has('delete_image') && $request->delete_image == '1') {
            $this->removeLotImage($lot);
        } elseif ($request->hasFile('lot_img')) {
            $this->handleImageUpload($request, $lot);
        }

        $lot->save();

        return redirect()->route('profile.')->with('success', 'Lot updated successfully!');
    }

    private function handleImageUpload(Request $request, Item $lot): void
    {
        if (!$request->hasFile('lot_img')) {
            return;
        }

        if ($lot->img && !str_starts_with($lot->img, 'img/')) {
            Storage::delete('public/' . $lot->img);
        }

        $lot->img = $request->file('lot_img')->store('lots', 'public');
    }

    private function removeLotImage(Item $lot): void
    {
        if ($lot->img && !str_starts_with($lot->img, 'img/')) {
            Storage::delete('public/' . $lot->img);
            $lot->img = null;
        }
    }

    public function toggleStatus($id)
    {
        $lot = Item::findOrFail($id);

        if (!auth()->user()->isAdmin() && !auth()->user()->isOwnerOf($lot)) {
            abort(403, 'Access denied');
        }

        $lot->status = $lot->status === 'active' ? 'inactive' : 'active';
        $lot->save();

        $statusText = $lot->status === 'active' ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Lot {$statusText} successfully!");
    }

    public function destroy($id)
    {
        $lot = Item::findOrFail($id);

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can delete lots');
        }

        if ($lot->img && !str_starts_with($lot->img, 'img/')) {
            Storage::delete('public/' . $lot->img);
        }

        $lot->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lot deleted successfully!'
            ]);
        }

        return redirect()->route('profile.')->with('success', 'Lot deleted successfully!');
    }
}
