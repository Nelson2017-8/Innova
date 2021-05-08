<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// import the storage facade
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
	public function __construct(){
        // SI NO EXISTE UNA SESION LA INICIO
        if ( !isset($_SESSION) ) {
            session_start();
        }
    }
	// // Path to the project's root folder
    // echo base_path();

    // // Path to the 'app' folder
    // echo app_path();

    // // Path to the 'public' folder
    // echo public_path();

    // // Path to the 'storage' folder
    // echo storage_path();

    // // Path to the 'storage/app' folder
    // echo storage_path('app');

    // $mypublicPath = public_path();
    // $savePath = $mypublicPath."/API/";
    // $path = $savePath."filename.txt";
    // return File::put($path , "Hola");
    

		/* PRIMERA FORMA DE SUBIR ARCHIVOS - SOLO UNO */
	// SUBIR ARCHIVO, SI SOLO ES UNO
	// $request->file('imagenes')->store('public');

	/* SEGUNDA FORMA DE SUBIR ARCHIVOS - SOLO UNO */

	//obtenemos el campo file definido en el formulario
	// $file = $request->file('imagenes');

	//obtenemos el nombre del archivo
	// $nombre = $file->getClientOriginalName();

		//indicamos que queremos guardar un nuevo archivo en el disco local
	// \Storage::disk('local')->put($nombre,  \File::get($file));


	// php artisan storage:link

	// OTRAS OPCIONES
	// Storage::delete('file.jpg');
	// Storage::delete(['file1.jpg', 'file2.jpg']);
	// Storage::copy('old/file1.jpg', 'new/file1.jpg');
	// Storage::move('old/file1.jpg', 'new/file1.jpg');

	// echo '<img src="'.asset('storage/img1.jpg').'" alt="image">';
	
	public static function delete($imagenes) : boolean {
    	if ( $imagenes != NULL ) {
    		// EXISTEN POR LO MENOS UNA IMAGEN
    		foreach ($imagenes as $imagen) {
    			Storage::delete($imagen);
    		}

    		return true;
    	
    	}else{
	    	
	    	return false;
		
		}
	}
    public static function upload($imagenes, string $name, string $folderRoot = '', int $maxSize = 3098190) : string {
    	if ( $imagenes != NULL ) {
    		
    		$paths = '';
    		
    		foreach ($imagenes as $imagen) {
    			// SI LA IMAGEN NO ES JPEG, PNG O GIF NO ES ACEPTADA
    			if ($imagen->getMimeType() == 'image/jpeg' || $imagen->getMimeType() == 'image/jpg' || $imagen->getMimeType() == 'image/png' || $imagen->getMimeType() == 'image/gif') {
    				
    				$imageType = '';
    				if ($imagen->getMimeType() == 'image/jpeg' || $imagen->getMimeType() == 'image/jpg') {
    					$imageType = '.jpg';
    				}else if ($imagen->getMimeType() == 'image/png') {
    					$imageType = '.png';
    				}else{
    					$imageType = '.gif';
    				}
	    			// si el tamaño es menor que 3MB
	    			if ( $imagen->getSize() < $maxSize ) {
		    			$folder = str_replace(' ', '_', $name);
		    			$filename = rand(1, 9000).$imageType;

		    			if ( $folderRoot == ''  ) {
		    				$folderRoot = 'img/'.$folder;
		    			}
						$url = $imagen->storeAs($folderRoot, $filename);
		    			$paths .= "|$url";
	    			}else{
	    				$_SESSION['notifications'] = array('error' => 'Error la imagen '.$imagen->getClientOriginalName().' tiene un tamaño mayor a 3MB y por lo tanto no puede ser guardada');
	    			}
    			}else{
    				// FORMATO DE IMAGEN NO VÁLIDO
    				$_SESSION['notifications'] = array('error' => 'Error: El formato de imagen no es aceptado');
    			}

    		}
    		
    		return trim($paths, '|');
		
		}else{
	    	
	    	return '';
		
		}
    }

    public static function obtenerListadoDeArchivos($directorio){

	  // Array en el que obtendremos los resultados
	  $res = array();

	  // Agregamos la barra invertida al final en caso de que no exista
	  if(substr($directorio, -1) != "/") $directorio .= "/";

	  // Creamos un puntero al directorio y obtenemos el listado de archivos
	  $dir = @dir($directorio) or die("getFileList: Error abriendo el directorio $directorio para leerlo");
	  while(($archivo = $dir->read()) !== false) {
	      // Obviamos los archivos ocultos
	      if($archivo[0] == ".") continue;
	      if(is_dir($directorio . $archivo)) {
	          $res[] = array(
	            "Nombre" => $directorio . $archivo . "/",
	            "Tamaño" => 0,
	            "Modificado" => filemtime($directorio . $archivo)
	          );
	      } else if (is_readable($directorio . $archivo)) {
	          $res[] = array(
	            "Nombre" => $directorio . $archivo,
	            "Tamaño" => filesize($directorio . $archivo),
	            "Modificado" => filemtime($directorio . $archivo)
	          );
	      }
	  }
	  $dir->close();
	  return $res;
	}
}
