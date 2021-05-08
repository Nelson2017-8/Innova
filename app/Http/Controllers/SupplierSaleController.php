<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AuthenticateController;
use App\Compra;
use App\CompraProvDet;
use App\Proveedor;
use App\Subcategoria;
use App\Almacen;
use App\InsumoComprado;
use App\InsumoAlmacenado;
use App\Http\Requests\SupplierSaleRequest;
use App\Http\Controllers\ImageController;
// import the storage facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/*
	LAS COMPRAS REALIZADAS A LOS PROVEEDORES DE MATERIA PRIMA
 */
class SupplierSaleController extends Controller
{
    protected $user = array();
	protected $auth = '';
    protected $authRoute = 'login';

    public function __construct(){
    	$this->auth = new AuthenticateController();
    	$this->user = $this->auth->start();
    }

    // VISTA INSERTAR
    public function index() {
    	// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

    	$subcategory = Subcategoria::all();
    	$raw_material = InsumoComprado::all();
    	$warehouse = Almacen::all();
    	$maxCapacidad = false;
        foreach($warehouse as $value){
           if($value->maxCapacidad === 'No'){
                $maxCapacidad = false;
                break;
           }else{
                $maxCapacidad = true;
           }
        }
        $errForm = false;
        if ( $maxCapacidad == true ) {
        	$errForm = true;
        	$_SESSION['notifications'][] = "Es requerido que por lo menos un alamcen no haya alcanzado su maxima capacidad, por favor cree otro alamcen.<a href='". route('dashboard.warehouse.create') ."''>Crear Almacen</a>";

        }
        if ( count($subcategory) == 0) {
        	$errForm = true;
        	$_SESSION['notifications'][] = "Es requerido crear primero una subcategoria. <a href='". route('dashboard.subcategory.create') ."''>Crear subcategoria</a> ";
        }
        if ( count($warehouse) == 0) {
        	$errForm = true;
        	$_SESSION['notifications'][] = "Es requerido crear primero un un alamcen. <a href='". route('dashboard.warehouse.create') ."''>Crear Almacen</a> ";
        }

        return view(
        	'dashboard.suppliers_sale.create',
        	[
        		'user' => $this->user,
        		'proveedores' => Proveedor::all(),
        		'warehouse' => $warehouse,
        		'raw_material' => $raw_material,
        		'subcategory' => $subcategory,
        		'errForm' => $errForm,
        		'notifications' => $this->auth->notifications(),
        	]
        );
    }

    public function isEncryp($value)
    {
    	return TokenController::desencriptar($value);
    }


    // PROCESO DE INSERTAR
    public function store(Request $request) {
    	// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

    	/* ------------- RECIVOS LOS DATOS --------------- */
    	// PROVEEDOR
    	$getIdProv = $request->proveedor_existe;

    	// EL PROVEEDOR NO ESTA REGISTRADO, ENTONCES LO REGISTRO
    	if ( $getIdProv == '' ) {
    		if ( count(Proveedor::where(['correo' => $request->correoProv])->get()) == 0 ) {

	    		$prov = new Proveedor();

	    		$prov->nombre = $request->nombreProv;
	    		$prov->correo = $request->correoProv;
	    		$prov->razonSocial = $request->razonSocialProv;
	    		$prov->direccion = $request->direccionProv;
	    		$prov->fechaRegistro = date("Y-m-d H:i:s");
				$prov->cod_proveedor = TokenController::obtenToken(10);
	    		$prov->cod_postal = $request->cod_postalProv;
		    	$prov->telefono_1 = $request->telefono_1Prov;
		    	$prov->telefono_2 = $request->telefono_2Prov;
		    	$prov->save();

		    	// EXTRAIGO EL ID DEL REGISTRO INSERTADO
		    	$prov = Proveedor::where(['correo' => $request->correoProv])->get()[0];
    		}else{
        		$_SESSION['notifications'][] = "El proveedor con el correo $request->correoProv ya esta registrado en la base de datos. Si un proveedor esta registrado solo debe seleccionarlo omitiendo el formulario de registro de proveedores.";
    			return redirect()->route('dashboard.suppliers.sale.view.create');
    		}
    	}else{
    		$getIdProv = $this->isEncryp($getIdProv);
    		$prov = Proveedor::find($getIdProv);
    	}

    	// DATOS DE LA COMPRA
    	$compra = new Compra();
        $compra->totalCompra = 0;
        $totalCompra = 0;
    	// $compra->totalCompra = $request->precioCompra;
    	if ( $request->numFacturaCompra == '' ) {
	    	$numFactura = TokenController::obtenToken(8);
    	}else{
	    	$numFactura = $request->numFacturaCompra;
    	}
    	$compra->numFactura = $numFactura;
	    $compra->fechaCompra = $request->fechaCompra;
	    $compra->proveedor_id = $this->isEncryp($prov->id);
	    $compra->save();

	    // EXTRAIGO EL ID DEL REGISTRO INSERTADO
		$compra = Compra::where(['numFactura' => $numFactura])->get()[0];
		$compra_id = $compra->id;

	    // DETALLES DE LA COMPRA - ARTICULOS COMPRADOS
    	$numArts = intval($request->numArts); // Numero de articulos comprados
        $compra_det = array();
        $almacen_id = array();
        $resto = array();
    	for ($i=1; $i < $numArts + 1; $i++) {


    		// MATERIA PRIMA
            echo "HOLA MUNDO 1<br>";
    		$nameMat = $request['nombreArt'.$i];
            // echo "HOLA MUNDO 1.1<br>";
            // echo "SUBCATEGORIA: ".$request['subcategoryArt'.$i];
    		$subcategoria_id = '';
            // echo "<br>HOLA MUNDO 1.2<br>";

    		if ( $request['raw_material_select'.$i] !== NULL ) {

                echo "HOLA MUNDO 2<br>";
    			$materiaPrima = InsumoComprado::find($this->isEncryp($request['raw_material_select'.$i]));
    		}
    		else{
                echo "HOLA MUNDO 3<br>";
	    		// SI EXISTE ESA MATERIA PRIMA NO LA CREO, SOLO EXTRAIGO EL ID
                $subcategoria_id = $this->isEncryp( $request['subcategoryArt'.$i] );
	    		$isRawMaterialExist = InsumoComprado::where(['subcategoria_id' => $subcategoria_id, 'nombre' => $nameMat])->get();
	    		if ( isset($isRawMaterialExist[0]) ) {
                    echo "HOLA MUNDO 3.1<br>";
	    			$materiaPrima = $isRawMaterialExist[0];
	    		}
		    	// SI NO EXISTE LA MATERIA PRIMA LO REGISTRO
	    		else{
                    echo "HOLA MUNDO 3.2<br>";
		    		$materiaPrima = new InsumoComprado();
			    	$materiaPrima->nombre = $nameMat;
			    	$materiaPrima->detalles = $request['detallesArt'.$i];
			    	$cb = '';

			    	if ( $request['codigo_barrasArt'.$i] == NULL ) {
                        echo "HOLA MUNDO 3.3<br>";
				    	$cb = TokenController::obtenToken(8);
			    	}else{
                        echo "HOLA MUNDO 3.4<br>";
				    	$cb = $request['codigo_barrasArt'.$i];
			    	}

				    $materiaPrima->codigo_barras = $cb;
			    	$materiaPrima->subcategoria_id = $subcategoria_id;
					$paths =  ImageController::upload( $request->file('imagenesArt'.$i), $nameMat );
		    		$materiaPrima->imagenes = $paths;
		    		$materiaPrima->save();
		    		// EXTRAIGO EL ID DEL REGISTRO INSERTADO
				    $materiaPrima = InsumoComprado::where(['nombre' => $nameMat, 'codigo_barras' => $cb, 'subcategoria_id' => $subcategoria_id ] )->get()[0];
	    		}
    		}

            echo "HOLA MUNDO 4<br>";
	    	$cantidad = $request['cantidadArt'.$i];
    		$compraDetalles = new CompraProvDet();
            // dd($materiaPrima);
	    	$compraDetalles->materia_prima_id = $materiaPrima->id;
	    	$compraDetalles->compra_prov_id = $compra_id;
	    	$compraDetalles->precioUnitario = $request['precioArt'.$i];
	    	$compraDetalles->cantidad = $cantidad;
	    	$compraDetalles->numFactura = $compra->numFactura;
            $subTotal = floatval($request['precioArt'.$i]) * floatval($cantidad);
            $totalCompra = $subTotal + $totalCompra;
            $compraDetalles->save();

	    	// EXTRAIGO EL ID DEL REGISTRO INSERTADO
            array_push($compra_det, CompraProvDet::where(['numFactura' => $numFactura])->get()[ $i-1 ]->id);
            array_push($almacen_id, $this->isEncryp($request['almacenArt'.$i]));

            $al_id = $this->isEncryp($request['almacenArt'.$i]);


            if ($almacen_id !=0) {
                echo "HOLA MUNDO 5<br>";
                $almacen = Almacen::find($al_id);
    	    	$almacen->capacidadActual = $almacen->capacidadActual + $cantidad;
    	    	// RESTO LA CANTIDAD DE PRODUCTOS AL ALMACEN

    	    	if ( $almacen->capacidadActual >= $almacen->max ) {
                    echo "HOLA MUNDO 6<br>";
    	    		$almacen->maxCapacidad = 'Si';
    	    		$rest =  $almacen->capacidadActual - $almacen->max;
    		    	$_SESSION['notifications'][] = "Alerta: El almacen $almacen->descripcion ha alcanzado su máxima capacidad. El articulo $nameMat fue registrado en dicho almacen, este ha superado su máxima capacidad con $rest artículos de más. Todas sus operaciones fueron guardadas éxitosamente, sin embargo, se recomienda gestionar el almacenamiento lo más pronto posible para permitirle al sistema llevar un mejor control. <a href='".route('dashboard.suppliers.query')."'>Gestionar Almacenamiento</a>";
    		    	$rest = 0 - $rest;
                    array_push($resto, $rest);

    	    	}else{
                    echo "HOLA MUNDO 7<br>";
    	    		$rest = 0;
                    array_push($resto, $rest);
    	    	}
    	    	$almacen->save();
            }else{
                echo "HOLA MUNDO 8<br>";
                $rest = 0;
                array_push($resto, $rest);
            }


            echo "HOLA MUNDO 9<br>";

    	}
    	// MATERIA PRIMA ALMACENDA EN:
        for ($i=0; $i < count($compra_det); $i++) {
	    	$mAlmacenada = new InsumoAlmacenado();
	    	$mAlmacenada->compra_prov_det_id = $compra_det[$i];
	    	$mAlmacenada->almacen_id = $almacen_id[$i];
	    	$mAlmacenada->resto = $resto[$i];
	    	$mAlmacenada->save();
        }
        // fin del for

        echo "HOLA MUNDO 10<br>";
        $compra2 = Compra::find($compra_id);
        $compra2->totalCompra = $totalCompra;
        $compra2->save();

        // echo $totalCompra;
    	// echo "Éxito todo salio bien";

    	$_SESSION['notifications'][] = "Se ha registrado la compra correctamente.";
    	return redirect()->route('dashboard.suppliers.sale.view.create');
    }

    // VISTA CONSULTA, PAGINADO POR CADA 50 REGISTROS
    public function show() {
    	// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }


        $compras = Compra::orderBy('created_at', 'desc')->paginate(50);

        return view(
        	'dashboard.suppliers_sale.query',
        	[
        		'user' => $this->user,
                'compras' => $compras,
        		'notifications' => $this->auth->notifications(),
        	]
        );
    }

    // VISTA BUSCAR, PAGINADO POR CADA 50 REGISTROS
    public function search(Request $request) {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }


        $compras = '';
        if( $request->facture !== NULL && $request->date !== NULL ){
            $compras = Compra::where([
                'numFactura' => $request->facture,
                'fechaCompra' => $request->date,
            ])->get();
        }else if( $request->dateOf !== NULL ){
            $start = Carbon::parse($request->dateOf)->format('Y-m-d');
            $end = Carbon::now();
            $compras = Compra::whereBetween('fechaCompra', [$start, $end ]) ->get();
        }
        else{
            if ( $request->facture !== NULL ) {
                $compras = Compra::where('numFactura', $request->facture)->get();
            }
            else if ( $request->date !== NULL ) {
                $compras = Compra::where('fechaCompra', $request->date)->get();
            }
        }


        // else{
        //     $cantidad = array();
        //     foreach ($compras as $compra) {
        //         $id = $compra->id;
        //         $query = DB::select('SELECT SUM(cantidad) AS cantidad FROM compras_prov_det WHERE compra_prov_id = '.$id )[0]->cantidad;
        //         if ( $query == '' ) {
        //             $query = 0;
        //         }
        //         $cantidad['compra_prov_id'.$id] = $query;
        //     }
        // }


        return view(
            'dashboard.suppliers_sale.squery',
            [
                'user' => $this->user,
                'compras' => $compras,
                'date' => $request->date,
                'facture' => $request->facture,
                'dateOf' => $request->dateOf,
                'notifications' => $this->auth->notifications(),
            ]
        );
    }

    public function query($id)
    {
        // REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        $id = TokenController::desencriptar($id);
        $proveedor = Compra::find($id);
        $productos = CompraProvDet::where(['compra_prov_id' => $id])->get();
        $almacenes = array();
        for ($i=0; $i < count($productos); $i++) {
            // echo "<br>ID: ".$productos[$i]->id."<br>";
            $m = InsumoAlmacenado::where(['compra_prov_det_id' => $productos[$i]->id ])->get();
            if ( isset($m[0]) ) {
                $almacenes[$productos[$i]->id] = $m[0]->almacen->descripcion;
            }else{
                $almacenes[$productos[$i]->id] = '';
            }

        }

        return view(
            'dashboard.suppliers_sale.query_id',
            [
                'user' => $this->user,
                'info' => $proveedor,
                'productos' => $productos,
                'almacenes' => $almacenes,
                'notifications' => $this->auth->notifications(),
            ]
        );
    }

    // ELIMINAR, SE SOLICITA ACCESO ROOT
    public function destroy($id) {
    	// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        if ($this->auth->isRoot() === false) {
        	$_SESSION['notifications'] = array('success' => 'No tienes el acceso requerido, se solicita ser usuario Administrador');
        	return redirect()->route('dashboard.index');
        }

        // BUSCAMOS EL REGISTRO compra_prov
        $id = $this->isEncryp($id);
        Compra::find($id)->delete();
        $_SESSION['notifications'] = array('warning' => 'El registro ha sido eliminado');
        return redirect()->route('dashboard.suppliers.sale.query');

    }
    public function viewUpdate($id) {
    	// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }

        $_id = $this->isEncryp($id);
        $compraProv = Compra::find($_id);
        $query = DB::select('SELECT COUNT(id) AS cantidad FROM compras_prov_det WHERE compra_prov_id = '.$_id )[0]->cantidad;
        $r = array();
        for ($i=0; $i < $query; $i++) {
            $d = $compraProv->comprasDetalles[$i]->id;
            $s = InsumoAlmacenado::where([ 'compra_prov_det_id' => $d ])->get();
            if (isset($s[0])) {
                $r[$d] = $s[0]->almacen->descripcion;
            }
        }
        return view(
            'dashboard.suppliers_sale.update',
            [
                'id' => $id,
                'user' => $this->user,
                'proveedores' => Proveedor::all(),
                'numArticulos' => $query,
                'compraProv' =>$compraProv,
                'warehouse' => Almacen::all(),
                'raw_material' => InsumoComprado::all(),
                'raw_warehouse' => $r,
                'subcategory' => Subcategoria::all(),
                'notifications' => $this->auth->notifications(),
            ]
        );
    }
    public function update(Request $request, $id) {
    	// REDIMENCIONA EN CASO DE NO ESTAR LOGUEADO
        if (is_array($this->user) == false ) { return $this->auth->redirect($this->authRoute); }


        // DATOS DE LA COMPRA
        $compraProvID = $this->isEncryp($id);
        $compraProv = Compra::find( $compraProvID );

        if ( $request->numFactura !== NULL ) { $compraProv->numFactura = $request->numFactura; }
        if ( $request->fechaCompra !== NULL ) { $compraProv->numFactura = $request->numFactura; }
        if ( $request->proveedor_id !== NULL ) { $compraProv->proveedor_id = $this->isEncryp($request->proveedor_id); }

        $totalCompra = 0;
        // DETALLES DE LA COMPRA - ARTICULOS COMPRADOS
        $numArticulos = intval($request->numArticulos);
        for ($i=0; $i < $numArticulos; $i++) {


            // MATERIA PRIMA

            $cantidad = $request['cantidadArt'.$i];
            $compraProvDetID = $this->isEncryp($request['comp-'.$i]);
            $compraDetalles = CompraProvDet::find( $compraProvDetID );

            if ( $request['raw_material_select'.$i] !== NULL ) { $compraDetalles->materia_prima_id = $this->isEncryp($request['raw_material_select'.$i]); }

            $compraDetalles->compra_prov_id = $compraProvID;
            $compraDetalles->precioUnitario = $request['precioArt'.$i];
            $compraDetalles->cantidad = $request['cantidadArt'.$i];
            $compraDetalles->numFactura = $compraProv->numFactura;
            $subTotal = floatval($request['precioArt'.$i]) * intval($cantidad);
            $totalCompra = $subTotal + $totalCompra;
            $compraDetalles->save();

            $resto = 0;
            // MATERIA PRIMA ALMACENDA EN:
            $mAlmacenada = InsumoAlmacenado::where('compra_prov_det_id', $compraProvDetID)->get()[0];
            // $mAlmacenada = InsumoAlmacenado::find($mAlmacenada->id);

            if ( $request['almacenArt'.$i] !== NULL ) {
                $almacen_id = $this->isEncryp($request['almacenArt'.$i]);

                if ($almacen_id !=0) {
                    $almacen = Almacen::find($almacen_id);
                    $almacen->capacidadActual = $almacen->capacidadActual + $cantidad;
                    // RESTO LA CANTIDAD DE PRODUCTOS AL ALMACEN

                    if ( $almacen->capacidadActual >= $almacen->max ) {
                        $almacen->maxCapacidad = 'Si';
                        $resto =  $almacen->capacidadActual - $almacen->max;
                        $_SESSION['notifications'][] = "Alerta: El almacen $almacen->descripcion ha alcanzado su máxima capacidad. Este ha superado su máxima capacidad con $resto artículos de más. Todas sus operaciones fueron guardadas éxitosamente, sin embargo, se recomienda gestionar el almacenamiento lo más pronto posible para permitirle al sistema llevar un mejor control. <a href='".route('dashboard.suppliers.query')."'>Gestionar Almacenamiento</a>";
                        $resto = 0 - $resto;
                    }else{
                        $resto = 0;
                    }

                    $almacen->save();

                }

                $mAlmacenada->almacen_id = $almacen_id;
                $mAlmacenada->resto = $resto;

            }


            $mAlmacenada->compra_prov_det_id = $compraProvDetID;
            // dd($mAlmacenada);
            $mAlmacenada->save();
        }

        $compraProv->totalCompra = $totalCompra;
        $compraProv->save();

        $_SESSION['notifications'][] = "Se ha actualizado correctamente.";
        return redirect()->route('dashboard.suppliers.sale.query');
    }
}
