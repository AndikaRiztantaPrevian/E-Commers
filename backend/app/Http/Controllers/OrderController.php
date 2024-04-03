<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $orderData = $request->validate([
            'product_id' => 'required',
            'qty' => 'required|numeric',
            'size' => 'required|in:S,M,L,XL,XXL,XXXL',
            'total_price' => 'required|integer',
            'estimate_arrived' => 'required',
        ]);

        $productId = Product::findOrFail($orderData['product_id']);

        $total_price = $productId->price * $orderData['qty'];

        $order = Order::create([
            'qty' => $orderData['qty'],
            'size' => $orderData['size'],
            'status' => 'Menunggu_Pembayaran',
            'address' => Auth::user()->address,
            'total_price' => $total_price,
            'date_order' => Carbon::now(),
            'estimate_arrived' => $orderData['estimate_arrived'],
            'product_id' => $orderData['product_id'],
            'user_id' => Auth::user()->id,
        ]);

        if ($order) {
            return response()->json(['message' => 'Berhasil membuat pesanan.'], 201);
        } else {
            return response()->json(['message' => 'Gagal membuat pesanan.'], 500);
        }
    }

    public function update(Request $request, Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        if($order->delete()) {
            return response()->json(['message' => 'Berhasil membatalkan pesanan'], 204);
        } else {
            return response()->json(['message' => 'Gagal membatalkan pesanan'], 500);
        }
    }
}
