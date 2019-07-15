<?php

namespace App\Http\Controllers;

use App\Category;
use App\FormFields\ItemFormFields;
use App\Http\Controllers\Traits\WithStatus;
use App\Item;
use Illuminate\Http\Request;
use Ycs77\LaravelFormFieldType\Traits\FormFieldsTrait;

class ItemController extends Controller
{
    use FormFieldsTrait;
    use WithStatus;

    protected $formFields;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ItemFormFields $formFields)
    {
        $this->formFields = $formFields;

        // Edit permission
        $this->middleware(function ($request, $next) {
            $this->authorize('edit', Item::class);
            return $next($request);
        })->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', Item::class);

        $itemsQuery = Item::query();
        $borrow = $request->query('borrow');
        $search = $request->query('q');

        if ($borrow === 'true') {
            $itemsQuery->whereNotNull('borrow_user_id');
        } else if ($borrow === 'false') {
            $itemsQuery->whereNull('borrow_user_id');
        }

        if ($search) {
            $itemsQuery->search($search, null, true, true);
        }

        $items = $itemsQuery
            ->with(['category', 'borrow_user'])
            ->paginate(20);

        return view('items.index', compact('items', 'borrow'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::withDepth()->get()->toFlatTree();

        $form = $this->renderForm([
            'url' => route('items.store'),
            'method' => 'POST',
        ]);

        return view('items.create', compact('form', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validateFormData($request);
        $data['category_id'] = $request->input('category_id');

        $item = Item::create($data);

        return redirect()->route('items.index')
            ->with('status', $this->createSuccess("新增物品 {$item->name} 成功"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        $this->authorize('view', Item::class);

        $histories = $item->histories()
            ->with(['user', 'parent'])
            ->latest()
            ->paginate(20);

        return view('items.show', compact('item', 'histories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $categories = Category::withDepth()->get()->toFlatTree();

        $form = $this->renderForm([
            'url' => route('items.update', $item),
            'method' => 'PUT',
            'model' => $item,
        ]);

        return view('items.edit', compact('item', 'form', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $data = $this->validateFormData($request);
        $data['category_id'] = $request->input('category_id');

        $item->update($data);

        return redirect()->route('item', $item)
            ->with('status', $this->updateSuccess("修改物品 {$item->name} 成功"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $name = $item->name;
        $item->delete();

        return redirect()->route('items.index')
            ->with('status', $this->deleteSuccess("刪除物品 $name 成功"));
    }
}
