<?php

namespace Modules\Product\Repositories;

use Modules\Product\Entities\Product;

class ProductRepository
{
    public static function list($ids = [])
    {
        return Product::select('id')
            ->withName()
            ->whereIn('id', $ids)
            ->when(!empty($ids), function ($query) use ($ids) {
                $idsString = collect($ids)
                    ->filter()
                    ->implode(',');

                $query->orderByRaw("FIELD(id, {$idsString})");
            })
            ->get()
            ->mapWithKeys(function ($product) {
                return [$product->id => $product->name];
            });
    }


    public static function findBySlug($slug)//: Model|Builder
    {
        $product = Product::with(['variations', 'variants', 'categories', 'tags', 'attributes.attribute.attributeSet', 'options', 'files', 'relatedProducts', 'upSellProducts'])
            ->where('slug', $slug)
            ->firstOrFail();

        if (request()->query('variant') && !$product->variant) {
            abort(404);
        }

        return $product;
    }
}
