<?php

namespace App\Http\Controllers;

use App\Mail\CodeEmail;
use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        try
        {
            $user = User::all();
            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try
        {
            $user = User::find($id);
            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|max:25',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    'status' => 'error',
                    'error' => $validator->errors()
                ], 400);
            }

            $user= User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            $token = hash('sha256', Str::random(60));
            $user->verification_token = $token;
            $user->save();

            $verificationLink = url('/api/verifyemail/' . $token);

            // Crear un nuevo correo electrónico de verificación
            $verificationEmail = new VerificationEmail($user, $verificationLink);
            // Enviar el correo electrónico de verificación
            Mail::to($request->email)->send($verificationEmail);

            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 201);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login (Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    'status' => 'error',
                    'error' => $validator->errors()
                ], 400);
            }

            $user = User::where('name', $request->name)->first();

            
            if ($user && Hash::check($request->password, $user->password))
            {
                if ($user->isActive == false)
                {
                    return response()->json([
                        'status' => 'error',
                        'error' => 'El usuario no está activo'
                    ], 401);
                }

                $code=rand(100000,999999);
                // $hashedCode = Hash::make($code);
                $user->code=$code;
                $user->save();

                Mail::to($user->email)->send(new CodeEmail($user, $code));

                return response()->json([
                    'status' => 'success',
                    'data' => $user,
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'error' => 'Los datos no son correctos'
            ], 401);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'data' => 'User not found'
            ], 404);
        }

        $user->isActive = true;
        $user->verification_token = null;
        $user->save();

        return response()->json([
            'status' => 'success', 
            'data' => 'Email verified'
        ], 200);
    }

    public function verifyCode(Request $request)
    {
       try
       {
              $validator = Validator::make($request->all(), [
                'code' => 'required',
              ]);
    
              if ($validator->fails())
              {
                return response()->json([
                     'status' => 'error',
                     'error' => $validator->errors()
                ], 400);
              }

              // Buscar al usuario en base al código
              $user = User::where('code', $request->code)->first();
    
              if ($user && $request->code == $user->code)
              {
                $token = JWTAuth::fromUser($user);

                return response()->json([
                     'status' => 'success',
                     'data' => $user,
                     'token' => $token,
                ], 200);
              }
    
              return response()->json([
                'status' => 'error',
                'error' => 'Code not verified'
              ], 401);
       }
       catch (\Exception $e)
       {
           return response()->json([
               'status' => 'error',
               'error' => $e->getMessage()
           ], 500);
       }
    }
}
