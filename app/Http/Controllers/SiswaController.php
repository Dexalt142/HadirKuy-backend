<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SiswaController extends Controller {
    

    /**
     * Get all siswa
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getAllSiswa() {

        try {

            $siswa = Siswa::all();

            if ($siswa->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Fetch failed, siswa not found.',
                ], 404);
            }

            $siswa = $siswa->map(function($sis) {
                $sis->ttl = $sis->tempat_lahir.', '.$this->formattedDate($sis->tanggal_lahir);
                $sis->foto = Config::get('app.url').'/siswa_image/'.$sis->foto;
                return $sis;
            });

            return response()->json([
                'status' => 200,
                'message' => 'Fetch success.',
                'data' => $siswa
            ], 200, [], JSON_UNESCAPED_SLASHES);
        } catch (Exception $e) {
            return $this->errorMessage($e);
        }
        
    }

    /**
     * Get siswa detailed information
     *
     * @param String $id
     * @return Illuminate\Http\JsonResponse
     */
    public function getSiswa($id) {

        try {
            $siswa = Siswa::find($id);

            if (!$siswa) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Fetch failed, siswa not found.',
                ], 404);
            }

            $siswa->ttl = $siswa->tempat_lahir . ', ' . $this->formattedDate($siswa->tanggal_lahir);
            $siswa->foto = Config::get('app.url') . '/siswa_image/' . $siswa->foto;
            $presensi = $siswa->presensi;
            $presensi = $presensi->map(function($pres) {
                $pertemuan = $pres->pertemuan;
                $pertemuan->date_time = $this->formattedDate($pertemuan->tanggal, $pertemuan->waktu, true, true);
                $pres->foto = Config::get('app.url').'/presensi_image/'.$pres->foto;
                $pres->date_time = $this->formattedDate($pres->tanggal, $pres->waktu, true, true);
                $pres->status = $this->isLate($pertemuan, $pres);
                $pertemuan->makeHidden(['created_at', 'updated_at', 'guru_id', 'tanggal', 'waktu', 'kode_pertemuan']);
                $pres->makeHidden(['siswa_id', 'pertemuan_id', 'created_at', 'updated_at']);
                
                return $pres;
            });

            return response()->json([
                'status' => 200,
                'message' => 'Fetch success.',
                'data' => $siswa
            ], 200, [], JSON_UNESCAPED_SLASHES);
        } catch (Exception $e) {
            return $this->errorMessage($e);
        }

    }

}
