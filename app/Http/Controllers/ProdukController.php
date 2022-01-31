<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Produk;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Response;
use PhpParser\Node\Stmt\Else_;

class ProdukController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            $produk = Produk::all();
            return datatables()->of($produk)
            ->addColumn('action', function ($produk) {
                return '
                <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$produk->id.'" data-original-title="Edit" class="edit btn btn-success edit-produk">
                    Edit
                </a>

                <a href="javascript:void(0);" id="delete-produk" data-toggle="tooltip" data-original-title="Delete" data-id="'.$produk->id.'" class="delete btn btn-danger">
                    Delete
                </a>
                ';
            })
            ->addColumn('gambar', function ($produk) {
                $url=asset("gambar/produk/$produk->gambar");
                return '<img src='.$url.' border="0" width="100" class="img-rounded" align="center" />';
            })
            ->rawColumns(['action','gambar'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('superuser.home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
       ]);

        $produkId = $request->produk_id;
        $harga = $request->harga;

        if ($harga>40000) {
            $diskon = $harga*(10/100);
        }elseif ($harga>20000 && $harga<=40000) {
            $diskon = $harga * (5/100);
        }else{
            $diskon = 0;
        }

        $details = [
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'diskon' => $diskon];

        if ($files = $request->file('gambar')) {

           //delete old file
           \File::delete('gambar/produk/'.$request->hidden_image);

           //insert new file
           $destinationPath = 'gambar/produk/'; // upload path
           $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
           $files->move($destinationPath, $profileImage);
           $details['gambar'] = "$profileImage";
        }

        $produk   =   Produk::updateOrCreate(['id' => $produkId], $details);

        return Response::json($produk);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $produk  = Produk::where($where)->first();

        return Response::json($produk);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Produk::where('id',$id)->first(['gambar']);
        \File::delete('gambar/produk/'.$data->gambar);
        $produk = Produk::where('id',$id)->delete();

        return Response::json($produk);
    }
}
