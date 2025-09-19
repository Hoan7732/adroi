<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->Where('email', 'like', '%' . $request->search . '%');
                  //->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by role only if a specific role is selected (not "Tất cả")
        if ($request->filled('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Filter by status only if a specific status is selected (not "Tất cả")
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $users = $query->select('id', 'name', 'email', 'status', 'role', 'avatar', 'created_at')
                    ->withCount('orders')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        return view('admin.users', compact('users'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,banned'
        ]);

        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật trạng thái thành công');
    }

    public function upgradeToAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->status === 'banned') {
            return redirect()->route('admin.users.index')->with('error', 'Không thể nâng cấp người dùng bị cấm thành admin');
        }
        $user->role = 'admin';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Nâng cấp thành admin thành công');
    }

    public function downgradeToUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = 'user';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Hủy quyền admin thành công');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->status === 'banned') {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng vĩnh viễn thành công');
        }
        return redirect()->route('admin.users.index')->with('error', 'Chỉ có thể xóa người dùng khi họ bị cấm');
    }
}