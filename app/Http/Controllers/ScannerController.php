<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class ScannerController extends Controller
{
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
    }

    /**
     * QR code scanner.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('scanner.index');
    }

    /**
     * Decode hash id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function decode(Request $request)
    {
        $item_id = Hashids::decode($request->input('code'))[0] ?? null;
        $item = Item::find($item_id);

        if (!$item) {
            return redirect()->route('scanner.error');
        }

        return redirect()->route('items.show', $item);
    }

    /**
     * QR code scanner error page.
     *
     * @return \Illuminate\Http\Response
     */
    public function error()
    {
        return view('scanner.error');
    }
}
