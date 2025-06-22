<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Lot\StoreRequest;
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

    public function update(StoreRequest $request, $id)
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
        $lot->timer = $validatedData['timer'];

        if ($request->hasFile('lot_img')) {
            if ($lot->img && file_exists(public_path($lot->img))) {
                unlink(public_path($lot->img));
            }

            $imageName = uniqid() . '.' . $request->file('lot_img')->extension();
            $request->file('lot_img')->move(public_path('img'), $imageName);
            $lot->img = 'img/' . $imageName;
        }

        $lot->save();

        return redirect()->route('profile.')->with('success', 'Lot updated successfully!');
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

        if ($lot->img && file_exists(public_path($lot->img))) {
            unlink(public_path($lot->img));
        }

        $lot->delete();

        return redirect()->back()->with('success', 'Lot deleted successfully!');
    }
}
