<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * Build internal server error message
     *
     * @param Exception $e
     * @return Illuminate\Http\JsonResponse
     */
    public function errorMessage(Exception $e) {
        if(config(('app.debug'))) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Internal server error.'
        ], 500);
    }

    /**
     * Format date
     *
     * @param String $tanggal
     * @param String $waktu
     * @param boolean $withTime
     * @param boolean $withSeconds
     * @return String
     */
    public function formattedDate($tanggal, $waktu, $withTime = false, $withSeconds = false) {
        $dateTime = Carbon::parse("$tanggal $waktu");
        if($withTime) {
            if($withSeconds) {
                return $dateTime->format('d M Y H:i:s');
            }
            
            return $dateTime->format('d M Y H:i');
        }
        
        return $dateTime->format('d M Y');
    }
}
