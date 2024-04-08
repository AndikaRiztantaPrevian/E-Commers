<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $categoryData = $this->validateCategory($request);

        if (Category::create($categoryData)) {
            return response()->json(['message' => 'Berhasil membuat kategori baru.'], 201);
        } else {
            return response()->json(['message' => 'Gagal membuat kategori baru.'], 500);
        }
    }

    public function update(Request $request, Category $category)
    {
        if ($request->name == $category->name) {
            return response()->json(['message' => 'Anda tidak merubah apapun.'], 200);
        } else {
            $categoryData = $this->validateCategory($request);

            if ($category->update($categoryData)) {
                return response()->json(['message' => 'Berhasil merubah kategori.'], 200);
            } else {
                return response()->json(['message' => 'Gagal merubah kategori.'], 500);
            }
        }
    }

    public function destroy(Category $category)
    {
        if ($category->delete()) {
            return response()->json(['message' => 'Berhasil menghapus kategori.'], 204);
        } else {
            return response()->json(['message' => 'Gagal menghapus kategori.'], 500);
        }
    }

    protected function validateCategory(Request $request)
    {
        return $request->validate([
            'name' => 'required',
        ]);
    }
}
