<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Validasi parameter divisi_id
        try {
            $validated = $request->validate([
                'divisi_id' => 'nullable|exists:divisis,id',
            ]);

            $divisi_id = $request->query('divisi_id');
            // Query karyawan dengan relasi divisi dan pekerjaan, serta soft delete
            $query = Karyawan::with(['divisi', 'pekerjaan'])->withTrashed();

            if ($divisi_id) {
                $query->where('divisi_id', $divisi_id);
            }

            $karyawan = $query->get();
            // Menghitung total karyawan yang aktif dan non-aktif
            $total_aktif = $karyawan->whereNull('deleted_at')->count();
            $total_non_aktif = $karyawan->whereNotNull('deleted_at')->count();
            
            return response()->json([
                'data'=> $karyawan,
                'total'=> count($karyawan),
                'total_aktif' => $total_aktif,
                'total_non_aktif' => $total_non_aktif
            ], 200);
        } catch (ValidationException $e) {
            // Kembalikan error validasi dalam format JSON
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);  // Status 422 untuk error validasi
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function hitung()
    {
        $karyawan = Karyawan::with(['divisi', 'pekerjaan'])->get()->count();
        return response()->json([
            'total'=> $karyawan],
        200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawans,email',
            'no_telepon' => 'required|string|max:15',
            'tanggal_lahir' => 'required|date',
            'divisi_id' => 'required|exists:divisis,id',
            'pekerjaan_id' => 'required|exists:pekerjaans,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $karyawan = Karyawan::create($request->all());
        return response()->json($karyawan, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $karyawan = Karyawan::with(['divisi', 'pekerjaan'])->find($id);

        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan not found'], 404);
        }

        return response()->json($karyawan, 200);
    }
    public function semua()
    {
        $karyawan = Karyawan::with(['divisi', 'pekerjaan'])->get();

        return response()->json($karyawan, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::find($id);

        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:karyawans,email,' . $id,
            'no_telepon' => 'sometimes|string|max:15',
            'tanggal_lahir' => 'sometimes|date',
            'divisi_id' => 'sometimes|exists:divisis,id',
            'pekerjaan_id' => 'sometimes|exists:pekerjaans,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $karyawan->update($request->all());
        return response()->json($karyawan, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::find($id);

        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan not found'], 404);
        }

        $karyawan->delete();
        return response()->json(['message' => 'Karyawan deleted successfully'], 200);
    }
}
