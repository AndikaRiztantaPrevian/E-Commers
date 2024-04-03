<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $cartData = $request->validate([
            'qty' => 'required|numeric',
            'product_id' => 'required',
        ]);

        $cart = Cart::create($cartData);

        if ($cart) {
            return response()->json(['message' => 'Berhasil Menambah Produk Ke Keranjang.'], 201);
        } else {
            return response()->json(['message' => 'Gagal Menambah Produk Ke Keranjang.'], 500);
        }
    }

    public function update(Request $request, Cart $cart)
    {
        $cartData = $request->validate([
            'qty' => 'required|numeric',
            'product_id' => 'required'
        ]);

        $cartUpdate = $cart->update([
            'qty' => $cartData['qty']
        ]);

        if ($cartUpdate) {
            return response()->json(['message' => 'Berhasil memperbarui jumlah produk.'], 200);
        } else {
            return response()->json(['message' => 'Gagal memperbarui jumlah produk.'], 500);
        }
    }

    public function destroy(Cart $cart)
    {
        if ($cart->delete()) {
            return response()->json(['message' => 'Berhasil menghapus produk dari keranjang.'], 204);
        } else {
            return response()->json(['message' => 'Gagal menghapus produk dari keranjang.'], 500);
        }
    }
}
