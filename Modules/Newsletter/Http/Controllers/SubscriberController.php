<?php

namespace Modules\Newsletter\Http\Controllers;

use Newsletter;
use Illuminate\Http\Response;
use Modules\Newsletter\Http\Requests\StoreSubscriberRequest;

class SubscriberController
{
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreSubscriberRequest $request)
    {
        Newsletter::subscribeOrUpdate($request->email);

        if (!Newsletter::lastActionSucceeded()) {
            return response()->json([
                'message' => str_after(Newsletter::getLastError(), '400: '),
            ], 403);
        }
    }
}
