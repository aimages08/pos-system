<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'nullable|exists:categories,id',
            'unit'            => 'nullable|string|max:50',
            'sku'             => 'nullable|string|max:100|unique:products,sku',
            'barcode'         => 'nullable|string|max:100|unique:products,barcode',
            'description'     => 'nullable|string',
            'cost_price'      => 'required|numeric|min:0',
            'selling_price'   => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'minimum_stock'   => 'required|integer|min:0',
            'expiry_date'     => 'nullable|date',
            'tax'             => 'nullable|numeric|min:0|max:100',
            'discount'        => 'nullable|numeric|min:0|max:100',
            'image'           => 'nullable|image|max:2048',
            'status'          => 'required|in:active,disabled',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect('/products')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'nullable|exists:categories,id',
            'unit'            => 'nullable|string|max:50',
            'sku'             => 'nullable|string|max:100|unique:products,sku,' . $id,
            'barcode'         => 'nullable|string|max:100|unique:products,barcode,' . $id,
            'description'     => 'nullable|string',
            'cost_price'      => 'required|numeric|min:0',
            'selling_price'   => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'minimum_stock'   => 'required|integer|min:0',
            'expiry_date'     => 'nullable|date',
            'tax'             => 'nullable|numeric|min:0|max:100',
            'discount'        => 'nullable|numeric|min:0|max:100',
            'image'           => 'nullable|image|max:2048',
            'status'          => 'required|in:active,disabled',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect('/products')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete image file
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect('/products')->with('success', 'Product deleted successfully.');
    }

    /**
     * Lookup product by barcode (used by POS scanner).
     */
    public function byBarcode($code)
    {
        $product = Product::where('barcode', $code)
            ->orWhere('sku', $code)
            ->first();

        if (!$product) {
            return response()->json(['found' => false], 404);
        }

        return response()->json([
            'found' => true,
            'product' => [
                'id'            => $product->id,
                'name'          => $product->name,
                'sku'           => $product->sku,
                'barcode'       => $product->barcode,
                'selling_price' => (float) $product->selling_price,
                'stock'         => (int) $product->stock,
                'expiry_date'   => $product->expiry_date?->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Check if a barcode is already used by another product.
     */
    public function checkBarcode(Request $request, $code)
    {
        $query = Product::where('barcode', $code);

        if ($request->has('exclude')) {
            $query->where('id', '!=', $request->exclude);
        }

        $existing = $query->first();

        if ($existing) {
            return response()->json([
                'exists'  => true,
                'product' => ['id' => $existing->id, 'name' => $existing->name],
            ]);
        }

        return response()->json(['exists' => false]);
    }
}