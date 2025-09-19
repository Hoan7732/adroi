<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Pages;
use App\Models\User;
use Carbon\Carbon;

class AHomeController extends Controller
{
    public function index()
    {
        // Tổng doanh thu: chỉ tính những đơn được coi là "hoàn thành"
        $totalRevenue = Orders::all()
            ->filter(fn($o) => $this->isCompletedStatus($o->trangthai))
            ->sum(fn($o) => (int)$o->total_amount);

        // Đơn hàng hôm nay
        $todayOrders = Orders::whereDate('created_at', Carbon::today())->count();

        // Tổng số game
        $totalGames = Pages::count();

        // Tổng users (loại trừ admin)
        $totalUsers = User::where('role', '!=', 'admin')->count();

        // Lấy 5 đơn gần nhất
        $recentOrders = Orders::orderBy('created_at', 'desc')->take(5)->get();

        // --- Gom product IDs từ recentOrders để query 1 lần
        $allProductIds = [];
        $ordersProductsDecoded = [];

        foreach ($recentOrders as $order) {
            $products = $this->decodeProducts($order->products);
            $ordersProductsDecoded[$order->id] = $products;

            if (is_array($products)) {
                foreach ($products as $p) {
                    if (!empty($p['id'])) $allProductIds[] = (int)$p['id'];
                    elseif (!empty($p['product_id'])) $allProductIds[] = (int)$p['product_id'];
                    elseif (!empty($p['pid'])) $allProductIds[] = (int)$p['pid'];
                }
            }
        }

        $productMap = [];
        if (!empty($allProductIds)) {
            $allProductIds = array_values(array_unique(array_filter($allProductIds)));
            $pages = Pages::whereIn('id', $allProductIds)->get(['id', 'name']);
            foreach ($pages as $p) {
                $productMap[(int)$p->id] = $p->name;
            }
        }

        // Gắn product_names cho từng order
        foreach ($recentOrders as $order) {
            $products = $ordersProductsDecoded[$order->id] ?? [];
            $names = [];

            if (is_array($products)) {
                foreach ($products as $p) {
                    if (!empty($p['name'])) {
                        $names[] = $p['name'];
                        continue;
                    }
                    if (!empty($p['title'])) {
                        $names[] = $p['title'];
                        continue;
                    }

                    $id = null;
                    if (!empty($p['id'])) $id = (int)$p['id'];
                    elseif (!empty($p['product_id'])) $id = (int)$p['product_id'];
                    elseif (!empty($p['pid'])) $id = (int)$p['pid'];

                    if ($id !== null && isset($productMap[$id])) {
                        $names[] = $productMap[$id];
                        continue;
                    }

                    if ($id !== null) $names[] = "Sản phẩm #{$id}";
                    else $names[] = 'Unknown';
                }
            }

            $names = array_values(array_unique(array_filter(array_map('trim', $names))));
            if (empty($names)) $names = ['Unknown'];

            $order->product_names = $names;
        }

        $formattedRevenue = number_format((int)$totalRevenue, 0, ',', '.') . ' VNĐ';

        return view('admin.home', compact(
            'formattedRevenue',
            'todayOrders',
            'totalGames',
            'totalUsers',
            'recentOrders'
        ));
    }

    // API: doanh thu 12 tháng gần nhất
    public function revenueChart()
    {
        $labels = [];
        $dataSet = [];

        $months = collect(range(0, 11))->map(fn($offset) => Carbon::now()->subMonths($offset))->reverse();

        foreach ($months as $month) {
            $labels[] = $month->format('M');
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $revenue = Orders::whereBetween('created_at', [$start, $end])->get()
                ->filter(fn($o) => $this->isCompletedStatus($o->trangthai))
                ->sum(fn($o) => (int)$o->total_amount);

            $dataSet[] = (int)$revenue;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Doanh thu',
                    'data' => $dataSet,
                    'borderColor' => '#2563eb',
                    'fill' => false
                ]
            ]
        ]);
    }

    // API: top games tuần này (dựa trên quantity)
    public function topGamesChart()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $orders = Orders::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();

        $games = [];
        foreach ($orders as $order) {
            if (!$this->isCompletedStatus($order->trangthai)) continue;

            $products = $this->decodeProducts($order->products);
            if (!is_array($products)) continue;

            foreach ($products as $product) {
                $name = $product['name'] ?? $product['title'] ?? null;
                $qty = isset($product['quantity']) ? (int)$product['quantity'] : (isset($product['qty']) ? (int)$product['qty'] : 0);

                if (!$name) {
                    // try map by id via Pages table if id exists (less optimal here – we skip)
                    continue;
                }
                if ($qty <= 0) $qty = 1;

                $games[$name] = ($games[$name] ?? 0) + $qty;
            }
        }

        arsort($games);
        $top = array_slice($games, 0, 5, true);

        return response()->json([
            'labels' => array_keys($top),
            'datasets' => [
                [
                    'label' => 'Số lượng bán',
                    'data' => array_values($top),
                    'backgroundColor' => ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
                ]
            ]
        ]);
    }

    /**
     * Decode products field an toàn.
     */
    private function decodeProducts($productsField)
    {
        if (is_array($productsField)) return $productsField;
        if (empty($productsField)) return [];

        if (is_object($productsField)) return (array)$productsField;

        if (is_string($productsField)) {
            $decoded = json_decode($productsField, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return $decoded;

            $stripped = stripslashes($productsField);
            $decoded2 = json_decode($stripped, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded2)) return $decoded2;

            $maybe = @unserialize($productsField);
            if ($maybe !== false && is_array($maybe)) return $maybe;

            if (strpos($productsField, ':') !== false && strpos($productsField, ';') !== false) {
                $parts = explode(';', $productsField);
                $arr = [];
                foreach ($parts as $p) {
                    if (trim($p) === '') continue;
                    [$id, $qty] = array_pad(explode(':', $p), 2, 1);
                    $arr[] = ['id' => (int)$id, 'quantity' => (int)$qty];
                }
                return $arr;
            }

            return [];
        }

        return [];
    }

    /**
     * Kiểm tra trạng thái "hoàn thành"
     */
    private function isCompletedStatus($status): bool
    {
        if (empty($status)) return false;
        $s = mb_strtolower(trim($status));
        $keywords = [
            'hoàn', 'hoan', 'complete', 'completed', 'paid',
            'đã thanh toán', 'da thanh toan', 'thanh toán', 'thanh toan',
            'thành công', 'thanh cong', 'delivered'
        ];
        foreach ($keywords as $kw) {
            if (mb_stripos($s, $kw) !== false) return true;
        }
        return false;
    }
}
