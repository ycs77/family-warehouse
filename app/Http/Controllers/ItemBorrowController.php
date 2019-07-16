<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\WithStatus;
use App\Item;
use App\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

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

            if (!$item->borrow_user) {
                return redirect()->route('item', $item)
                    ->with('status', $this->error("現在沒有借走 {$item->name}，所以不能歸還。"));
            }

            if ($request->user()->cant('edit', Item::class)) {
                if (!$borrow_user) {
                    return redirect()->route('item', $item)
                        ->with('status', $this->error("您/您代管的小孩沒有借走 {$item->name}"));
                }
            }

            return $next($request);
        })->only(['returnPage', 'return']);
    }

    /**
     * Response item QR code.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function qrcode(Item $item)
    {
        $img = QrCode::size(300)->generate(Hashids::encode($item->id));
        return response($img)
            ->header('Content-Type', 'image/svg+xml');
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
    public function borrow(Request $request, Item $item, User $user)
    {
        if ($item->borrow_user) {
            return redirect()->route('item', $item)
                ->with('status', $this->error("無法借出 {$item->name}，因為已經被 {$item->borrow_user->name} 借走了！"));
        }

        $borrow_user = $user;
        $user = $request->user();

        $item->update([
            'borrow_user_id' => $borrow_user->id,
        ]);

        $item->histories()->create([
            'action' => 'borrow',
            'user_id' => $borrow_user->id,
            'parent_user_id' => $borrow_user->id === $user->id ? null : $user->id,
        ]);

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

        $item_old = $item->fresh('borrow_user');
        $item->update([
            'borrow_user_id' => null,
        ]);

        if ($borrow_user) {
            $item->histories()->create([
                'action' => 'return',
                'user_id' => $borrow_user->id,
                'parent_user_id' => $borrow_user->id === $user->id ? null : $user->id,
            ]);
        } else {
            // 跟借物者非親非故，但我是管理者，所以可以強制歸還
            if ($request->user()->can('edit', Item::class)) {
                $item->histories()->create([
                    'action' => 'return',
                    'user_id' => $item_old->borrow_user->id,
                    'parent_user_id' => $user->id,
                ]);
            }
        }

        return redirect()->route('item', $item)
            ->with('status', $this->success("歸還物品 {$item->name} 成功"));
    }
}
