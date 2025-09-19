<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Orders::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('trangthai', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Orders::findOrFail($id);

        $order->products = $this->ensureProductsArray($order->products);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'trangthai' => 'required|string'
        ]);

        $order = Orders::findOrFail($id);
        $order->update([
            'trangthai' => $request->trangthai,
            'updated_at' => now()
        ]);

        return redirect()->route('orders.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }

    public function destroy($id)
    {
        $order = Orders::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Xóa đơn hàng thành công');
    }

    private function ensureProductsArray($productsField): array
    {
        if (empty($productsField)) {
            return [];
        }

        if (is_array($productsField)) {
            return $productsField;
        }
        if (is_object($productsField)) {
            return (array) $productsField;
        }

        if (is_string($productsField)) {
            // 1) json_decode chuẩn
            $decoded = json_decode($productsField, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            $stripped = stripslashes($productsField);
            $decoded2 = json_decode($stripped, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded2)) {
                return $decoded2;
            }

            $maybe = @unserialize($productsField);
            if ($maybe !== false && is_array($maybe)) {
                return $maybe;
            }

            if (strpos($productsField, ':') !== false && strpos($productsField, ';') !== false) {
                $parts = explode(';', $productsField);
                $arr = [];
                foreach ($parts as $p) {
                    $p = trim($p);
                    if ($p === '') continue;
                    $pair = explode(':', $p);
                    $id = isset($pair[0]) ? (int)$pair[0] : null;
                    $qty = isset($pair[1]) ? (int)$pair[1] : 1;
                    $arr[] = ['id' => $id, 'quantity' => $qty];
                }
                return $arr;
            }

            return [];
        }

        return [];
    }
}
