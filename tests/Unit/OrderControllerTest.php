<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use App\Models\OrdersClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_list_orders_successfully()
    {
        $orderClient = OrdersClient::factory()->create();
        Order::factory()->count(3)->create(['order_id' => $orderClient->id]);

        $controller = new OrderController();
        $response = $controller->getListOrders();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(3, json_decode($response->getContent())->data);
    }

    public function test_create_order_successfully()
    {
        $client = Client::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $controller = new OrderController();
        $request = new Request([
            'client_id' => $client->id,
            'product_ids' => [$product1->id, $product2->id]
        ]);
        $response = $controller->createOrder($request);

        $this->assertEquals(201, $response->getStatusCode());

        $orderClient = OrdersClient::latest()->first();

        $this->assertDatabaseHas('orders', [
            'client_id' => $client->id,
            'product_id' => $product1->id,
            'order_id' => $orderClient->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'client_id' => $client->id,
            'product_id' => $product2->id,
            'order_id' => $orderClient->id,
        ]);
    }


    public function test_get_order_successfully()
    {
        $orderClient = OrdersClient::factory()->create();
        $order = Order::factory()->create(['order_id' => $orderClient->id]);

        $controller = new OrderController();
        $response = $controller->getOrder($order->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($order->id, json_decode($response->getContent())->data->id);
    }

    public function test_update_order_successfully()
    {
        $orderClient = OrdersClient::factory()->create();
        $order = Order::factory()->create(['order_id' => $orderClient->id]);

        $client = Client::factory()->create();
        $product = Product::factory()->create();

        $controller = new OrderController();
        $request = new Request(['client_id' => $client->id, 'product_id' => $product->id]);
        $response = $controller->updateOrder($request, $order->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'client_id' => $client->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_delete_order_successfully()
    {
        $orderClient = OrdersClient::factory()->create();
        $order = Order::factory()->create(['order_id' => $orderClient->id]);

        $controller = new OrderController();
        $response = $controller->deleteOrder($order->id);

        $this->assertNotNull(Order::withTrashed()->find($order->id)->deleted_at);
        $this->assertEquals(202, $response->getStatusCode());
    }
}
