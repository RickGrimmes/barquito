<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //un evento que sirva para que reciba al user que crea la partida

    //otro para llenar la partida

    //uno que espere el mensaje del token para llenar

    // front ya manda por ws, entonces igual que aqui el server esté atento a mensajes del canal home y del evento x, si le llegan entonces que ya use los metodos de aqui para hacer cosas, igual y que
    // front hace solicitudes y demas por http, y tambien manda por ws
    // api puede mandar del ws creo, ya sé voy a crear un evento cada que creo un user a ver qué onda, para saber si la api manda igual
}
