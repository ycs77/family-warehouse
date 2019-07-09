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

        return view('users.create', compact('form'));
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

        return redirect()->route('users.index')
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
        ], $this->editFormFields->fields());

        if ($user->isCantDeprivation()) {
            $form->remove('role');
        }

        return view('users.edit', compact('user', 'form'));
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
        $data = $this->validateFormData($request, $this->editFormFields->fields());

        $user->update($data);

        return redirect()->route('users.index')
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

        return view('users.edit', compact('user', 'form'));
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

        return redirect()->route('users.index')
            ->with('status', $this->deleteSuccess("修改用戶 {$user->name} 密碼成功"));
    }
}
