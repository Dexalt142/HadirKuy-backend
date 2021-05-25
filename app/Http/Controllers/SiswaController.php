<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Exception;
use Illuminate\Http\Request;

class SiswaController extends Controller {
    

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
                $sis->foto = env('APP_URL').'/siswa_image/'.$sis->foto;
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

}
