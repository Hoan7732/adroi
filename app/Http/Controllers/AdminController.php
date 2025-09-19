<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pages;
use App\Models\Category;
use App\Http\Requests\ProductsRequest;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Pages::query();

        // Tìm kiếm theo tên hoặc thể loại
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

         $product = $query->orderBy('date', 'desc')->paginate(15);

        // Danh sách thể loại
        $categories = Category::all();

        return view('admin.admin', compact('product', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.create', compact('categories'));
    }

    public function store(ProductsRequest $request)
    {
        $request->validated();

        $pr = new Pages();
        $pr->name = $request->input('txtname');
        $pr->category = $request->input('txtcategory');
        $pr->date = $request->input('txtdate');
        $pr->gia = $request->input('txtgia');
        $pr->soluong = $request->input('txtsoluong');
        $pr->mota = $request->input('txtmota');
        $pr->cauhinhtt = $request->input('txtcauhinhtt');
        $pr->cauhinhdx = $request->input('txtcauhinhdx');

        if ($request->hasFile('txtanh')) {
            $file = $request->file('txtanh');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $pr->anh = $filename;
        }

        $pr->save();
        return redirect()->route('admin.index')->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit($id)
    {
        $product = Pages::find($id);
        $categories = Category::all();
        return view('admin.edit', compact('product', 'categories'));
    }

    public function update(ProductsRequest $request, $id)
    {
        $request->validated();

        $data = [
            'name' => $request->input('txtname'),
            'category' => $request->input('txtcategory'),
            'date' => $request->input('txtdate'),
            'gia' => $request->input('txtgia'),
            'soluong' => $request->input('txtsoluong'),
            'mota' => $request->input('txtmota'),
            'cauhinhtt' => $request->input('txtcauhinhtt'),
            'cauhinhdx' => $request->input('txtcauhinhdx'),
        ];

        if ($data['gia'] == 0 || $data['gia'] === '0') {
                $data['gia'] = 0;
            }


        if ($request->hasFile('txtanh')) {
            $file = $request->file('txtanh');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['anh'] = $filename;
        }

        Pages::where('id', $id)->update($data);

        return redirect()->route('admin.index')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy($id)
    {
        $product = Pages::find($id);
        if ($product) {
            $product->delete();
            return redirect()->route('admin.index')->with('success', 'Xóa sản phẩm thành công');
        } else {
            return redirect()->route('admin.index')->with('error', 'Sản phẩm không tồn tại');
        }
    }

    public function show($id)
    {
        $product = Pages::findOrFail($id);   // Lấy game theo id
        return view('admin.show', compact('product'));
    }
}
