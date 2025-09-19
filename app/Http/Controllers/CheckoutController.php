<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Pages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = session('cart', []);
        $total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cartItems));

        return view('guest.checkout', compact('user', 'cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^[0-9]{10,11}$/',
            'paymentMethod' => 'required|in:zalo,banking,visa',
        ]);

        $user = Auth::user();
        $cartItems = session('cart', []);
        $products = json_encode($cartItems);

        DB::beginTransaction();

        try {
            $order = new Orders();
            $order->full_name = $user->name;
            $order->email = $user->email;
            $order->phone = $request->phone;
            $order->payment_method = $request->paymentMethod;
            $order->total_amount = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cartItems));
            $order->products = $products;
            $order->trangthai = 'pending';
            $order->save();

            foreach ($cartItems as $productId => $item) {
                $product = Pages::findOrFail($productId);
                $newQuantity = $product->soluong - $item['quantity'];
                if ($newQuantity < 0) {
                    throw new \Exception("Số lượng không đủ cho sản phẩm ID: $productId");
                }
                $product->soluong = $newQuantity;
                $product->save();
            }

            DB::commit();
            session()->forget('cart');

            // 👉 Nếu là ZaloPay thì gọi API tạo đơn
            if ($order->payment_method === 'zalo') {
                return app(\App\Http\Controllers\ZaloPayController::class)
                    ->createOrder($request, $order);
            }

            return redirect()->route('order.details', ['id' => $order->id])
                            ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }

    // Helper method để decode products an toàn
    private function safeDecodeProducts($products)
    {
        if (is_array($products)) {
            return $products;
        }
        
        if (is_string($products)) {
            return json_decode($products, true) ?: [];
        }
        
        return [];
    }

    public function show($id)
    {
        $order = Orders::findOrFail($id);
        $products = $this->safeDecodeProducts($order->products);
        $orderItems = [];

        foreach ($products as $productId => $item) {
            $product = Pages::find($productId);
            if ($product) {
                $orderItems[] = [
                    'game' => [
                        'title' => $product->name,
                        'image' => $product->anh,
                    ],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ];
            }
        }

        $total = $order->total_amount;
        $tax = $total * 0.1;
        $grandTotal = $total + $tax;

        if (!($order->created_at instanceof \Carbon\Carbon)) {
            $order->created_at = \Carbon\Carbon::parse($order->created_at);
        }

        return view('guest.order-details', compact('order', 'orderItems', 'total', 'tax', 'grandTotal'));
    }

    public function refund($id)
    {
        $user = Auth::user();
        
        DB::beginTransaction();
        try {
            $order = Orders::where('id', $id)
                ->where('email', $user->email)
                ->where('trangthai', 'pending')
                ->firstOrFail();

            $order->trangthai = 'cancelled';
            $order->save();

            $products = $this->safeDecodeProducts($order->products);
            foreach ($products as $productId => $item) {
                $product = Pages::findOrFail($productId);
                $product->soluong += $item['quantity'];
                $product->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Hoàn tiền thành công!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Hoàn tiền thất bại: ' . $e->getMessage()], 400);
        }
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Orders::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(function ($order) {
                $products = $this->safeDecodeProducts($order->products);
                $orderItems = [];

                foreach ($products as $productId => $item) {
                    $product = Pages::find($productId);
                    if ($product) {
                        $orderItems[] = [
                            'game' => [
                                'title' => $product->name,
                                'image' => $product->anh,
                            ],
                            'price' => $item['price'],
                            'quantity' => $item['quantity'],
                        ];
                    }
                }
                $order->items = $orderItems;
                return $order;
            });

        return view('guest.orders', compact('orders'));
    }
}