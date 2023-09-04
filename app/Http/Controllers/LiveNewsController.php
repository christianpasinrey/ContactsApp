<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LiveNewsController extends Controller
{
    public function index(Request $request)
    {
        $response = Http::get($request->url);
        $data = $response->json();
        return $data;
    }
}
