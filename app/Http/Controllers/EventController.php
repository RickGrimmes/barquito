<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function emitirEvento(Request $request)
    {
        $channel = $request->input('channel');
        $event = $request->input('event');
        $data = $request->input('data');

        event(new $event($data), $channel);

        return response()->json(['message' => 'Evento emitido con éxito']);
    }

}
