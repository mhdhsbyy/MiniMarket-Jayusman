<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\IncomingGood;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->get();
        $totalProduk = $suppliers->sum('products_count');

        return view('owner.products.index', compact('suppliers', 'totalProduk'));
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
        $nextKodes = $suppliers->mapWithKeys(fn ($s) => [$s->id => $this->generateProductKode($s)]);

        return view('owner.products.create', compact('categories', 'suppliers', 'nextKodes'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'harga_beli' => str_replace('.', '', $request->harga_beli),
            'harga_jual' => str_replace('.', '', $request->harga_jual),
        ]);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => [
                'required',
                'exists:suppliers,id',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $supplier = Supplier::find($value);
                    if ($supplier && $supplier->status !== 'active') {
                        $fail('Supplier yang dipilih tidak aktif.');
                    }
                },
            ],
            'nama' => [
                'required',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/',
            ],
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'satuan' => [
                'required',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'status' => 'required|in:active,inactive',
        ], [
            'category_id.required' => 'Kategori wajib di pilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'supplier_id.required' => 'Supplier wajib di pilih.',
            'supplier_id.exists' => 'Supplier tidak valid.',
            'nama.required' => 'Nama produk wajib di isi.',
            'nama.regex' => 'Nama produk harus karakter.',
            'harga_beli.required' => 'Harga beli wajib di isi.',
            'harga_beli.numeric' => 'Harga beli harus angka.',
            'harga_beli.min' => 'Harga beli tidak boleh negatif.',
            'harga_jual.required' => 'Harga jual wajib di isi.',
            'harga_jual.numeric' => 'Harga jual harus angka.',
            'harga_jual.min' => 'Harga jual tidak boleh negatif.',
            'satuan.required' => 'Satuan wajib di pilih.',
            'satuan.regex' => 'Satuan harus karakter.',
            'status.required' => 'Status wajib di pilih.',
        ]);

        $supplier = Supplier::findOrFail($request->supplier_id);
        $kode = $this->generateProductKode($supplier);

        Product::create([
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'kode' => $kode,
            'nama' => $request->nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'satuan' => $request->satuan,
            'status' => $request->status,
        ]);

        return redirect()->route('owner.products.supplier', $supplier)
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('nama', 'asc')->get();
        $suppliers = Supplier::where('status', 'active')
            ->orWhere('id', $product->supplier_id)
            ->orderBy('nama', 'asc')
            ->get();

        return view('owner.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->merge([
            'harga_beli' => str_replace('.', '', $request->harga_beli),
            'harga_jual' => str_replace('.', '', $request->harga_jual),
        ]);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => [
                'required',
                'exists:suppliers,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($product) {
                    $supplier = Supplier::find($value);
                    if ($supplier && $supplier->status !== 'active' && (int) $value !== (int) $product->supplier_id) {
                        $fail('Supplier yang dipilih tidak aktif.');
                    }
                },
            ],
            'nama' => [
                'required',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/',
            ],
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'satuan' => [
                'required',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'status' => 'required|in:active,inactive',
        ], [
            'category_id.required' => 'Kategori wajib di pilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'supplier_id.required' => 'Supplier wajib di pilih.',
            'supplier_id.exists' => 'Supplier tidak valid.',
            'nama.required' => 'Nama produk wajib di isi.',
            'nama.regex' => 'Nama produk harus karakter.',
            'harga_beli.required' => 'Harga beli wajib di isi.',
            'harga_beli.numeric' => 'Harga beli harus angka.',
            'harga_beli.min' => 'Harga beli tidak boleh negatif.',
            'harga_jual.required' => 'Harga jual wajib di isi.',
            'harga_jual.numeric' => 'Harga jual harus angka.',
            'harga_jual.min' => 'Harga jual tidak boleh negatif.',
            'satuan.required' => 'Satuan wajib di pilih.',
            'satuan.regex' => 'Satuan harus karakter.',
            'status.required' => 'Status wajib di pilih.',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'nama' => $request->nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'satuan' => $request->satuan,
            'status' => $request->status,
        ]);

        return redirect()->route('owner.products.supplier', Supplier::findOrFail($product->supplier_id))
            ->with('success', 'Produk berhasil diperbarui.');
    }

    private function generateProductKode(Supplier $supplier): string
    {
        $prefix = 'PRD-'.$supplier->kode;
        $lastProduct = Product::where('supplier_id', $supplier->id)
            ->where('kode', 'LIKE', $prefix.'-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->kode, strlen($prefix) + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix.'-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function destroy(Product $product)
    {
        $hasRelation = TransactionDetail::where('product_id', $product->id)->exists()
            || IncomingGood::where('product_id', $product->id)->exists()
            || Stock::where('product_id', $product->id)->exists();

        if ($hasRelation) {
            return redirect()->route('owner.products.supplier', $product->supplier_id)
                ->with('error', 'Produk tidak dapat dihapus karena sudah memiliki data transaksi, stok, atau barang masuk.');
        }

        $product->delete();

        return redirect()->route('owner.products.supplier', $product->supplier_id)
            ->with('success', 'Produk berhasil dihapus.');
    }
}
