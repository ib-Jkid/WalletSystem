<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Repository\IUserRepository;
use App\Repository\IWalletRepository;
use Exception;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    private $user_repository;
    private $wallet_repository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUserRepository $user_repo, IWalletRepository $wallet_repo)
    {
        //
        $this->user_repository = $user_repo;

        $this->wallet_repository = $wallet_repo;
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "email" => "required",
            "password" => "required"
        ]);

        if($validator->fails()) return $this->bad_validation($validator->errors()->toArray());
        $credentials = request(['email', 'password']);
    
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    public function  register(Request $request) {

        $validator = Validator::make($request->all(), [
            "email" => "required|unique:users",
            "password" => "required|min:8",
            "currency" => "required|in:".implode(",",SUPPORTED_CURRENCY),
        ]);

        if($validator->fails()) return $this->bad_validation($validator->errors()->toArray());

        DB::beginTransaction();


        try {
            
            $user = $this->user_repository->create([
                "public_id" => Uuid::uuid(),
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);



        
            
            $wallet = $this->wallet_repository->create([
                "public_id" => Uuid::uuid(),
                "currency" => $request->currency,
                "amount" => 0,
                "email" => $request->email
            ]);

            DB::commit();

        }catch(Exception $e) {
            DB::rollBack();

            return $this->server_error();
        }


        $user->wallet = $wallet;

    
    
        return $this->ok(UserResource::make($user));
        
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    

    //
}
