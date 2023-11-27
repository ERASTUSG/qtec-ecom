<?php

namespace Modules\Cart\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class CartController
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        return view('public.cart.index');
    }
}
