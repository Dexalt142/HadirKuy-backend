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

            return response()->json([
                'status' => 200,
                'message' => 'Fetch success.',
                'data' => $siswa
            ]);
        } catch (Exception $e) {
            return $this->errorMessage($e);
        }
        
    }

}
