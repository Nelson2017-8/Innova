<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TokenController extends Controller
{
	public static function obtenCaracterAleatorio($arreglo) {
    	$clave_aleatoria = array_rand($arreglo, 1);	//obtén clave aleatoria
		return $arreglo[ $clave_aleatoria ];	//devolver ítem aleatorio
	}
 
	public static function obtenCaracterMd5($car) {
		$md5Car = md5($car.Time());	//Codificar el carácter y el tiempo POSIX (timestamp) en md5
		$arrCar = str_split(strtoupper($md5Car));	//Convertir a array el md5
		$carToken = TokenController::obtenCaracterAleatorio($arrCar);	//obtén un ítem aleatoriamente
		return $carToken;
	}
 
	public static function obtenToken($longitud) {
		//crear alfabeto
		$mayus = "ABCDEFGHIJKMNPQRSTUVWXYZ";
		$mayusculas = str_split($mayus);	//Convertir a array
		//crear array de numeros 0-9
		$numeros = range(0,9);
		//revolver arrays
		shuffle($mayusculas);
		shuffle($numeros);
		//Unir arrays
		$arregloTotal = array_merge($mayusculas,$numeros);
		$newToken = "";
		
		for($i=0;$i<=$longitud;$i++) {
			$miCar = TokenController::obtenCaracterAleatorio($arregloTotal);
			$newToken .= TokenController::obtenCaracterMd5($miCar);
		}
		return $newToken;
	}


    // Encriptar y desencriptar
	public static function encriptar($dato) {
		if ( is_numeric($dato) == true ) {
	        $resultado = $dato;
	        $arrayLetras = array('M', 'A', 'R', 'C', 'O', 'S');
	        $limite = count($arrayLetras) - 1;
	        $num = mt_rand(0, $limite);
	        for ($i = 1; $i <= $num; $i++) {
	            $resultado = base64_encode($resultado);
	        }
	        $resultado = $resultado . '+' . $arrayLetras[$num];
	        $resultado = base64_encode($resultado);
	        return $resultado;
    	}else{
    		return $dato;
    	}
    }
    public static function desencriptar($dato) {
    	if ( is_null($dato) ) {
    		return '';
    	}else{
	    	if ( is_numeric($dato) == false ) {
		        $resultado = base64_decode($dato);
		        list($resultado, $letra) = explode('+', $resultado);
		        $arrayLetras = array('M', 'A', 'R', 'C', 'O', 'S');
		        for ($i = 0; $i < count($arrayLetras); $i++) {
		            if ($arrayLetras[$i] == $letra) {
		                for ($j = 1; $j <= $i; $j++) {
		                    $resultado = base64_decode($resultado);
		                }
		                break;
		            }
		        }
		        return $resultado;
	    	}else{
	    		return $dato;
	    	}
    	}
    }


    // Encriptar y desencriptar MD5
	// public static function encriptarMD5($cadena){
	//     $key='palabraclaveparalacodificacionydecodificacion';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	//     $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
	//     return $encrypted;

	// }
	// public static function desencriptarMD5($cadena){
	//     $key='farmaciajuntoati';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	//     $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key) ) ), "\0");
	//     return $decrypted;
	// }
}
