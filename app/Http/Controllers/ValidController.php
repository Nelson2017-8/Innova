<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidController extends Controller
{
	// VERIFICA LOS VALORES QUE PUEDE TOMAR UN CAMPO. EJEMPLO: dia puede tomar 1,2,3,4,5,6,7
    public static function inputCheck(string $input, ...$value) :bool {
    	for ($i=0; $i < count($value); $i++) {
    		if ($input === $value[$i]) {
    			return true;
    		}
    	}
    	return false;
    }

    // VERIFICA LOS VALORES QUE PUEDE TOMAR UN CAMPO. EJEMPLO: dia puede tomar 1,2,3,4,5,6,7
    // Devuelve la primer coincidencia
    public static function inputAllCheck(string $input, ...$value) {
        for ($i=0; $i < count($value); $i++) {
            if ($input === $value[$i]) {
                echo "Input: $input      | Value: $value[$i]<br>";
                return $value[$i];
            }
        }
        return NULL;
    }

    // VERIFICA SI UN CAMPO EXISTE Y ADEMÁS NO ESTA VACIO
    public static function inputIsNull($input) :bool {
		if (!empty($input)) {
			if ($input !== null) {
				return true;
			}
		}
    	return false;
    }

    // VERIFICA SI UN CAMPO NO ESTA VACIO SI LO ESTA AGREGO UN VALOR
    public static function emptyDefault($input, string $default) :string {
        if (!empty($input)) {
            return $input;
        }else{
            return $default;
        }
    }

    // VERIFICA SI UN CAMPO ESTA VACIO O NULO Y DEVUELVE UN STRING VACIO
    public static function emptyString($input)
    {
        return self::emptyDefault($input, '');
    }

    // VERIFICA SI UN CAMPO EXISTE Y ADEMÁS NO ESTA VACIO
    // ADICIONALMENTE VERIFICA UN TIPO DE DATO ESPECIFICO
    public static function inputNoNullType($input, string $type) :bool {
        if (!empty($input)) {
            if ($input !== null) {
                $op = false;
                switch ($type) {
                    case 'number':
                        $op = is_numeric($input);
                        break;
                    case 'string':
                        $op = is_string($input);
                        break;
                    case 'array':
                        $op =  is_array($input);
                        break;
                    case 'function':
                        $op = is_callable($input);
                        break;
                    case 'bool':
                    case 'booleano':
                        $op =  is_bool($input);
                        break;
                    case 'int':
                        $op = is_int($input);
                        break;
                    case 'integer':
                        $op = is_integer($input);
                        break;
                    case 'float':
                        $op = is_float($input);
                        break;
                    case 'double':
                        $op = is_double($input);
                        break;
                }

                if ( $op == true ) {
                    return true;
                }else{
                    return false;
                }
            }
        }
        return false;
    }

    // CUENTA LOS ELEMENTOS DE UNA CONSULTA SQL, DEVUELVE -1 EN CASO DE VACIO
    public static function countSQL($sql)
    {
        if ( count($sql) > 0 ) {
            // HAY RESULADOS
            return $sql;
        }else{
            return -1;
        }
    }
}
