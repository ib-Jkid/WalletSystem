<?php 

namespace App\Traits;


trait ApiResponse {
    private function ok($data) {
        return response()->json(["status" => true,  "message" => "successfull", "data" => $data,], 200);
    }


    private function not_found() {
        return response()->json(["status" => false, "message" => "Not found", "data" => null], 404);
    }


    public function bad_validation($validationErrors) {
        return response()->json(["status" => false, "message" => $validationErrors, "data" => null], 401);
    }


    public function server_error() {
        return response()->json(["status" => false, "message" => "Server Error", "data" => null], 500);
    }
}
