<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PassRequest;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('guest.account', compact('user'));
    }

    public function settings()
    {
        $user = auth()->user();
        return view('guest.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . auth()->id(), // Giữ để xác thực, nhưng không sử dụng
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $user->avatar = 'images/' . $filename;
        }

        $user->name = $request->input('name');
        $user->save();

        return redirect()->route('guest.account')->with('success', 'Cập nhật hồ sơ thành công.');
    }

    public function changePassword(PassRequest $request)
    {
        $request->validated();

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Vui lòng đăng nhập để đổi mật khẩu.']);
        }

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->input('new_password'));
        try {
            if ($user->save()) {
                return redirect()->route('guest.settings')->with('success', 'Đổi mật khẩu thành công.');
            } else {
                \Log::error('Không thể lưu mật khẩu cho user ID: ' . $user->id);
                return redirect()->back()->withErrors(['error' => 'Không thể cập nhật mật khẩu. Vui lòng thử lại.']);
            }
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lưu mật khẩu: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'promotion_notifications' => 'boolean',
            'order_notifications' => 'boolean',
        ]);

        $user = auth()->user();
        $user->email_notifications = $request->input('email_notifications', 0);
        $user->promotion_notifications = $request->input('promotion_notifications', 0);
        $user->order_notifications = $request->input('order_notifications', 0);
        $user->save();

        return redirect()->route('guest.settings')->with('success', 'Cập nhật cài đặt thông báo thành công.');
    }
}