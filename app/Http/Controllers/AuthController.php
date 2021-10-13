<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Http\Traits\HttpResponse;


class AuthController extends Controller
{
    use HttpResponse;

    // public function __construct() {
    //     $this->middleware('auth:api', ['except' => ['login', 'register',]]);
    // }
        /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
            //code...
            if (! $token = auth()->attempt($validator->validated())) {
                return $this->failure('incorrect');
            }
    
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            //throw $th;
            return $this->failure(['message' => $e->getMessage()]);
        }

    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,10user-profile0',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            //code...
            $user = User::create(array_merge(
                        $validator->validated(),
                        ['password' => bcrypt($request->password)]
                    ));
    
            // return response()->json([user-profile
            //     'message' => 'User successfully registered',
            //     'user' => $user
            // ], 201);
            return $this->success('Registration Successful');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->failure('server Error');
        }
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        // return response()->json(auth()->user());
        try {
            //code...
            $user = Auth::user();
            return $this->success('success', $user);
        } catch (\Exception $e) {
            //throw $th;
            return $this->failure(['message' => $e->getMessage()]);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function user(){
        return Auth::user();
    }

    public function guard()
    {
        return Auth::guard();
    }



}
