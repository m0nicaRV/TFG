<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
}
// $request‐>validate();
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                /*'status' => 'error',
                'message' => 'Unauthorized',*/
                'error' => 'Unauthorized. Either email or password is wrong.',
            ], 401);
}
        $user = Auth::user();
        return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' =>120 *60,
        'user' => $user,
    ]);
}
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'dni' => 'required|string|max:10|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => 'string|max:20',
            'password' => 'required|string|min:6',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
}
        $user = User::create([
            'name' => $request->name,
            'dni'=>$request->dni,
            'email' => $request->email,
            'telefono'=>$request->telefono,
            'password' => Hash::make($request->password)

// 'role_id'=> "2"
        ]);
//$token = Auth::login($user);
        return response()->json([
        'message' => "User successfully registered",
        'user' => $user,
    ]);
}
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(
        Auth::user(),
    );
}
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        Auth::logout();
        return response()->json([
            'message' => 'User successfully signed out',
        ]);
}
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
            $user = Auth::user();
            return response()->json([
            'access_token' => Auth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL') * 60,
            'user' => $user,
        ]);
    
    }

    public function index(){
        $users = User::all();
        return response()->json([
            'users' => $users,
        ]);
    }


}
