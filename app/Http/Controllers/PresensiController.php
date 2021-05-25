<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PresensiController extends Controller {
    
    public function createPresensi(Request $request) {

        try {

            $messages = [
                'required' => ':attribute tidak boleh kosong',
                'exists' => ':attribute tidak ditemukan'
            ];

            $attributes = [
                'pertemuan_id' => 'Pertemuan',
                'siswa_uid' => 'Siswa',
            ];

            $validator = Validator::make($request->all(), [
                'pertemuan_id' => 'required|exists:pertemuan,id',
                'siswa_uid' => 'required|string|exists:siswa,kode'
            ], $messages, $attributes);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors()
                ], 400);
            }

            $siswa = Siswa::where('kode', $request->siswa_uid)->first();
            $pertemuan = Siswa::find($request->pertemuan_id);
            $presensi = Presensi::where('pertemuan_id', $pertemuan->id)->where('siswa_id', $siswa->id)->first();
            if($presensi) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Data exists.',
                    'data' => [
                        'presensi' => $presensi,
                        'siswa' => $siswa
                    ]
                ]);
            }

            $currentDate = now();
            $presensi = Presensi::create([
                'tanggal' => $currentDate->toDateString(),
                'waktu' => $currentDate->toTimeString(),
                'pertemuan_id' => $pertemuan->id,
                'siswa_id' => $siswa->id,
            ]);

            if($presensi->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Create success.',
                    'data' => [
                        'presensi' => $presensi,
                        'siswa' => $siswa
                    ]
                ]);
            }

        } catch (Exception $e) {
            return $this->errorMessage($e);
        }

    }

}
