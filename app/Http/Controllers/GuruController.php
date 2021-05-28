<?php

namespace App\Http\Controllers;

use App\Models\Pertemuan;
use App\Models\Presensi;
use App\Models\Siswa;
use Exception;

class GuruController extends Controller {

    /**
     * Get statistic
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getStatistic() {

        try {
            $pertemuan = Pertemuan::count();
            $siswa = Siswa::count();
            $latestPresensi = collect();

            $presensi = Presensi::latest()->limit(10)->get();
            foreach($presensi as $pres) {
                $presSiswa = $pres->siswa;
                $presPertemuan = $pres->pertemuan;
                $presData = [
                    'nama_siswa' => $presSiswa->nama,
                    'nama_pertemuan' => $presPertemuan->nama,
                    'waktu' => $pres->waktu,
                    'status' => $this->isLate($presPertemuan, $pres)
                ];

                $latestPresensi->push($presData);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Fetch success.',
                'data' => [
                    'pertemuan' => $pertemuan,
                    'siswa' => $siswa,
                    'presensi' => $latestPresensi
                ]
            ]);
        } catch (Exception $e) {
            return $this->errorMessage($e);
        }

    }
    
}
