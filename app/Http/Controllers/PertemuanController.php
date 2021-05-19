<?php

namespace App\Http\Controllers;

use App\Models\Pertemuan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PertemuanController extends Controller {

    /**
     * Get pertemuan by id or code
     *
     * @param String $id
     * @return Illuminate\Http\JsonResponse
     */
    public function getPertemuan($id) {

        try {
            $pertemuan = Pertemuan::where('id', $id)->orWhereRaw("BINARY `kode_pertemuan`=?", [$id])->first();

            if($pertemuan) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Fetch success.',
                    'data' => $pertemuan
                ]);
            }

            return response()->json([
                'status' => 404,
                'message' => 'Fetch failed, pertemuan not found.',
            ], 404);
        } catch (Exception $e) {
            return $this->errorMessage($e);
        }

    }

    /**
     * Create pertemuan
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function createPertemuan(Request $request) {

        try {

            $messages = [
                'required' => ':attribute tidak boleh kosong',
                'tanggal.date_format' => 'Format tanggal tidak valid',
                'waktu.date_format' => 'Format waktu tidak valid',
            ];

            $attributes = [
                'nama' => 'Nama',
                'tanggal' => 'Tanggal',
                'waktu' => 'Waktu'
            ];

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string',
                'tanggal' => 'required|date_format:Y-m-d',
                'waktu' => 'required|date_format:H:i',
            ], $messages, $attributes);

            if($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors()
                ], 400);
            }

            $user = auth()->user();
            $pertemuan = Pertemuan::make([
                'nama' => $request->nama,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'kode_pertemuan' => Str::random(8),
                'guru_id' => $user->id
            ]);

            if($pertemuan->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Create succes.',
                    'data' => $pertemuan
                ]);
            }

        } catch (Exception $e) {
            return $this->errorMessage($e);
        }

    }
    
}
