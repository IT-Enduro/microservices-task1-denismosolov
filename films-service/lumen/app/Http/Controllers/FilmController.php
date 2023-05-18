<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page') ?? 0;
        $size = $request->query('size') ?? 100;

        $films = Film::paginate($size, ['*'], 'page', $page);

        return response()->json($films);
    }
}
