<?php

namespace App\Http\Controllers\Store\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Auth;
use Request;

class OrderItemController extends Controller
{
    protected $section = 'storeAdmin';

    public function __construct()
    {
        $this->middleware('auth');

        if (Auth::user() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return parent::__construct();
    }

    public function update($orderId, $orderItemId)
    {
        $item = Store\OrderItem::findOrFail($orderItemId);

        if ($item->order_id !== $orderId) {
            return error_popup('invalid order id for this item.');
        }

        if ($item->order->status !== 'paid') {
            return error_popup("order status {$item->order->status} is invalid.");
        }

        $item->unguard();
        $item->update(Request::input('item'));
        $item->save();

        return ['message' => "order item {$orderItemId} updated"];
    }
}
