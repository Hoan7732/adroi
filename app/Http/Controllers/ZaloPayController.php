<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;

class ZaloPayController extends Controller
{
    public function createOrder(Request $request, Orders $order = null)
    {
        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        $config = [
            "appid" => 553,
            "key1" => "9phuAOYhan4urywHTh0ndEXiV3pKHr5Q",
            "key2" => "Iyz2habzyr7AG8SgvoBCbKwKi3UzlLi3",
            "endpoint" => "https://sandbox.zalopay.com.vn/v001/tpe/createorder"
        ];

        $decodedProducts = json_decode($order->products, true) ?? [];
        $items = [];

        foreach ($decodedProducts as $productId => $item) {
            $items[] = [
                "itemid" => $productId,
                "itemname" => $item['name'] ?? 'Unknown',
                "itemprice" => $item['price'] ?? 0,
                "itemquantity" => $item['quantity'] ?? 1,
            ];
        }

        $embeddata = [
            "redirecturl" => route('order.details', ['id' => $order->id]),
            "order_id" => $order->id
        ];

        $zOrder = [
            "appid" => $config["appid"],
            "apptime" => round(microtime(true) * 1000),
            "apptransid" => date("ymd") . "_" . uniqid(),
            "appuser" => $order->email,
            "item" => json_encode($items, JSON_UNESCAPED_UNICODE),
            "embeddata" => json_encode($embeddata, JSON_UNESCAPED_UNICODE),
            "amount" => $order->total_amount,
            "description" => "Thanh toán đơn hàng #{$order->id}",
            "bankcode" => "zalopayapp"
        ];

        $data = $zOrder["appid"]."|".$zOrder["apptransid"]."|".$zOrder["appuser"]."|".$zOrder["amount"]
            ."|".$zOrder["apptime"]."|".$zOrder["embeddata"]."|".$zOrder["item"];
        $zOrder["mac"] = hash_hmac("sha256", $data, $config["key1"]);

        $response = \Http::asForm()->post($config["endpoint"], $zOrder);
        $result = $response->json();

        if (isset($result['orderurl'])) {
            return redirect()->away($result['orderurl']);
        }

        return redirect()->route('order.details', ['id' => $order->id])
                        ->with('error', 'Không tạo được đơn hàng ZaloPay');
    }


    public function callback(Request $request)
    {
        $configKey2 = "Iyz2habzyr7AG8SgvoBCbKwKi3UzlLi3";
        $data = $request->all();

        // Verify MAC
        $mac = hash_hmac("sha256", $data["data"], $configKey2);
        if ($mac != $data["mac"]) {
            return response()->json(['returncode' => -1, 'returnmessage' => 'Invalid MAC']);
        }

        $payload = json_decode($data["data"], true);
        $orderId = $payload["embeddata"]["order_id"] ?? null;

        if ($orderId) {
            $order = Orders::find($orderId);
            if ($order && $order->trangthai === 'pending') {
                $order->trangthai = 'paid';
                $order->save();
            }
        }

        return response()->json(['returncode' => 1, 'returnmessage' => 'OK']);
    }

}
