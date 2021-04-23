<?php


namespace App\Presenter;


class WritePostJsonPresenter implements WritePostPresenter
{
    public function toJson()
    {
        return response()->json(null, 200);
    }
}
