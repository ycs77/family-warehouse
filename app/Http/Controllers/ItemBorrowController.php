<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\WithStatus;
use App\Item;
use App\User;
use Illuminate\Http\Request;

class ItemBorrowController extends Controller
{
    use WithStatus;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authorize('view', Item::class);
            return $next($request);
        });

        $this->middleware(function ($request, $next) {
            $item = $request->item;
            $borrow_user = $request->user()->getSelfOrChildToBorrow($item);

            if ($request->user()->cant('edit', Item::class)) {
                if (!$item->borrow_user) {
                    return redirect()->route('item', $item)
                        ->with('status', $this->error("現在沒有借走 {$item->name}，所以不能歸還..."));
                }

                if (!$borrow_user) {
                    return redirect()->route('item', $item)
                        ->with('status', $this->error("您/您代管的小孩沒有借走 {$item->name}..."));
                }
            }

            return $next($request);
        })->only(['returnPage', 'return']);
    }

    /**
     * Borrow things page.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function borrowPage(Item $item)
    {
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
        if ($item->borrow_user) {
            return redirect()->route('item', $item)
                ->with('status', $this->error("無法借出 {$item->name}，因為已經被 {$item->borrow_user->name} 借走了！"));
        }

        $borrow_user = $user->id ? $user : $request->user();

        $item->update([
            'borrow_user_id' => $borrow_user->id,
        ]);

        // if ($borrow_user) {
        //     // History - $borrow_user
        // } else {
        //     //
        // }

        return redirect()->route('item', $item)
            ->with('status', $this->success("借出物品 {$item->name} 成功"));
    }

    /**
     * Return things page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function returnPage(Request $request, Item $item)
    {
        return view('items.return', compact('item'));
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
        $user = $request->user();
        $borrow_user = $request->user()->getSelfOrChildToBorrow($item);

        $item->update([
            'borrow_user_id' => null,
        ]);

        // if ($borrow_user) {
        //     // History - $borrow_user
        // } elseif (!$borrow_user && $user) {
        //     //
        // }

        return redirect()->route('item', $item)
            ->with('status', $this->success("歸還物品 {$item->name} 成功"));
    }
}
