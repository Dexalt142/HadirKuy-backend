<?php

namespace App\Http\Controllers;

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
}
