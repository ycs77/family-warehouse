<?php

namespace App\Http\Controllers;

use App\FormFields\UserEditFormFields;
use App\FormFields\UserFormFields;
use App\FormFields\UserPassswordFormFields;
use App\Http\Controllers\Traits\WithStatus;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ycs77\LaravelFormFieldType\Traits\FormFieldsTrait;

class UserController extends Controller
{
    use FormFieldsTrait;
    use WithStatus;

    protected $formFields;
    protected $editFormFields;
    protected $passwordFormFields;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserFormFields $formFields,
        UserEditFormFields $editFormFields,
        UserPassswordFormFields $passwordFormFields
    )
    {
        $this->formFields = $formFields;
        $this->editFormFields = $editFormFields;
        $this->passwordFormFields = $passwordFormFields;

        $this->middleware(function ($request, $next) {
            $this->authorize('edit', User::class);
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->renderForm([
            'url' => route('users.store'),
            'method' => 'POST',
        ]);

        $allChildren = User::where('role', 'child')->get();

        return view('users.create', compact('form', 'allChildren'));
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

        unset($data['password_confirmation']);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = User::create($data);

        // Update user children
        $request->validate([
            'children' => 'array',
            'children.*' => 'required|numeric',
        ]);

        if ($request->input('children')) {
            $user->updateChildren($request->input('children'));
        }

        return redirect()->route('users.show', $user)
            ->with('status', $this->createSuccess("新增用戶 {$user->name} 成功"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $form = $this->renderForm([
            'url' => route('users.update', $user),
            'method' => 'PUT',
            'model'  => $user,
        ], $this->editFormFields->fields($user->isCantDeprivation()));

        $allChildren = User::where('id', '<>', $user->id)->where('role', 'child')->get();

        return view('users.edit', compact('user', 'form', 'allChildren'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $this->validateFormData($request, $this->editFormFields->fields($user->isCantDeprivation()));

        $user->update($data);

        if ($user->role === 'child') {
            $user->children()->sync([]);
        } else {
            // Update user children
            $request->validate([
                'children' => 'array',
                'children.*' => 'required|numeric',
            ]);

            if ($request->input('children')) {
                $user->updateChildren($request->input('children'));
            }
        }

        return redirect()->route('users.show', $user)
            ->with('status', $this->updateSuccess("修改用戶 {$user->name} 成功"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->isCantDeprivation()) {
            abort(403);
        }

        $name = $user->name;
        $user->children()->sync([]);
        $user->parents()->sync([]);
        $user->delete();

        return redirect()->route('users.index')
            ->with('status', $this->deleteSuccess("刪除用戶 $name 成功"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function password(User $user)
    {
        $form = $this->renderForm([
            'url' => route('users.password.update', $user),
            'method' => 'PUT',
        ], $this->passwordFormFields->fields());

        return view('users.password', compact('user', 'form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, User $user)
    {
        $data = $this->validateFormData($request, $this->passwordFormFields->fields());

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('users.show', $user)
            ->with('status', $this->deleteSuccess("修改用戶 {$user->name} 密碼成功"));
    }

    /**
     * Borrow things history.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function borrow_history(User $user)
    {
        $histories = $user
            ->histories()
            ->with(['item', 'parent'])
            ->latest()
            ->paginate(20);

        return view('users.borrow_history', compact('user', 'histories'));
    }

    /**
     * Proxy borrow children things history.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function proxy_borrow_history(User $user)
    {
        $proxy_histories = $user
            ->proxy_histories()
            ->with(['item', 'user'])
            ->latest()
            ->paginate(20);

        return view('users.proxy_history', compact('user', 'proxy_histories'));
    }
}
