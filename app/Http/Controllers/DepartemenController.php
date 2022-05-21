<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    public function index()
    {
        return view('departemen.index');
    }

    public function data()
    {
        $departemen = Departemen::orderBy('id_departemen', 'desc')->get();

        return datatables()
            ->of($departemen)
            ->addIndexColumn()
            ->addColumn('aksi', function ($departemen) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('departemen.update', $departemen->id_departemen) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('departemen.destroy', $departemen->id_departemen) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $departemen = new Departemen();
        $departemen->departemen = $request->nama_departemen;
        $departemen->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $departemen = Departemen::find($id);

        return response()->json($departemen);
    }

    public function update(Request $request, $id)
    {
        $departemen = Departemen::find($id);
        $departemen->departemen = $request->nama_departemen;
        $departemen->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy($id)
    {
        $departemen = Departemen::find($id);
        $departemen->delete();

        return response(null, 204);
    }
}
