<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Divisi;

class DivisiController extends Controller
{
    /**
     * Menampilkan semua divisi.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $divisi = Divisi::all();

        return response()->json($divisi, 200);
    }
    /**
     * Menambahkan divisi baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255',
        ]);

        $divisi = Divisi::create([
            'nama_divisi' => $request->nama_divisi,
        ]);

        return response()->json($divisi, 201);
    }

    /**
     * Mengupdate divisi yang ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        $request->validate([
            'nama_divisi' => 'required|string|max:255',
        ]);

        $divisi->update([
            'nama_divisi' => $request->nama_divisi,
        ]);

        return response()->json($divisi, 200);
    }

    /**
     * Menghapus divisi.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);

        $divisi->delete();

        return response()->json(['message' => 'Divisi deleted successfully'], 200);
    }
}
