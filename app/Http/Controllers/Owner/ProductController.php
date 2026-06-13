<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->get();

        return view('owner.products.index', compact('suppliers'));
    }

    public function supplierProducts(Supplier $supplier)
    {
        $products = Product::with(['category', 'supplier'])
            ->where('supplier_id', $supplier->id)
            ->oldest()
            ->get();

        return view('owner.products.supplier-products', compact('supplier', 'products'));
    }

    public function create()
    {
        $categories = Category::orderBy('nama', 'asc')->get();
        $suppliers = Supplier::where('status', 'active')->orderBy('nama', 'asc')->get();

        return view('owner.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'kode' => 'required|max:50|unique:products,kode',
            'nama' => 'required|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'satuan' => 'required|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        Product::create($request->all());

        return redirect()->route('owner.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('nama', 'asc')->get();
        $suppliers = Supplier::where('status', 'active')->orderBy('nama', 'asc')->get();

        return view('owner.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'kode' => 'required|max:50|unique:products,kode,' . $product->id,
            'nama' => 'required|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'satuan' => 'required|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($request->all());

        return redirect()->route('owner.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('owner.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
