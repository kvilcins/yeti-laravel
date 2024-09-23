<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Http\Requests\Lot\StoreRequest;
use App\Http\Controllers\DataController;

class LotController extends Controller
{
    // Используем DataController для получения общих данных
    protected $dataController;
    protected $breadcrumbsController;
    
    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }
    
    // Метод для отображения страницы со всеми лотами
    public function index()
    {
        $commonData = $this->dataController->getCommonData();
        $lots = Item::all();
    
        // Генерация хлебных крошек
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());
    
        return view('pages.index', array_merge($commonData, ['lots' => $lots, 'breadcrumbs' => $breadcrumbs]));
    }
    
    // Метод для отображения формы создания лота
    public function create()
    {
        $commonData = $this->dataController->getCommonData();
    
        // Генерация хлебных крошек
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());
    
        return view('pages.add', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }
    
    // Метод для сохранения данных формы создания лота
    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();
        
        // Поиск категории
        $category = Category::where('name', $request->input('category'))->first();
        
        if (!$category) {
            return redirect()->back()->withErrors(['category' => 'Категория не найдена'])->withInput();
        }
        
        // Обработка изображения
        $imageName = null;
        if ($request->hasFile('lot-img')) {
            $imageName = uniqid() . '.' . $request->file('lot-img')->extension();
            $request->file('lot-img')->move(public_path('img'), $imageName);
        }
        
        // Сохранение данных в таблицу `items`
        Item::create([
            'title' => $validatedData['lot-name'],
            'description' => $validatedData['message'],
            'price' => $validatedData['lot-rate'],
            'min_bid' => $validatedData['lot-step'],
            'img' => $imageName ? 'img/' . $imageName : null,
            'category_id' => $category->id,
            'end_date' => $validatedData['lot-date'],
        ]);
        
        return redirect()->route('home')->with('success', 'Лот успешно добавлен!');
    }
    
    // Метод для отображения страницы конкретного лота
    public function show($id)
    {
        $commonData = $this->dataController->getCommonData();
        $lot = Item::with('category')->find($id);
        
        if (!$lot) {
            abort(404, 'Лот не найден');
        }
    
        // Генерация хлебных крошек
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());
    
        return view('pages.lot', array_merge($commonData, ['lot' => $lot, 'breadcrumbs' => $breadcrumbs]));
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
    //     $lot->end_date = $validatedData['lot-date'];
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
