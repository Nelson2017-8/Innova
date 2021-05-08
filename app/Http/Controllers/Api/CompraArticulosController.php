<?php

namespace App\Http\Controllers\Api;

use App\Compra;
use App\Http\Controllers\Controller;
use App\InsumoComprado;
use App\Subcategoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompraArticulosController extends Controller
{

    public function index()
    {
        //
    }

    public function create(Request $request)
    {
		$compra = Compra::where('numFactura', $request->numFactura)->get();
		$subcategorias = Subcategoria::select('id', 'nombre')->get();
//		dd($subcategoria);
		if ( !isset($compra[0]) ){
			return abort(404);
		}else{
			return view('dashboard.compraArticulos.create',
				[
					'compra' => $compra,
					'subcategorias'=>$subcategorias,
				]
			);
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$compra = Compra::where('numFactura', $request->numFactura)->get();
    	if (!isset($compra[0])){
    		return false;
		}
//    	$datos = [];
    	$precioTotal = 0;
    	for ($i=0; $i < count($request->nombre); $i++ ){
			$precioTotal += $request->precio[$i] * $request->cantidad[$i];
//			$datos['nombre'][] = $request->nombre[$i];
//			$datos['cantidad'][] = $request->cantidad[$i];
//			$datos['precioUnitario'][] = $request->precio[$i];
//			$datos['codigo_barras'][] = $request->codBarras[$i];
//			$datos['detalles'][] = $request->detalles[$i];
//			$datos['subcategoria_id'][] = $request->subcategoria[$i];
//			$datos['compra_id'][] = $compra[0]->id;


			$articulo = new InsumoComprado();
			$articulo->nombre = $request->nombre[$i];
			$articulo->cantidad = $request->cantidad[$i];
			$articulo->precioUnitario = $request->precio[$i];
			$articulo->codigo_barras = $request->codBarras[$i];
			$articulo->detalles = $request->detalles[$i];
			$articulo->subcategoria_id = $request->subcategoria[$i];
			$articulo->compra_id = $compra[0]->id;
			$articulo->save();
		}
		$compra[0]->precioCompra = $precioTotal;
		$compra[0]->save();

		$respuesta = [
			'messenger' => 'Los articulos de la compra han sido registrado correctamente',
			'result' => 'success',
			'redirect' => route('compras.index')
		];
    	return JsonResponse::create($respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InsumoComprado  $insumoComprado
     * @return \Illuminate\Http\Response
     */
    public function show(InsumoComprado $insumoComprado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InsumoComprado  $insumoComprado
     * @return \Illuminate\Http\Response
     */
    public function edit(InsumoComprado $insumoComprado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InsumoComprado  $insumoComprado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InsumoComprado $insumoComprado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InsumoComprado  $insumoComprado
     * @return \Illuminate\Http\Response
     */
    public function destroy(InsumoComprado $insumoComprado)
    {
        //
    }
}
