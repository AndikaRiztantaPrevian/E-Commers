<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Process call data from table product
    public function getDataProduct(Request $request)
    {
        if ($request->has('search')) {
            // Handle filtering data by product name
            $searchData = $request->search;
            $product = Product::where('name', 'like', '%' . $searchData . '%')->get();

            if ($product->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mencari produk berdasarkan yang dicari.',
                    'data' => []
                ], 404);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil mencari produk berdasarkan yang dicari.',
                    'data' => $product
                ], 200);
            }
        } elseif ($request->category) {
            // Handle filtering data by category
            $product = Product::where('categories_id', $request->category)->get();

            if ($product->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Produk dengan kategori tersebut tidak ditemukan',
                    'data' => []
                ], 404);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil mengambil data dari produk.',
                    'data' => $product
                ], 200);
            }
        } else {
            // Product data without filtering the data
            $product = Product::all();

            if ($product->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data dari produk masih belum ada.',
                    'data' => []
                ], 404);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil mengambil data dari produk.',
                    'data' => $product
                ], 200);
            }
        }
    }

    // Process Store Product
    public function store(Request $request)
    {
        // Validation for data product
        $productData = Validator::make($request->all(), $this->validateProduct());

        // Catch if validation failed
        if ($productData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data yang dimasukkan tidak lengkap / ada kesalahan',
                'errors' => $productData->errors()
            ], 422);
        }

        // Create folder in public storage for imaege & get the name after the process create a random name for the image product
        $imageFileStore = $request->file('image')->storeAs('/Product/Image', 'public');
        $imageUrl = Storage::url($imageFileStore);

        // Process entry data to Product Table
        $product = new Product();
        $product->name = $request->name;
        $product->size = $request->size;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->image = $imageUrl;
        $product->save();

        // Response if product successfully created
        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menambahkan Produk Baru.',
            'data' => $product
        ], 201);
    }

    // Process Update Product
    public function update(Request $request, Product $product)
    {
        if ($request->hasFile('image') && $request->hasFile('image') != $product->image) {
            // Validation for data product
            $productData = Validator::make($request->all(), $this->validateProduct());

            // Catch if validation failed
            if ($productData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data yang dimasukkan tidak lengkap / ada kesalahan pada data yang dikirimkan',
                    'errors' => $productData->errors()
                ], 422);
            }

            // Delete old image from public storage
            Storage::disk('public')->delete($product->image);

            // Create folder in public storage for imaege & get the name after the process create a random name for the image product
            $imageFileStore = $request->file('image')->storeAs('/Product/Image', 'public');
            $imageUrl = Storage::url($imageFileStore);

            // Process update data product
            $product->name = $request->name;
            $product->size = $request->size;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->image = $imageUrl;
            $product->update();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Mengupdate Produk.',
                'data' => $product
            ], 200);
        } elseif ($request->image->isEmpty()) {
            // If image is empty, the process only update all data without image

            // Validation for data product
            $productData = Validator::make($request->all(), $this->validateProductWithOutImage());

            // Catch if validation failed
            if($productData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data yang dimasukkan tidak lengkap / ada kesalahan pada data yang dikirimkan',
                    'erros' => $productData->errors()
                ], 422);
            }

            // Get old location image
            $productImage = $product->image;

            // Process update data product
            $product->name = $request->name;
            $product->size = $request->size;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->image = $productImage;
            $product->update();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Mengupdate Produk.',
                'data' => $product
            ], 200);
        } elseif ($request->all() == $product) {
            // If all request data same like product data will return this response and no data updated
            return response()->json([
                'status' => true,
                'message' => 'Tidak merubah apapun dari data product.',
                'data' => $product
            ], 200);
        }
    }

    // Process Delete Product
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

    // Validation Product
    protected function validateProduct()
    {
        return [
            'name' => 'required|min:5|max:200',
            'size' => 'required|in:S,M,L,XL,XXL,XXXL',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:1',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ];
    }

    // Validation Product Without Image
    protected function validateProductWithOutImage()
    {
        return [
            'name' => 'required|min:5|max:200',
            'size' => 'required|in:S,M,L,XL,XXL,XXXL',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:1',
        ];
    }
}
