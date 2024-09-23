<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreated;
use App\Helpers\ApiHelper;
use App\Models\Order;
use App\Models\Client;
use App\Models\OrdersClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class OrderController extends Controller
{
    public function getListOrders()
    {
        $listOrders = Order::all()->whereNull('deleted_at');

        return ApiHelper::sendResponse($listOrders, 'Data returned successfully!', 200);
    }

    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id,deleted_at,NULL',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            return ApiHelper::sendError($validator->errors(), "Validation Error", 422);
        }

        $orderClient = OrdersClient::create();

        foreach ($request->product_ids as $product_id) {
            Order::create([
                'order_id' => $orderClient->id,
                'client_id' => $request->client_id,
                'product_id' => $product_id,
            ]);
        }

        $client = Client::find($request->client_id);
        $orderDetails = Order::with('product')->where('order_id', $orderClient->id)->get();

        Mail::to($client->email)->send(new OrderCreated($orderDetails, $client->email));

        return ApiHelper::sendResponse($orderClient, 'Order created successfully!', 201);
    }

    public function getOrder($orderId)
    {
        $order = Order::where('id', $orderId)->whereNull('deleted_at')->first();

        if (!$order)
            return ApiHelper::sendError([], "Order not found.", 404);

        return ApiHelper::sendResponse($order, 'Data returned successfully!', 200);
    }

    public function updateOrder(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)->whereNull('deleted_at')->first();

        if (!$order) {
            return ApiHelper::sendError([], "Order not found.", 404);
        }

        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id,deleted_at,NULL',
            'product_id' => 'required|exists:products,id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            return ApiHelper::sendError($validator->errors(), "Validation Error", 422);
        }

        $order->update($request->all());

        return ApiHelper::sendResponse($order, 'Order updated successfully!!', 200);
    }

    public function deleteOrder($orderId)
    {
        $order = Order::find($orderId);

        if (!$order)
            return ApiHelper::sendError([], "Order not found.", 404);

        if ($order) {
            $order->delete();
            return ApiHelper::sendResponse($orderId, 'Order deleted successfully!', 202);
        }

        return ApiHelper::sendError($orderId, 'Error deleting Order. Order not found.', 404);
    }
}
