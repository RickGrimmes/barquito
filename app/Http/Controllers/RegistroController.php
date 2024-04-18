<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegistroController extends Controller
{
    public function index()
    {
        try
        {
            $registro = Registro::all();
            return response()->json([
                'status' => 'success',
                'data' => $registro
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
            $registro = Registro::find($id);
            return response()->json([
                'status' => 'success',
                'data' => $registro
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

    public function crearPartida(Request $request)
    {
        try
        {
            $authenticatedUser = Auth::user();
         
        $registro = Registro::create([
            'player1' => $authenticatedUser->id,
        ]);

            return response()->json([
                'status' => 'success',
                'data' => $registro
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

    public function iniciarPartida(Request $request)
    {
        try
        {
            $authenticatedUser = Auth::user();

            $registro = Registro::where('player2', 0)->orderBy('id', 'desc')->first();

            if (!$registro) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontró ninguna partida disponible'
                ], 404);
            }
            $registro->player2 = $authenticatedUser->id;
            $registro->save();

            return response()->json([
                'status' => 'success',
                'data' => $registro
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'data' => $e
            ]);
        }
    }

    public function finPartida(Request $request, $id)
    {
        try
        {
            $registro = Registro::find($id);

            if ($registro == null)
            {
                return response()->json([
                    'status' => 'error',
                    'error' => 'Registro not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
               'winner' => [
                'required',
                'exists:users,id',
                'in:' . $registro->player1 . ',' . $registro->player2,
                Rule::notIn([$request->loser])
            ],
            'loser' => [
                'required',
                'exists:users,id',
                'in:' . $registro->player1 . ',' . $registro->player2,
                Rule::notIn([$request->winner])
            ],
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    'status' => 'error',
                    'error' => $validator->errors()
                ], 400);
            }

            $registro = Registro::where('id', $id)->first();

            if ($registro) {
                $registro->update([
                    'winner' => $request->winner,
                    'loser' => $request->loser,
                ]);

                 // Incrementa el valor de wins para el usuario winner
                User::where('id', $request->winner)->increment('wins');
                User::where('id', $request->loser)->increment('losses');

                // Recarga el modelo con los datos frescos de la base de datos
                $registro->refresh();

                return response()->json([
                    'status' => 'success',
                    'data' => $registro
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'error' => 'Registro no encontrado'
                ], 404);
            }

        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // voy a crear el registro de la partida al inicio de cada partida y otro que actualice solo hasta el final de la misma
}
