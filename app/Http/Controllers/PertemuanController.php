<?php

namespace App\Http\Controllers;

use App\Models\Pertemuan;
use Exception;
use Illuminate\Http\Request;

class PertemuanController extends Controller {

    /**
     * Get pertemuan by id or code
     *
     * @param String $id
     * @return Illuminate\Http\JsonResponse
     */
    public function getPertemuan($id) {

        try {
            $pertemuan = Pertemuan::where('id', $id)->orWhere('kode_pertemuan', $id)->first();

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
    
}
