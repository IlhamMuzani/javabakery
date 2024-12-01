<?php

namespace App\Http\Controllers\Toko_cilacap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    public function index()
    {
        return view('toko_cilacap.index');
    }
}