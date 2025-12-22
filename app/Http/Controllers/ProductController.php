<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 🔹 GET: All Products
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Product::latest()->get()
        ]);
    }

    // 🔹 POST: Store Product
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric'
        ]);

        $product = Product::create(
            $request->only('name', 'detail', 'price')
        );

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    // 🔹 GET: Single Product
    public function show($id)
    {
        return response()->json([
            'status' => true,
            'data' => Product::findOrFail($id)
        ]);
    }

    // 🔹 PUT: Update Product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric'
        ]);

        $product = Product::findOrFail($id);

        $product->update(
            $request->only('name', 'detail', 'price')
        );

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    // 🔹 DELETE: Remove Product
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
