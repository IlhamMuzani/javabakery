<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function check_user()
    {
        if (auth()->user()->isAdmin()) {
            return redirect('admin');
        } elseif (auth()->user()->isTokoslawi()) {
            return redirect('toko_slawi');
        }elseif (auth()->user()->isTokobanjaran()) {
            return redirect('toko_banjaran');
        }elseif (auth()->user()->isTokobumiayu()) {
            return redirect('toko_bumiayu');
        }elseif (auth()->user()->isTokotegal()) {
            return redirect('toko_tegal');
        }elseif (auth()->user()->isTokopemalang()) {
            return redirect('toko_pemalang');
        }elseif (auth()->user()->isTokocilacap()) {
            return redirect('toko_cilacap');
        }
    }
}