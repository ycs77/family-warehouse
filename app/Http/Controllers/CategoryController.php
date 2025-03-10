<?php

namespace App\Http\Controllers;

use App\Category;
use App\FormFields\CategoryFormFields;
use App\Http\Controllers\Traits\WithStatus;
use Illuminate\Http\Request;
use Ycs77\LaravelFormFieldType\Traits\FormFieldsTrait;

class CategoryController extends Controller
{
    use FormFieldsTrait;
    use WithStatus;

    protected $formFields;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoryFormFields $formFields)
    {
        $this->formFields = $formFields;

        // Edit permission
        $this->middleware(function ($request, $next) {
            $this->authorize('edit', Category::class);
            return $next($request);
        })->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::withDepth()->get()->toTree();

        return view('categories.index', compact('categories'));
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
            'url' => route('categories.store'),
            'method' => 'POST',
        ]);

        return view('categories.create', compact('categories', 'form'));
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
        $data['parent_id'] = $request->input('parent_id');

        $category = Category::create($data);

        return redirect()->route('categories.show', $category)
            ->with('status', $this->createSuccess("新增分類 {$category->name} 成功"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $this->authorize('view', Category::class);

        $category_items = $category->items()
            ->with('borrow_user')
            ->paginate(24);

        return view('categories.show', compact('category', 'category_items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::withDepth()->get()->toFlatTree();

        $form = $this->renderForm([
            'url' => route('categories.update', $category),
            'method' => 'PUT',
            'model' => $category,
        ]);

        return view('categories.edit', compact('category', 'categories', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = $this->validateFormData($request);

        $parent_id = $request->input('parent_id');
        if ($category->parent_id !== $parent_id) {
            $data['parent_id'] = $parent_id;
        }

        $category->update($data);

        return redirect()->route('categories.show', $category)
            ->with('status', $this->updateSuccess("修改分類 {$category->name} 成功"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $name = $category->name;
        $category->delete();

        return redirect()->route('categories.index')
            ->with('status', $this->deleteSuccess("刪除分類 $name 成功"));
    }

    /**
     * Show the form for creating a new sub category.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function createSub(Category $category)
    {
        $form = $this->renderForm([
            'url' => route('categories.sub.store', $category),
            'method' => 'POST',
        ]);

        return view('categories.create_sub', compact('category', 'form'));
    }

    /**
     * Store a newly created sub category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function storeSub(Request $request, Category $category)
    {
        $data = $this->validateFormData($request);
        $data['parent_id'] = $category->id;

        $subCategory = Category::create($data);

        return redirect()->route('categories.show', $subCategory)
            ->with('status', $this->createSuccess("新增子分類 {$subCategory->name} 成功"));
    }
}
