<?php

namespace Modules\Cart\Http\Middleware;

use Closure;
use Modules\Cart\CartItem;
use Illuminate\Http\Request;
use Modules\Cart\Facades\Cart;
use Modules\Product\Entities\Product;
use Modules\FlashSale\Entities\FlashSale;
use Modules\Product\Entities\ProductVariant;

class CheckItemStock
{
    private CartItem|null $cartItem;
    private Product $product;
    private ProductVariant|null $variant;
    private Product|ProductVariant $item;


    public function __construct()
    {
        if (request()->routeIs('cart.items.update')) {
            $this->cartItem = Cart::items()->get(request('cartItemId'));
            $this->product = $this->cartItem->product;
            $this->variant = $this->cartItem->variant;
        }

        if (request()->routeIs('cart.items.store')) {
            $this->product = request('product_id') ? $this->getProduct(request('product_id')) : null;
            $this->variant = request('variant_id') ? $this->getVariant(request('variant_id')) : null;
        }

        $this->item = $this->variant ?? $this->product;
    }


    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->item->isOutOfStock()) {
            abort(400, trans('cart::messages.out_of_stock'));
        }


        if (!$this->hasFlashSaleStock()) {
            abort(400, trans('cart::messages.not_have_enough_quantity_in_stock', [
                'stock' => FlashSale::remainingQty($this->product),
            ]));
        }


        if (!$this->hasStock()) {
            abort(400, trans('cart::messages.not_have_enough_quantity_in_stock', [
                'stock' => $this->item->qty,
            ]));
        }

        return $next($request);
    }


    private function getProduct($id)
    {
        return Product::withName()
            ->addSelect('id', 'in_stock', 'manage_stock', 'qty')
            ->where('id', $id)
            ->firstOrFail();
    }


    private function getVariant($id)
    {
        return ProductVariant::addSelect('id', 'in_stock', 'manage_stock', 'qty')
            ->where('id', $id)
            ->firstOrFail();
    }


    private function hasFlashSaleStock(): bool
    {
        if (!FlashSale::contains($this->product)) {
            return true;
        }

        $remainingQty = FlashSale::remainingQty($this->product);

        $cartItem = Cart::items()->get(request('cartItemId'));

        if ($cartItem) {
            $addedCartQty = Cart::addedQty($cartItem);
            // Exclude current cart item quantity from the total added cart quantity
            // So, that current quantity is not added with the updated quantity.
            $addedCartQty -= $cartItem->qty;

            return ($remainingQty - $addedCartQty) >= request('qty');
        }

        return $remainingQty >= request('qty');
    }


    private function hasStock(): bool
    {
        if (!$this->item->manage_stock) {
            return true;
        }

        $cartItem = Cart::items()->get(request('cartItemId'));

        if ($cartItem) {
            $addedCartQty = Cart::addedQty($cartItem);

            // Exclude current cart item quantity from the total added cart quantity
            // So, that current quantity is not added with the updated quantity.
            $addedCartQty -= $cartItem->qty;

            return ($cartItem->item->qty - $addedCartQty) >= request('qty');
        }

        return $this->item->qty >= request('qty');
    }
}
