<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Category;

class PagesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Pages::query();

        // Lọc theo danh mục
        if ($request->has('category') && $request->input('category') != '') {
            $query->where('category', $request->input('category'));
        }

        // Lọc theo khoảng giá
        if ($request->has('price_range') && $request->input('price_range') != '') {
            $priceRange = explode('-', $request->input('price_range'));
            if (count($priceRange) == 2) {
                $query->whereBetween('gia', [$priceRange[0], $priceRange[1]]);
            } elseif ($priceRange[0] == '2000000+') {
                $query->where('gia', '>=', 2000000);
            }
        }

        // Sắp xếp
        if ($request->has('sort_by')) {
            switch ($request->input('sort_by')) {
                case 'price_asc':
                    $query->orderBy('gia', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('gia', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('date', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'asc');
                    break;
            }
        }

        $product = $query->paginate(12);
        $moi = Pages::orderBy('date', 'desc')->take(4)->get();
        $random = Pages::inRandomOrder()->take(4)->get();

        return view('guest.index', compact('moi', 'product', 'random', 'categories'));
    }

    public function show($id)
    {
        $product = Pages::findOrFail($id);
        return view('guest.show', compact('product'));
    }
}