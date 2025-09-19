<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PassRequest;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AccController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('admin.account', compact('user'));
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
            // Xóa avatar cũ nếu có
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Lưu trực tiếp vào public/images
            $file->move(public_path('images'), $filename);

            // Lưu đường dẫn tương đối để hiển thị
            $user->avatar = 'images/' . $filename;
        }

        $user->name = $request->input('name');

        $user->save();

        return redirect()->route('admin.account')->with('success', 'Profile updated successfully.');
    }

    public function changePassword(PassRequest $request)
    {
        $request->validated();

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Vui lòng đăng nhập để đổi mật khẩu.']);
        }

        if (!password_verify($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = bcrypt($request->input('new_password'));
        try {
            if ($user->save()) {
                return redirect()->route('admin.account')->with('success', 'Đổi mật khẩu thành công.');
            } else {
                \Log::error('Không thể lưu mật khẩu cho user ID: ' . $user->id);
                return redirect()->back()->withErrors(['error' => 'Không thể cập nhật mật khẩu. Vui lòng thử lại.']);
            }
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lưu mật khẩu: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
    }
}