<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cart_items = [];

        if (is_array($cart)) {
            $isKeyedById = true;
            foreach ($cart as $k => $v) {
                if (!is_array($v) || (!isset($v['quantity']) && !isset($v['id']))) {
                    $isKeyedById = false;
                    break;
                }
            }

            if ($isKeyedById) {
                foreach ($cart as $id => $item) {
                    $product = Pages::find($id);
                    if ($product) {
                        $cart_items[$id] = [
                            'id' => $id,
                            'name' => $item['name'] ?? $product->name,
                            'price' => $item['price'] ?? $product->gia,
                            'image' => $item['image'] ?? $product->anh,
                            'quantity' => $item['quantity'] ?? 1,
                            'max_quantity' => $product->soluong,
                        ];
                    }
                }
            } else {
                // fallback: numeric array of items (cũ)
                foreach ($cart as $item) {
                    if (!is_array($item)) continue;
                    $id = $item['id'] ?? null;
                    if (!$id) continue;
                    $product = Pages::find($id);
                    if ($product) {
                        $cart_items[$id] = [
                            'id' => $id,
                            'name' => $item['name'] ?? $product->name,
                            'price' => $item['price'] ?? $product->gia,
                            'image' => $item['image'] ?? $product->anh,
                            'quantity' => $item['quantity'] ?? 1,
                            'max_quantity' => $product->soluong,
                        ];
                    }
                }
            }
        }

        $total = array_sum(array_map(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1), $cart_items));

        return view('guest.cart', compact('cart_items', 'total'));
    }

    public function add(Request $request)
    {
        $gameId = (string)$request->input('game_id');
        $product = Pages::findOrFail($gameId);

        // Kiểm tra nếu số lượng tồn kho bằng 0
        if ($product->soluong <= 0) {
            return redirect()->back()->with('error', 'Sản phẩm đã hết hàng!');
        }

        $cart = session()->get('cart', []);

        if (!is_array($cart)) $cart = [];

        if (isset($cart[$gameId])) {
            $cart[$gameId]['quantity']++;
        } else {
            $cart[$gameId] = [
                'quantity' => 1,
                'price' => $product->gia,
                'name' => $product->name,
                'image' => $product->anh,
            ];
        }

        if ($cart[$gameId]['quantity'] > $product->soluong) {
            $cart[$gameId]['quantity'] = $product->soluong;
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function remove(Request $request)
    {
        $gameId = (string)$request->input('game_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$gameId])) {
            unset($cart[$gameId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa khỏi giỏ hàng!');
    }

    public function updateQuantity(Request $request)
    {
        $gameId = (string)$request->input('game_id');
        $action = $request->input('action'); // increase/decrease
        $cart = session()->get('cart', []);
        $product = Pages::findOrFail($gameId);

        if (isset($cart[$gameId])) {
            if ($action === 'increase') {
                $cart[$gameId]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$gameId]['quantity']--;
            }

            // đảm bảo số lượng hợp lệ
            if ($cart[$gameId]['quantity'] < 1) {
                unset($cart[$gameId]);
            } elseif ($cart[$gameId]['quantity'] > $product->soluong) {
                $cart[$gameId]['quantity'] = $product->soluong;
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
    }
}
