<?php

namespace App\Http\Controllers;

use App\Models\Pertemuan;
use App\Models\Siswa;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
            $a = Carbon::now();

            if($pertemuan) {
                $pertemuan->date_time = $this->formattedDate($pertemuan->tanggal, $pertemuan->waktu, true);
                $pertemuanDateTime = Carbon::parse($pertemuan->date_time);
                if(($pertemuanDateTime->lessThanOrEqualTo(now())) && (now()->diffInMinutes($pertemuanDateTime) < 30)) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Fetch success.',
                        'data' => $pertemuan
                    ]);
                }
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
     * Get detail pertemuan by id
     *
     * @param String $id
     * @return Illuminate\Http\JsonResponse
     */
    public function getDetailPertemuan($id) {

        try {
            $pertemuan = Pertemuan::where('id', $id)->first();
            $siswa = Siswa::all();

            if($pertemuan) {
                $pertemuan->date_time = $this->formattedDate($pertemuan->tanggal, $pertemuan->waktu, true);
                $presensi = $pertemuan->presensi;
                $present = collect();
                $late = collect();
                $absent = collect();

                foreach($siswa as $sis) {
                    $presensiSiswa = $presensi->where('siswa_id', $sis->id)->first();
                    $presData = [
                        'siswa' => [
                            'id' => $sis->id,
                            'nis' => $sis->nis,
                            'nama' => $sis->nama,
                            'foto' => Config::get('app.url').'/siswa_image/'.$sis->foto,
                        ]
                    ];

                    if(!$presensiSiswa) {
                        $absent->push($presData);
                        continue;
                    }

                    $presData['presensi'] = [
                        'id' => $presensiSiswa->id,
                        'date_time' => $this->formattedDate($presensiSiswa->tanggal, $presensiSiswa->waktu, true, true),
                        'foto' => Config::get('app.url').'/presensi_image/'.$presensiSiswa->foto,
                        'status' => $this->isLate($pertemuan, $presensiSiswa),
                    ];

                    if($presData['presensi']['status'] == 'Terlambat') {
                        $late->push($presData);
                        continue;
                    }

                    $present->push($presData);
                }

                $pertemuan->unsetRelation('presensi');
                $pertemuan->presensi = [
                    'present' => $present,
                    'late' => $late,
                    'absent' => $absent,
                ];

                return response()->json([
                    'status' => 200,
                    'message' => 'Fetch success.',
                    'data' => $pertemuan
                ], 200, [], JSON_UNESCAPED_SLASHES);
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
     * Get all pertemuan
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getAllPertemuan() {
        
        try {

            $user = auth()->user();
            $pertemuan = $user->pertemuan;

            if($pertemuan->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Fetch failed, pertemuan not found.',
                ], 404);
            }

            $pertemuan = $pertemuan->map(function($per) {
                $per->date_time = $this->formattedDate($per->tanggal, $per->waktu, true);
                return $per;
            });

            return response()->json([
                'status' => 200,
                'message' => 'Fetch success.',
                'data' => $pertemuan
            ]);

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
                $pertemuan->date_time = $this->formattedDate($pertemuan->tanggal, $pertemuan->waktu, true, true);

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
