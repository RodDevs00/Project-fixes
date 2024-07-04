<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, UserInfo};


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
        if (! $token = auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Your email or password are incorrect'], 401);
        } else {
            $me = User::find(auth()->user()->id);
            
            if($me->account_activated === 1){
                return $this->respondWithToken($token);
            }   
            
            if($me->account_activated === 0 ) {
                return response()->json(['error' => 'Your account is still on pending state.'], 401);
            }

            if($me->account_activated === 2 ) {
                return response()->json(['error' => 'Your account is suspended. Please contact your Administrator.'], 401);
            }
        }

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $me = UserInfo::where('user_id', auth()->user()->id)->with('account')->first();
        return response()->json($me);
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
        return $this->respondWithToken(auth()->refresh());
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
        $me = User::where('id', auth()->user()->id)->first();
        return response()->json([
            'access_token' => $token,
            'role'         => $me->role->role
        ]);
    }

    public function register(Request $request)
    {
        try {
            $newAccount = User::create([
                'email'     => $request->email,
                'password'  => $request->password,
                'user_role_id'   => 2,
            ]);
            
            UserInfo::create([
                'first_name'                => $request->first_name,
                'middle_name'               => $request->middle_name,
                'surname'                   => $request->surname,
                'sex'                       => $request->sex,
                'baranggay'                 => $request->baranggay,
                
                'user_id'                   => $newAccount->id
            ]);
    
            return response()->json(['success' => 'Submitted! Administrator will validate your registration.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Registration failed, try again later."], 500);
        }
    }

    public function updateAccount($id, Request $request)
    {
        try {
            $userAccount = UserInfo::where("user_id", $id)->first();
            
            $userAccount->update([
                'first_name'                => $request->first_name,
                'middle_name'               => $request->middle_name,
                'surname'                   => $request->surname,
                'sex'                       => $request->sex,
                'civil_status'              => $request->civil_status,
                'baranggay'                 => $request->baranggay,
            ]);
    
            return response()->json(['success' => 'Account updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}