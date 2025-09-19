<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages; // model của bảng product

class WishlistController extends Controller
{
    // Hiển thị danh sách yêu thích
    public function index()
    {
        $ids = session('wishlist', []); // mảng id product
        $games = $ids ? Pages::whereIn('id', $ids)->get() : collect();
        return view('guest.wishlist', compact('games'));
    }

    // Thêm 1 game vào wishlist
    public function add(Request $request)
    {
        $id = intval($request->input('game_id'));
        if (!Pages::find($id)) {
            return back()->with('error', 'Sản phẩm không tồn tại.');
        }

        $wishlist = session('wishlist', []);
        if (!in_array($id, $wishlist)) {
            $wishlist[] = $id;
            session(['wishlist' => $wishlist]);
            return back()->with('success', 'Đã thêm vào danh sách yêu thích.');
        }

        return back()->with('info', 'Sản phẩm đã có trong danh sách yêu thích.');
    }

    // Xóa 1 game khỏi wishlist
    public function remove(Request $request)
    {
        $id = intval($request->input('game_id'));
        $wishlist = session('wishlist', []);
        $wishlist = array_values(array_diff($wishlist, [$id]));
        session(['wishlist' => $wishlist]);
        return back()->with('success', 'Đã xóa khỏi danh sách yêu thích.');
    }

    // Xóa tất cả
    public function clear(Request $request)
    {
        session(['wishlist' => []]);
        return back()->with('success', 'Đã xóa tất cả khỏi danh sách yêu thích.');
    }

    // Thêm tất cả game trong wishlist vào giỏ (session-based cart)
    public function addAllToCart(Request $request)
    {
        $wishlist = session('wishlist', []);
        if (empty($wishlist)) {
            return back()->with('error', 'Danh sách yêu thích trống!');
        }

        $cart = session('cart', []);
        if (!is_array($cart)) {
            $cart = [];
        }

        $addedNew = 0;   
        $increased = 0;   
        $games = Pages::whereIn('id', $wishlist)->get();

        foreach ($games as $g) {
            $gid = (string)$g->id; 

            if (isset($cart[$gid])) {
                $currentQty = $cart[$gid]['quantity'] ?? 0;
                $newQty = min($currentQty + 1, max(0, (int)$g->soluong));
                if ($newQty > $currentQty) {
                    $cart[$gid]['quantity'] = $newQty;
                    $increased++;
                }
            } else {
                if ((int)$g->soluong <= 0) {
                    continue;
                }

                $cart[$gid] = [
                    'quantity' => 1,
                    'price'    => $g->gia,
                    'name'     => $g->name,   // dùng key 'name' để thống nhất
                    'image'    => $g->anh,
                ];
                $addedNew++;
            }
        }

        session(['cart' => $cart]);

        if ($addedNew > 0 || $increased > 0) {
            $msgParts = [];
            if ($addedNew > 0) $msgParts[] = "Đã thêm {$addedNew} game vào giỏ hàng";
            if ($increased > 0) $msgParts[] = "Đã có {$increased} game trong giỏ hàng";
            return back()->with('success', implode(' — ', $msgParts) . '!');
        }

        return back()->with('info', 'Game đã hết hàng.');
    }
}
