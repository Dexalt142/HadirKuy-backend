<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

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

}
