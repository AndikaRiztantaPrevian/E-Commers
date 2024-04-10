<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function getDataCart() {
        // Filtering cart data only show data where the user_id from table cart same like auth::user()->id (login user id)
        $cart = Cart::where('user_id', Auth::user()->id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menggambil data cart',
            'data' => $cart
        ], 200);
    }

    public function store(Request $request)
    {
        $cartData = Validator::make($request->all(), $this->validateCart());

        if($cartData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Ada kesalahan dalam validasi data keranjang yang dikirim',
                'errors' => $cartData->errors()
            ], 422);
        }

        $cart = new Cart();
        $cart->qty = $request->qty;
        $cart->product_id = $request->product_id;
        $cart->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan produk kedalam keranjang.',
            'data' => $cart
        ], 200);
    }

    public function update(Request $request, Cart $cart)
    {
        $cartData = Validator::make($request->all(), $this->validateCart());

        if($cartData->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Ada kesalahan dalam validasi data keranjang yang dikirim',
                'errors' => $cartData->errors()
            ], 422);
        }

        $cart->qty = $request->qty;
        $cart->update();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengupdate jumlah produk didalam keranjang.',
            'data' => $cart
        ], 200);
    }

    public function destroy(Cart $cart)
    {
        if ($cart->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus produk dari keranjang.'], 204);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus produk dari keranjang.'], 500);
        }
    }

    protected function validateCart()
    {
        return [
            'qty' => 'required|numeric',
            'product_id' => 'required',
        ];
    }
}
