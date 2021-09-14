<?php 

namespace App\Traits;


trait ApiResponse {
    protected function ok($data) {
        return response()->json(["status" => true,  "message" => "successfull", "data" => $data,], 200);
    }


    protected function not_found() {
        return response()->json(["status" => false, "message" => "Not found", "data" => null], 404);
    }


    protected function bad_validation($validationErrors) {
        return response()->json(["status" => false, "message" => $validationErrors, "data" => null], 401);
    }


    protected function server_error($message = null) {
        return response()->json(["status" => false, "message" => ($message)? $message : "Server Error", "data" => null], 500);
    }
}
