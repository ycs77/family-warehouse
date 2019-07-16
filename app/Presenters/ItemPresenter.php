<?php

namespace App\Presenters;

use Illuminate\Http\Request;

class ItemPresenter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function statusText($borrow, $search)
    {
        $texts = [];

        if ($borrow === 'true') {
            $texts[] = '篩選「已借物」';
        } else if ($borrow === 'false') {
            $texts[] = '篩選「未借物」';
        }

        if ($search) {
            $texts[] = "搜尋「{$search}」";
        }

        return implode('，', $texts);
    }
}
