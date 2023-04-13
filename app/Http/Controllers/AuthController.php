<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && (Hash::check($request->password, $user->password))) {
            if ($user->level == 1 && !$user->approved) {
                return response()->json(['message' => 'Your account is yet to be approved by the admin'], 401,);
            }
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $tokenRequest = $request->create('/oauth/token', 'post', [
            'grant_type' => $request->grant_type,
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);

        $response = app()->handle($tokenRequest);
        $responseData = json_decode($response->getContent(), true);

        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        return response()->json(['data' => $responseData, 'message' => 'Successfully logged in!'], 200);
    }
}
