<?php

namespace App\Http\Controllers;

use App\Category;
use App\FormFields\ItemFormFields;
use App\Http\Controllers\Traits\WithStatus;
use App\Item;
use App\User;
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
        })->except(['index', 'show', 'borrowPage', 'borrow', 'return']);
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

        if ($borrow === 'true') {
            $itemsQuery->whereNotNull('borrow_user_id');
        } else if ($borrow === 'false') {
            $itemsQuery->whereNull('borrow_user_id');
        }

        $items = $itemsQuery->paginate(20);

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

        return view('items.show', compact('item'));
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

        return redirect()->route('items.index')
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

    /**
     * Borrow things page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function borrowPage(Request $request, Item $item)
    {
        $this->authorize('view', Item::class);

        if ($item->borrow_user) {
            return redirect()->route('item', $item)
                ->with('status', $this->error("無法借出 {$item->name}，因為已經被 {$item->borrow_user->name} 借走了！"));
        }

        return view('items.borrow', compact('item'));
    }

    /**
     * Borrow things.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function borrow(Request $request, Item $item, ?User $user)
    {
        $this->authorize('view', Item::class);

        if ($item->borrow_user) {
            return redirect()->route('item', $item)
                ->with('status', $this->error("無法借出 {$item->name}，因為已經被 {$item->borrow_user->name} 借走了！"));
        }

        $borrow_user = $user->id ? $user : $request->user();

        $item->update([
            'borrow_user_id' => $borrow_user->id,
        ]);
        // History - $borrow_user

        return redirect()->route('item', $item)
            ->with('status', $this->success("借出物品 {$item->name} 成功"));
    }

    /**
     * Return things.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function return(Request $request, Item $item)
    {
        $this->authorize('view', Item::class);

        if ($request->user()->cant('edit', Item::class)) {
            if (!$item->borrow_user) {
                return redirect()->route('item', $item)
                    ->with('status', $this->error("現在沒有借走 {$item->name}，所以不能歸還..."));
            }

            $borrow_user = $request->user()->getSelfOrChildToBorrow($item);

            if (!$borrow_user) {
                return redirect()->route('item', $item)
                    ->with('status', $this->error("您/您代管的小孩沒有借走 {$item->name}..."));
            }
        }

        $item->update([
            'borrow_user_id' => null,
        ]);
        // History - $borrow_user

        return redirect()->route('item', $item)
            ->with('status', $this->success("歸還物品 {$item->name} 成功"));
    }
}
