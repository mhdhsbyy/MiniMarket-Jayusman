<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::oldest()->get();

        return view('owner.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('owner.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => [
                'required',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],
        ], [
            'nama.required' => 'Nama kategori wajib di isi.',
            'nama.regex' => 'Nama kategori harus karakter.',
        ]);

        Category::create($request->only('nama'));

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('owner.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama' => [
                'required',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],
        ], [
            'nama.required' => 'Nama kategori wajib di isi.',
            'nama.regex' => 'Nama kategori harus karakter.',
        ]);

        $category->update($request->only('nama'));

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if (Product::where('category_id', $category->id)->exists()) {
            return redirect()->route('owner.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->route('owner.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
