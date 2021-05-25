<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PresensiController extends Controller {
    
    public function createPresensi(Request $request) {

        try {

            $messages = [
                'required' => ':attribute tidak boleh kosong',
                'exists' => ':attribute tidak ditemukan',
                'mimes' => ':attribute harus beformat JPEG',
                'picture.max' => ':attribute maksimal 4MB',
            ];

            $attributes = [
                'pertemuan_id' => 'Pertemuan',
                'siswa_uid' => 'Siswa',
                'picture' => 'Foto'
            ];

            $validator = Validator::make($request->all(), [
                'pertemuan_id' => 'required|exists:pertemuan,id',
                'siswa_uid' => 'required|string|exists:siswa,kode',
                'picture' => 'required|file|mimes:jpeg|max:4096',
            ], $messages, $attributes);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors()
                ], 400);
            }

            $siswa = Siswa::where('kode', $request->siswa_uid)->first();
            $siswa->foto = Config::get('app.url').'/siswa_image/'.$siswa->foto;
            $pertemuan = Siswa::find($request->pertemuan_id);
            $presensi = Presensi::where('pertemuan_id', $pertemuan->id)->where('siswa_id', $siswa->id)->first();
            if($presensi) {
                $presensi->date_time = $this->formattedDate($presensi->tanggal, $presensi->waktu, true, true);

                return response()->json([
                    'status' => 200,
                    'message' => 'Data exists.',
                    'data' => [
                        'presensi' => $presensi,
                        'siswa' => $siswa
                    ]
                ], 200, [], JSON_UNESCAPED_SLASHES);
            }

            $currentDate = now();
            $picture = $request->file('picture');
            $fileName = Str::uuid().'.jpg';

            Storage::disk('presensi_image')->putFileAs('', $picture, $fileName);

            $presensi = Presensi::create([
                'tanggal' => $currentDate->toDateString(),
                'waktu' => $currentDate->toTimeString(),
                'pertemuan_id' => $pertemuan->id,
                'siswa_id' => $siswa->id,
                'foto' => $fileName
            ]);
            
            if($presensi->save()) {
                $presensi->date_time = $this->formattedDate($presensi->tanggal, $presensi->waktu, true, true);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Create success.',
                    'data' => [
                        'presensi' => $presensi,
                        'siswa' => $siswa
                    ]
                ], 200, [], JSON_UNESCAPED_SLASHES);
            }

        } catch (Exception $e) {
            return $this->errorMessage($e);
        }

    }

}
