<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /**
     * GET: All Products
     *
     * Features:
     * - Search
     * - Minimum price
     * - Maximum price
     * - Sorting
     * - Pagination
     * - Per page
     * - Status filter
     */
    public function index(Request $request)
    {
        $query = Product::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('detail', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $status = $request->status;

            if (in_array($status, ['active', 'inactive'])) {
                $query->where('status', $status);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSortColumns = [
            'id',
            'name',
            'price',
            'created_at',
        ];

        $sortBy = $request->get('sort_by', 'id');

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'id';
        }

        $sortOrder = strtolower(
            $request->get('sort_order', 'asc')
        );

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);

        /*
        |--------------------------------------------------------------------------
        | Per Page
        |--------------------------------------------------------------------------
        */

        $perPage = (int) $request->get('per_page', 5);

        $allowedPerPage = [5, 10, 20, 50];

        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->paginate($perPage)
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
            ],

            'sorting' => [
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
        ]);
    }


    /**
     * GET: Product Statistics
     */
    public function statistics()
    {
        $totalProducts = Product::count();

        $activeProducts = Product::where(
            'status',
            'active'
        )->count();

        $inactiveProducts = Product::where(
            'status',
            'inactive'
        )->count();

        $trashedProducts = Product::onlyTrashed()->count();

        $averagePrice = Product::avg('price');

        $highestPrice = Product::max('price');

        $lowestPrice = Product::min('price');

        return response()->json([
            'status' => true,

            'data' => [
                'total_products' => $totalProducts,
                'active_products' => $activeProducts,
                'inactive_products' => $inactiveProducts,
                'trashed_products' => $trashedProducts,
                'average_price' => round(
                    $averagePrice ?? 0,
                    2
                ),
                'highest_price' => $highestPrice ?? 0,
                'lowest_price' => $lowestPrice ?? 0,
            ]
        ]);
    }


    /**
     * POST: Store Product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'detail' => 'nullable|string',

            'price' => 'required|numeric|min:0',

            'status' => 'nullable|in:active,inactive',
        ]);

        $product = Product::create([
            'name' => $validated['name'],

            'detail' => $validated['detail'] ?? null,

            'price' => $validated['price'],

            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'status' => true,

            'message' => 'Product created successfully',

            'data' => $product
        ], 201);
    }


    /**
     * GET: Single Product
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'status' => true,

            'data' => $product
        ]);
    }


    /**
     * PUT: Update Product
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'detail' => 'nullable|string',

            'price' => 'required|numeric|min:0',

            'status' => 'nullable|in:active,inactive',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $validated['name'],

            'detail' => $validated['detail'] ?? null,

            'price' => $validated['price'],

            'status' => $validated['status']
                ?? $product->status,
        ]);

        return response()->json([
            'status' => true,

            'message' => 'Product updated successfully',

            'data' => $product->fresh()
        ]);
    }


    /**
     * PATCH: Toggle Product Status
     *
     * Active <-> Inactive
     */
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);

        $product->status =
            $product->status === 'active'
                ? 'inactive'
                : 'active';

        $product->save();

        return response()->json([
            'status' => true,

            'message' => 'Product status updated successfully',

            'data' => $product
        ]);
    }


    /**
     * DELETE: Soft Delete Product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            'status' => true,

            'message' => 'Product moved to trash successfully'
        ]);
    }


    /**
     * GET: Trash Products
     */
    public function trash(Request $request)
    {
        $products = Product::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => true,

            'data' => $products->items(),

            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }


    /**
     * POST: Restore Product
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);

        $product->restore();

        return response()->json([
            'status' => true,

            'message' => 'Product restored successfully',

            'data' => $product->fresh()
        ]);
    }


    /**
     * DELETE: Permanently Delete Product
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);

        $product->forceDelete();

        return response()->json([
            'status' => true,

            'message' => 'Product permanently deleted'
        ]);
    }


    /**
     * POST: Bulk Delete Products
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',

            'ids.*' => 'integer|exists:products,id',
        ]);

        $count = Product::whereIn(
            'id',
            $validated['ids']
        )->delete();

        return response()->json([
            'status' => true,

            'message' =>
                $count . ' product(s) moved to trash successfully',

            'deleted_count' => $count
        ]);
    }


    /**
     * GET: Export Products CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = Product::query();

        /*
        |--------------------------------------------------------------------------
        | Same Search Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('detail', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Price Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {
            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }

        if ($request->filled('max_price')) {
            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            if (
                in_array(
                    $request->status,
                    ['active', 'inactive']
                )
            ) {
                $query->where(
                    'status',
                    $request->status
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSortColumns = [
            'id',
            'name',
            'price',
            'created_at',
        ];

        $sortBy = $request->get('sort_by', 'id');

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'id';
        }

        $sortOrder = strtolower(
            $request->get('sort_order', 'asc')
        );

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);

        $fileName =
            'products-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        return response()->streamDownload(
            function () use ($query) {

                $handle = fopen('php://output', 'w');

                /*
                |--------------------------------------------------------------------------
                | CSV Header
                |--------------------------------------------------------------------------
                */

                fputcsv($handle, [
                    'ID',
                    'Name',
                    'Detail',
                    'Price',
                    'Status',
                    'Created At',
                ]);

                /*
                |--------------------------------------------------------------------------
                | CSV Data
                |--------------------------------------------------------------------------
                */

                $query->chunk(500, function ($products) use ($handle) {

                    foreach ($products as $product) {

                        fputcsv($handle, [
                            $product->id,
                            $product->name,
                            $product->detail,
                            $product->price,
                            $product->status,
                            $product->created_at,
                        ]);
                    }
                });

                fclose($handle);
            },

            $fileName,

            [
                'Content-Type' =>
                    'text/csv; charset=UTF-8',
            ]
        );
    }
}