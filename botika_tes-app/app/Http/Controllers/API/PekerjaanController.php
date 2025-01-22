<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pekerjaan;

class PekerjaanController extends Controller
{
    /**
     * Menampilkan semua .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $divisi = Pekerjaan::all();

        return response()->json($divisi, 200);
    }
    /**
     * Menambahkan pekerjaan baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pekerjaan' => 'required|string|max:255',
        ]);

        $pekerjaan = Pekerjaan::create([
            'nama_pekerjaan' => $request->nama_pekerjaan,
        ]);

        return response()->json($pekerjaan, 201);
    }

    /**
     * Mengupdate pekerjaan yang ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $pekerjaan = Pekerjaan::findOrFail($id);

        $request->validate([
            'nama_pekerjaan' => 'required|string|max:255',
        ]);

        $pekerjaan->update([
            'nama_pekerjaan' => $request->nama_pekerjaan,
        ]);

        return response()->json($pekerjaan, 200);
    }

    /**
     * Menghapus pekerjaan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $pekerjaan = Pekerjaan::findOrFail($id);

        $pekerjaan->delete();

        return response()->json(['message' => 'Pekerjaan deleted successfully'], 200);
    }
}
