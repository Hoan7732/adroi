<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages; 

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $games = Pages::where('name', 'LIKE', "%{$query}%")
                         
                         ->paginate(12); 
        } else {
            $games = Pages::paginate(12); 
        }

        return view('guest.search', compact('games', 'query'));
    }
}