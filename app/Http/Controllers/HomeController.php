<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(request()->ajax()) {
            $produk = Produk::all();
            return datatables()->of($produk)
            ->addColumn('gambar', function ($produk) {
                $url=asset("gambar/produk/$produk->gambar");
                return '<img src='.$url.' border="0" width="100" class="img-rounded" align="center" />';
            })
            ->rawColumns(['gambar'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('home');
    }

}
