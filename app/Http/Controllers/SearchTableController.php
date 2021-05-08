<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ValidController;

class SearchTableController extends Controller
{
    // Recibe un string y comprueba sus posibles valores
    public static function orderBy($campo, $numberPag, $funcion, $posiblesValores)
    {
        $coincidencia = NULL;
        for ($i=0; $i < count($posiblesValores); $i++) {
            if ($campo === $posiblesValores[$i]) {
                $coincidencia = $posiblesValores[$i];
            }
        }

        if ( $coincidencia !== NULL ) {
            return $funcion($campo, $coincidencia, $numberPag);
        }else{
            return -1;
        }

    }

    // Recibe un string y comprueba sus posibles valores
    public static function searchWhere(array $campo, string $value, $numberPag, ...$funcions)
    {
        $funcion = -1;

        for ($i=0; $i < count($campo); $i++) {
            for ($j=0; $j < count($funcions); $j++) {
                $funcion = $funcions[$j]($campo[$i], $value, $numberPag);
                if ( count($funcion) > 0 ) {
                    return $funcion;
                }
            }
        }

        return $funcion;
    }

    public static function checkPaginate($numberPag)
    {
        if ( ValidController::inputNoNullType($numberPag, 'number') ) {
            $numberPag = intval($numberPag);
        }else{
            $numberPag = 20;
        }
        return $numberPag;
    }
}
