<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_list_products_successfully()
    {
        Product::factory()->count(3)->create();

        $controller = new ProductController();
        $response = $controller->getListProducts();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(3, $response->original['data']);
    }

    public function test_create_product_successfully()
    {
        Storage::fake('public'); 

        $photoPath = 'photos/test.jpg';
        Storage::put($photoPath, 'content');

        $request = new Request([
            'name' => 'Product 1',
            'price' => 100.00,
            'photo' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $controller = new ProductController();
        $response = $controller->createProduct($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Product created successfully!', $response->original['message']);
        $this->assertDatabaseHas('products', ['name' => 'Product 1']);
    }

    public function test_get_product_successfully()
    {
        $product = Product::factory()->create();

        $controller = new ProductController();
        $response = $controller->getProduct($product->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($product->id, $response->original['data']['id']);
    }

    public function test_update_product_successfully()
    {
        $product = Product::factory()->create();

        $request = new Request([
            'name' => 'Updated Product',
            'price' => 150.00,
            'photo' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $controller = new ProductController();
        $response = $controller->updateProduct($request, $product->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Product updated successfully!', $response->original['message']);
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
    }

    public function test_delete_product_successfully()
    {
        $product = Product::factory()->create();

        $controller = new ProductController();
        $response = $controller->deleteProduct($product->id);

        $this->assertNotNull(Product::withTrashed()->find($product->id)->deleted_at);
        $this->assertEquals(202, $response->getStatusCode());
    }
}
