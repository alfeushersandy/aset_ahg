<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;

class MekanikController extends Controller
{
    public function index()
    {
        return view('mekanik.index');
    }

    public function data()
    {
        $petugas = Petugas::all();

        return datatables()
            ->of($petugas)
            ->addIndexColumn()
            ->addColumn('aksi', function ($petugas) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('mekanik.update', $petugas->id_petugas) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('mekanik.destroy', $petugas->id_petugas) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $petugas = Petugas::latest()->first() ?? new Petugas();

        $petugas = new Petugas();
        $petugas->nama_petugas = $request->nama_petugas;
        $petugas->kategori_tugas = $request->kategori_tugas;
        $petugas->departemen_tugas = $request->departemen_tugas;
        $petugas->telepon = $request->telepon;
        $petugas->alamat = $request->alamat;
        $petugas->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $petugas = Petugas::find($id);

        return response()->json($petugas);
    }

    public function update(Request $request, $id)
    {
        $petugas = Petugas::find($id)->update($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy($id)
    {
        $member = Petugas::find($id);
        $member->delete();

        return response(null, 204);
    }

}
