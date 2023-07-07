<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(){

    }
    
    public function register(Request $request){
        try{
           
            $input = $request->only('name', 'email', 'password', 'role_id');
            $validate = Validator::make($input, User::$registerRule);
            if($validate->fails()) return $this->sendError($validate->errors()->first(), 400);

            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);

            if(Auth::attempt($request->only('email', 'password'))){
                Log::debug('Auth Attempt in Register');
                $userData = auth()->user();
                $token = $userData->createToken('token')->plainTextToken;
                $userData['token'] = $token;
                return $this->sendRessponse($userData, "Register Successfully.");
            }

            return $this->sendRessponse($user, 'Registered Successfull, Kindly Login');

        }catch(Exception $e){
            Log::debug('Error in' . __FUNCTION__ . ' function in ' .__CLASS__. ' class, check details.');
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }

    public function login(Request $request){
        try{

            $input = $request->only('email', 'password');
            $validate = Validator::make($input, User::$loginRule);
            if($validate->fails()) return $this->sendError($validate->errors()->first(), 400);

            if(Auth::attempt(['email' => $input['email'], 'password'=>$input['password']])){
                $userData = auth()->user();
                $token = $userData->createToken('token')->plainTextToken;
                $userData['token'] = $token;
                return $this->sendRessponse($userData, "Login Successfully.");
            } 
            
            return $this->sendError('Please provide valid Password.', 400);
            
        }catch(Exception $e){
            Log::debug('Error in' . __FUNCTION__ . ' function in ' .__CLASS__. ' class, check details.');
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }

    public function logout(){
        try{

            Auth::logout();
            return redirect('/login');

        }catch(Exception $e){
            Log::debug('Error in' . __FUNCTION__ . ' function in ' .__CLASS__. ' class, check details.');
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }

    public function loginPage(){
        if(auth()->user()) return redirect('/');
        return view('auth.login');
    }

    public function registerPage(){
        if(auth()->user()) return redirect('/');
        $roles = Role::all();
        return view('auth.register', ["roles"=>$roles]);
    }
}
