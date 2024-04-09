<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $productData = $this->validateProduct($request);

        $imageName = $request->file('image')->storeAs('/Product/Image', 'public');

        $product = Product::create([
            'name' => $productData['name'],
            'size' => $productData['size'],
            'price' => $productData['price'],
            'stock' => $productData['stock'],
            'image' => $imageName
        ]);

        if ($product) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menambahkan Produk Baru.'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan Produk.'
            ], 422);
        }
    }

    public function update(Request $request, Product $product)
    {
        if ($request->hasFile('image')) {
            $productData = $this->validateProduct($request);

            Storage::disk('public')->delete($product->image);

            $imageName = $request->file('image')->storeAs('/Product/Image', 'public');

            $product->update([
                'name' => $productData['name'],
                'size' => $productData['size'],
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                'image' => $imageName
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Mengupdate Produk.'
            ], 200);
        } else {
            $productData = $this->validateProductWithOutImage($request);

            if ($product->update($productData)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil mengupdate produk.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal Mengupdate Produk.'
                ], 422);
            }
        }
    }

    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Anda berhasil menghapus produk.'
            ], 204);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus produk.'
            ], 422);
        }
    }

    protected function validateProduct(Request $request)
    {
        return $request->validate([
            'name' => 'required|min:5|max:200',
            'size' => 'required|in:S,M,L,XL,XXL,XXXL',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:1',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);
    }

    protected function validateProductWithOutImage(Request $request)
    {
        return $request->validate([
            'name' => 'required|min:5|max:200',
            'size' => 'required|in:S,M,L,XL,XXL,XXXL',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:1',
        ]);
    }
}
