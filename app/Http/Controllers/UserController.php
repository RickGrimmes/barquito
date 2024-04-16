<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

                return response()->json([
                    'status' => 'success',
                    'data' => $user
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
}
