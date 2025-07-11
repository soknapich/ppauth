<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'http_code' => 200,
            'message' => 'Success',
            'data' => $user,
        ]);
    }
}
