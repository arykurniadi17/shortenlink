<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
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

        $checkUserExisting = User::where('email', $request->input('email'))->first();
        if($checkUserExisting) {
            $response['response']['code'] = 400;
            $response['response']['message'] = 'Email has been existing';
            return response()->json($response, 400);
        }
        
        event(new Registered($user = $this->create($request->all())));    
        
        $this->guard()->login($user);
        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }    

    protected function registered(Request $request, $user)
    {
        $user->generateToken();    

        $response = [
            'response' => ['code' => 200, 'message' => 'Registrasi success'],
            'data' => $user->toArray()
        ];

        return response()->json($response, 200);
    }    
}
