<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        // Validation for category
        $categoryData = Validator::make($request->all(), $this->validateCategory());

        // Catch if validation fails
        if ($categoryData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data kategori tidak valid',
                'errors' => $categoryData->errors()
            ], 422);
        }

        // Process entry data to Category
        $category = new Category();
        $category->name = $request->name;
        $category->save();

        // Response if product successfully created
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambah kategori',
            'data' => $category
        ], 200);
    }

    public function update(Request $request, Category $category)
    {
        // Validation for category
        $categoryData = Validator::make($request->all(), $this->validateCategory());

        // Catch if validation fails
        if ($categoryData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data kategori tidak valid',
                'errors' => $categoryData->errors()
            ], 422);
        }

        // Response if request name same like data from name category
        if ($request->name == $category->name) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak merubah data apapun'
            ], 204);
        }

        // Process update data from category
        $category->name = $request->name;
        $category->update();

        // Response if data successfully updated
        return response()->json([
            'status' => true,
            'message' => 'Berhasil memperbarui nama kategori',
            'data' => $category
        ], 200);
    }

    public function destroy(Category $category)
    {
        if ($category->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus kategori.'
            ], 204);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus kategori.'
            ], 422);
        }
    }

    // Validation for category
    protected function validateCategory()
    {
        return [
            'name' => 'required',
        ];
    }
}
