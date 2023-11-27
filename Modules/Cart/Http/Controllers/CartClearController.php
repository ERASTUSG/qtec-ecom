<?php

namespace Modules\Cart\Http\Controllers;

use Modules\Cart\Facades\Cart;

class CartClearController
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Modules\Cart\Cart
     */
    public function store(): \Modules\Cart\Cart
    {
        Cart::clear();

        return Cart::instance();
    }
}
