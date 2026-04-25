<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('items')->latest();
        if ($request->filled('gudang') && $request->gudang !== 'universal') {
            $query->where('gudang', $request->gudang);
        }
        $categories = $query->paginate(15)->withQueryString();
        $activeGudang = $request->get('gudang', 'universal');
        
        return view('categories.index', compact('categories', 'activeGudang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('categories')->where('gudang', $request->gudang)],
            'gudang' => 'required|in:jakarta,bali,sfp',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index', ['gudang' => $request->gudang])->with('success', 'Kategori barang berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('categories')->ignore($category->id)->where('gudang', $category->gudang)],
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index', ['gudang' => $category->gudang])->with('success', 'Kategori barang berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        $gudang = $category->gudang;
        $category->delete();

        return redirect()->route('categories.index', ['gudang' => $gudang])->with('success', 'Kategori barang berhasil dihapus!');
    }
}
