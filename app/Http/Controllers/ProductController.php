<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getListProducts()
    {
        $listProducts = Product::all()->whereNull('deleted_at');

        return ApiHelper::sendResponse($listProducts, 'Data returned successfully!', 200);
    }

    public function createProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return ApiHelper::sendError($validator->errors(), "Validation Error", 422);
        }

        $productData = $request->only(['name', 'price']);
        $product = Product::create($productData);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $directoryPath = public_path("photoProducts/{$product->id}");
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }

            $originalFileName = $photo->getClientOriginalName();

            try {
                $photo->move($directoryPath, $originalFileName);
                $product->photo = $originalFileName;
                $product->save();
            } catch (\Exception $e) {
                return ApiHelper::sendError([], "Error uploading image: " . $e->getMessage(), 500);
            }
        }

        return ApiHelper::sendResponse($product, 'Product created successfully!', 201);
    }

    public function getProduct($productId)
    {
        $product = Product::where('id', $productId)->whereNull('deleted_at')->first();

        if (!$product)
            return ApiHelper::sendError([], "Product not found.", 404);

        return ApiHelper::sendResponse($product, 'Data returned successfully!', 200);
    }

    public function updateProduct(Request $request, $productId)
    {
        $product = Product::where('id', $productId)->whereNull('deleted_at')->first();

        if (!$product) {
            return ApiHelper::sendError([], "Product not found.", 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return ApiHelper::sendError($validator->errors(), "Validation Error", 422);
        }

        $productData = $request->only(['name', 'price']);
        $product->update($productData);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $directoryPath = public_path("photoProducts/{$product->id}");
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }

            $originalFileName = $photo->getClientOriginalName();

            try {
                $photo->move($directoryPath, $originalFileName);
                $product->photo = $originalFileName;
                $product->save();
            } catch (\Exception $e) {
                return ApiHelper::sendError([], "Error uploading image: " . $e->getMessage(), 500);
            }
        }

        return ApiHelper::sendResponse($product, 'Product updated successfully!', 200);
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);

        if (!$product)
            return ApiHelper::sendError([], "Product not found.", 404);

        if ($product) {
            $product->delete();
            return ApiHelper::sendResponse($productId, 'Product deleted successfully!', 202);
        }

        return ApiHelper::sendError($productId, 'Error deleting Product. Product not found.', 404);
    }
}
