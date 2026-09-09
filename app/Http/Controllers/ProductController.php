<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 🔹 GET: All Products + Search + Price Filter + Pagination
    public function index(Request $request)
    {
        $query = Product::query();

        // 🔎 Search by product name or detail
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('detail', 'like', '%' . $search . '%');
            });
        }

        // 💰 Minimum price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // 💰 Maximum price filter
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 📄 Pagination
        $products = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return response()->json([
            'status' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ]
        ]);
    }


    // 🔹 GET: Product Statistics
    public function statistics()
    {
        $totalProducts = Product::count();

        $averagePrice = Product::avg('price');
        $highestPrice = Product::max('price');
        $lowestPrice = Product::min('price');

        return response()->json([
            'status' => true,
            'data' => [
                'total_products' => $totalProducts,
                'average_price' => round($averagePrice ?? 0, 2),
                'highest_price' => $highestPrice ?? 0,
                'lowest_price' => $lowestPrice ?? 0,
            ]
        ]);
    }


    // 🔹 POST: Store Product
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
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
            'price' => 'required|numeric|min:0'
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