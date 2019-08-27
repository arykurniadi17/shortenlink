<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required",
            "password"  => "required",
        ]);        

        $response = [
            'response' => ['code' => 404, 'message' => 'Initial error'],
        ];

        if ($validator->fails()) {
            $response['response']['code'] = 422;
            $response['response']['message'] = $validator->errors();
            return response()->json($response, 422);
        }        

        $validate = $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            $user->generateToken();

            $response['response']['code'] = 200;
            $response['response']['message'] = 'Login has been success';
            $response['data'] = $user->toArray();
            
            return response()->json($response, 200);
        }
    
        return $this->sendFailedLoginResponse($request);
    }    
}
