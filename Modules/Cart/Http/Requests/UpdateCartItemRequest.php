<?php

namespace Modules\Cart\Http\Requests;

use Modules\Cart\CartItem;
use Modules\Cart\Facades\Cart;
use Modules\Product\Entities\Product;
use Modules\Core\Http\Requests\Request;
use Modules\Product\Entities\ProductVariant;

class UpdateCartItemRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $item = $this->getCartItem()->item;

        return [
            'qty' => ['required', 'numeric', $this->maxQty($item)],
        ];
    }


    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'qty' => (int)$this->qty,
        ]);
    }


    /**
     * Get the item from the cart
     *
     * @return CartItem
     */
    private function getCartItem(): CartItem
    {
        return Cart::items()->get($this->cartItemId)->refreshStock();
    }


    /**
     * Get the max qty rule for the given product or variant.
     *
     * @param Product|ProductVariant $item
     *
     * @return string|null
     */
    private function maxQty(Product|ProductVariant $item)
    {
        if ($item->manage_stock) {
            return "max:{$item->qty}";
        }
    }
}
