<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use Tymon\JWTAuth\Facades\JWTAuth;

use App\Notifications\RegisterRequest;



use App\Events\Registered;
use App\Events\RequestReset;

use App\Models\User;
use App\Models\Info;
use App\Models\Authority;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('JWT', ['except' => ['activate', 'send_reset_link', 'reset_page', 'reset_password' ,'login','register', 'refresh', 'checkpulse']]);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'        => 'nullable|string|max:255',
            'last_name'         => 'nullable|string|max:255',
            'email'             => 'required|email|unique:users|max:255',
            'role'              => 'nullable|required|in:employee,customer',
            'password'          => 'required|max:300',
            'cpassword'         => 'required|max:300'
        ]);
        
        if($validator->fails())
        {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }
        

        //create user
        $new_user = new User();
        $new_user->first_name       = $request->first_name;
        $new_user->last_name        = $request->last_name;
        $new_user->email            = $request->email;
        $new_user->role             = $request->role;
        $new_user->password         = Hash::make($request->password);
        $new_user->save();


        return response()->json(['message' => 'Registered Successfully']);

    }


    public function login()
    {
        $credentials = request(['email', 'password']);

        $user = User::where('email', $credentials['email'])->first();

        if (! $token = auth()->setTTL(259200)->attempt($credentials)) 
        { // 3 days time to live
            return response()->json(['errors' => ['auth' => ['Invalid Email Or Password']]], 401);
        }

        return $this->respondWithToken($token);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = User::find(auth()->user()->id);
        return response()->json($user);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh());
        } catch (\Throwable $th) {
            return response()->json(['errors' => ['auth' => ["Couldn't Refresh Token"]]], 401);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = User::find(auth()->user()->id);
        
        return response()->json([
            'token' => [
                        'access_token' => $token,
                        'expires_in'    => auth()->factory()->getTTL(),
            ],
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->first_name.' '.$user->last_name,
                'email' => $user->email, 
                'role'  => $user->role
            ]
        ]);
    }

}
