<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $productData = $request->validate([
            'name' => 'required|min:5|max:200',
            'size' => 'required|in:S,M,L,XL,XXL,XXXL',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:1',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        $imageName = $request->file('image')->storeAs('/Product/Image', 'public');

        $product = Product::create([
            'name' => $productData['name'],
            'size' => $productData['size'],
            'price' => $productData['price'],
            'stock' => $productData['stock'],
            'image' => $imageName
        ]);

        if ($product) {
            return response()->json(['message' => 'Berhasil Menambahkan Produk Baru.'], 201);
        } else {
            return response()->json(['message' => 'Gagal menambahkan Produk.'], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        if ($request->hasFile('image')) {
            $productData = $request->validate([
                'name' => 'required|min:5|max:200',
                'size' => 'required|in:S,M,L,XL,XXL,XXXL',
                'price' => 'required|numeric|min:1',
                'stock' => 'required|numeric|min:1',
                'image' => 'required|image|mimes:png,jpg,jpeg'
            ]);

            Storage::disk('public')->delete($product->image);

            $imageName = $request->file('image')->storeAs('/Product/Image', 'public');

            $product->update([
                'name' => $productData['name'],
                'size' => $productData['size'],
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                'image' => $imageName
            ]);

            return response()->json(['message' => 'Berhasil Mengupdate Produk.'], 200);
        } else {
            $productData = $request->validate([
                'name' => 'required|min:5|max:200',
                'size' => 'required|in:S,M,L,XL,XXL,XXXL',
                'price' => 'required|numeric|min:1',
                'stock' => 'required|numeric|min:1',
            ]);

            if ($product->update($productData)) {
                return response()->json(['message' => 'Berhasil Mengupdate Produk.'], 200);
            } else {
                return response()->json(['message' => 'Gagal Mengupdate Produk.'], 500);
            }
        }
    }

    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return response()->json(['message' => 'Anda berhasil menghapus produk ini.'], 204);
        } else {
            return response()->json(['message' => 'Gagal menghapus produk ini.'], 500);
        }
    }
}
