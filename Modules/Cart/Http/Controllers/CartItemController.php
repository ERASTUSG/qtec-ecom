<?php

namespace Modules\Cart\Http\Controllers;

use Exception;
use Modules\Cart\Facades\Cart;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Checkers\ValidCoupon;
use Modules\Coupon\Checkers\MaximumSpend;
use Modules\Coupon\Checkers\MinimumSpend;
use Modules\Coupon\Checkers\CouponExists;
use Modules\Coupon\Checkers\AlreadyApplied;
use Modules\Coupon\Checkers\ExcludedProducts;
use Modules\Coupon\Checkers\ApplicableProducts;
use Modules\Coupon\Checkers\ExcludedCategories;
use Modules\Coupon\Checkers\UsageLimitPerCoupon;
use Modules\Cart\Http\Middleware\CheckItemStock;
use Modules\Coupon\Checkers\ApplicableCategories;
use Modules\Coupon\Checkers\UsageLimitPerCustomer;
use Modules\Cart\Http\Requests\StoreCartItemRequest;
use Modules\Cart\Http\Requests\UpdateCartItemRequest;

class CartItemController extends Controller
{
    private array $checkers = [
        CouponExists::class,
        AlreadyApplied::class,
        ValidCoupon::class,
        MinimumSpend::class,
        MaximumSpend::class,
        ApplicableProducts::class,
        ExcludedProducts::class,
        ApplicableCategories::class,
        ExcludedCategories::class,
        UsageLimitPerCoupon::class,
        UsageLimitPerCustomer::class,
    ];


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckItemStock::class)->only(['store', 'update']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCartItemRequest $request
     *
     * @return \Modules\Cart\Cart
     */
    public function store(StoreCartItemRequest $request)
    {
        Cart::store(
            $request->product_id,
            $request->variant_id,
            $request->qty,
            $request->options ?? []
        );

        return Cart::instance();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCartItemRequest $request
     * @param string $cartItemId
     *
     * @return \Modules\Cart\Cart
     */
    public function update(UpdateCartItemRequest $request, string $cartItemId)
    {
        Cart::updateQuantity($cartItemId, request('qty'));

        $cartWithCoupon = null;
        $couponCode = request()->query('coupon_code');
        if ($couponCode) {
            $coupon = Coupon::findByCode($couponCode);
            try {
                resolve(Pipeline::class)
                    ->send($coupon)
                    ->through($this->checkers)
                    ->then(function ($coupon) use (&$cartWithCoupon) {
                        Cart::applyCoupon($coupon);
                        $cartWithCoupon = json_encode(Cart::instance());
                        Cart::removeCoupon();
                    });
            } catch (Exception) {
                //Just suppressing the exception
            }
        }

        return $cartWithCoupon ?? Cart::instance();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param string $cartItemId
     *
     * @return \Modules\Cart\Cart
     */
    public function destroy(string $cartItemId)
    {
        Cart::remove($cartItemId);

        $cartWithCoupon = null;
        $couponCode = request()->query('coupon_code');
        if ($couponCode) {
            $coupon = Coupon::findByCode($couponCode);
            try {
                resolve(Pipeline::class)
                    ->send($coupon)
                    ->through($this->checkers)
                    ->then(function ($coupon) use (&$cartWithCoupon) {
                        Cart::applyCoupon($coupon);
                        $cartWithCoupon = json_encode(Cart::instance());
                        Cart::removeCoupon();
                    });
            } catch (Exception) {
                //Just suppressing the exception
            }
        }

        return $cartWithCoupon ?? Cart::instance();
    }
}
