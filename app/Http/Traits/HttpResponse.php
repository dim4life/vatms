<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;

trait HttpResponse {

    protected function success($message, $data = [], $status = 200) {
        return response([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function failure($message, $status = 200) {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'token_type' => 'bearer',
            'success' => true,
            'token' => $token,
            'user'=>Auth::user()
        ], 200)->withCookie(cookie('_token', $token, 1440));
    }

    protected function failAuth($message="") {
        return response()->json([
            'token_type' => 'bearer',
            'success' => false,
            'message' => $message,
        ], 200);
    }

    protected function userInfo($user) {
        if($user === null) {
            return response()->json([
                'success' => false,
                'user' => null
            ]);
        }
        return response()->json([
            'success' => true,
            'user' => $user,
            'roles' => $user->roles->pluck('name')
        ]);
    }

}