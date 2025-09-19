<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('category');

        // Tìm kiếm theo tên thể loại
        if ($request->filled('search')) {
            $query->where('theloai_ct', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'theloai_ct' => 'required|string|max:255',
            'mota_ct' => 'nullable|string',
        ]);

        DB::table('category')->insert([
            'theloai_ct' => $request->theloai_ct,
            'mota_ct' => $request->mota_ct,
        ]);

        return redirect()->route('category.index')->with('success', 'Thêm danh mục thành công');
    }

    public function edit($id)
    {
        $category = DB::table('category')->where('id_ct', $id)->first();
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'theloai_ct' => 'required|string|max:255',
            'mota_ct' => 'nullable|string',
        ]);

        DB::table('category')->where('id_ct', $id)->update([
            'theloai_ct' => $request->theloai_ct,
            'mota_ct' => $request->mota_ct,
        ]);

        return redirect()->route('category.index')->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy($id)
    {
        DB::table('category')->where('id_ct', $id)->delete();
        return redirect()->route('category.index')->with('success', 'Xóa danh mục thành công');
    }
}
