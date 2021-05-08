<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthenticateController;
use Illuminate\Support\Facades\DB;
use App\Cliente;
use App\User;
use App\Vendedor;
use App\Sucursal;
use App\Proveedor;
use App\Categoria;
use App\Subcategoria;
use App\InsumoComprado;
use App\Almacen;
use App\Http\Controllers\ValidController;

class ExportFileController extends Controller
{

    public function pdfBackup(string $name, string $route) {
    	// Carga un vista
    	$pdf = \PDF::loadView($route);

        // Descarga la vista en PDF
        return $pdf->download($name.'.pdf');
    }
    public function view(string $table) {
    	$view = '';
    	$query = '';
    	$data = [
        	'fecha' => date('d/m/Y'),
        	'user' => $this->user,
        	'name' => 'report-tabla-'.$table.'-'.date('d/m/Y h:s:i'),
    	];

    	switch ($table) {
    		case 'users':
    			$view = 'dashboard.users.form.consult';
		    	$query = User::all();
		    	$pdf = \PDF::loadView('dashboard.report.exportPDF', [
			        'users' => $query,
			        'data' => $data,
			        'view' => $view,
			        'table' => $table,
				]);
    			break;
            case 'proveedores':
                $view = 'dashboard.suppliers.form.consult';
                $query = Proveedor::all();
                $pdf = \PDF::loadView('dashboard.report.exportPDF', [
                    'suppliers' => $query,
                    'data' => $data,
                    'view' => $view,
                    'table' => $table,
                ]);
                break;
            case 'clientes':
                $view = 'dashboard.customers.form.consult';
                $query = Cliente::all();
                $pdf = \PDF::loadView('dashboard.report.exportPDF', [
                    'customers' => $query,
                    'data' => $data,
                    'view' => $view,
                    'table' => $table,
                ]);
                break;
            case 'vendedores':
                $view = 'dashboard.sellers.form.consult';
                $query = Vendedor::all();
                $pdf = \PDF::loadView('dashboard.report.exportPDF', [
                    'sellers' => $query,
                    'data' => $data,
                    'view' => $view,
                    'table' => $table,
                ]);
                break;
            case 'sucursal':
                $view = 'dashboard.brach.form.consult';
                $query = Sucursal::all();
                $pdf = \PDF::loadView('dashboard.report.exportPDF', [
                    'brach' => $query,
                    'data' => $data,
                    'view' => $view,
                    'table' => $table,
                ]);
                break;
            case 'categoria':
                $view = 'dashboard.category.form.consult';
                $query = Categoria::all();
                $pdf = \PDF::loadView('dashboard.report.exportPDF', [
                    'category' => $query,
                    'data' => $data,
                    'view' => $view,
                    'table' => $table,
                ]);
                break;
            case 'subcategoria':
                $view = 'dashboard.subcategory.form.consult';
                $query = Subcategoria::all();
                $pdf = \PDF::loadView('dashboard.report.exportPDF', [
                    'subcategory' => $query,
                    'data' => $data,
                    'view' => $view,
                    'table' => $table,
                ]);
                break;
            case 'materia_prima':
                $view = 'dashboard.raw_material.form.consult';
                $query = InsumoComprado::all();
                $pdf = \PDF::loadView('dashboard.report.exportPDF', [
                    'raw_material' => $query,
                    'data' => $data,
                    'view' => $view,
                    'table' => $table,
                ]);
                break;
            case 'almacen':
                $view = 'dashboard.warehouse.form.consult';
                $query = Almacen::all();
                $pdf = \PDF::loadView('dashboard.report.exportPDF', [
                    'warehouse' => $query,
                    'data' => $data,
                    'view' => $view,
                    'table' => $table,
                ]);
                break;

    		default:
    			$view = 'dashboard.index';
    			break;
    	}

        return $pdf->download($data['name'].'.pdf');

        // return $pdf->stream($data['name'].'.pdf');

    }

    public function print(string $table) {
    	if (empty($_SESSION)){
    		session_start();
		}
		$tabla = DB::table( $table )->select($_REQUEST['campos'])->orderBy('updated_at', 'asc')->get();
		$data = [
			'delete' => false,
			'edit' => false,
		];
		foreach ($_REQUEST['campos'] as $indice => $campo) {
			$data['inputs'][$indice]['head'] = $_REQUEST['cabeceras'][$indice];
			$data['inputs'][$indice]['name'] = $campo;
			$data['inputs'][$indice]['value'] = '';
		}
		// INCREMENTAL
		foreach ($tabla as $indice => $valor) {
			$i = $indice;
			if ( isset($_GET['page']) ){
				if ( $_GET['page'] > 1 ){
					$max = $numberPag * $_GET['page'];
					$pagAnterior = $_GET['page'] - 1;
					$min = $numberPag * $pagAnterior;
					$indice2 = $indice;
					$i = $min + $indice2;
				}
			}

			$tabla[$indice]->incremental = $i;
		}
		$view = 'general';
		$query = $tabla;
		$a = [
			'name' => 'report-tabla-'.$table.'-'.date('d/m/Y h:s:i'),
			'fecha' => date('d/m/Y'),
			'view' => $view,
			'tabla' => $tabla,
			'print' => true,
			'nameTable' => $table,
			'data' => $data,
			'fields' => $_REQUEST['campos'],
			'columns' => $_REQUEST['cabeceras'],
			'favicon' => $_SESSION['data']['favicon'],
			'logo' => $_SESSION['data']['logo'],
			'title' => $_SESSION['data']['title'],
			'titleAll' => $_SESSION['data']['title-all'],
			'address' => $_SESSION['data']['address'],
			'zip_postal' => $_SESSION['data']['zip_postal'],
			'username' => $_SESSION['username'],

		];
		$view = view('dashboard.report.exportPDF', $a);
		return $view;
	}
	public function pdf(string $table) {
		$name = 'report-tabla-'.$table.'-'.date('d/m/Y h:s:i');
		if (empty($_SESSION)){
			session_start();
		}
		$tabla = DB::table( $table )->select($_REQUEST['campos'])->orderBy('updated_at', 'asc')->get();
		$data = [
			'delete' => false,
			'edit' => false,
		];
		foreach ($_REQUEST['campos'] as $indice => $campo) {
			$data['inputs'][$indice]['head'] = $_REQUEST['cabeceras'][$indice];
			$data['inputs'][$indice]['name'] = $campo;
			$data['inputs'][$indice]['value'] = '';
		}
		// INCREMENTAL
		foreach ($tabla as $indice => $valor) {
			$i = $indice;
			if ( isset($_GET['page']) ){
				if ( $_GET['page'] > 1 ){
					$max = $numberPag * $_GET['page'];
					$pagAnterior = $_GET['page'] - 1;
					$min = $numberPag * $pagAnterior;
					$indice2 = $indice;
					$i = $min + $indice2;
				}
			}

			$tabla[$indice]->incremental = $i;
		}
		$view = 'general';
		$query = $tabla;
		$a = [
			'name' => 'report-tabla-'.$table.'-'.date('d/m/Y h:s:i'),
			'fecha' => date('d/m/Y'),
			'view' => $view,
			'tabla' => $tabla,
			'print' => true,
			'nameTable' => $table,
			'data' => $data,
			'fields' => $_REQUEST['campos'],
			'columns' => $_REQUEST['cabeceras'],
			'favicon' => $_SESSION['data']['favicon'],
			'logo' => $_SESSION['data']['logo'],
			'title' => $_SESSION['data']['title'],
			'titleAll' => $_SESSION['data']['title-all'],
			'address' => $_SESSION['data']['address'],
			'zip_postal' => $_SESSION['data']['zip_postal'],
			'username' => $_SESSION['username'],

		];
		$pdf = \PDF::loadView('dashboard.report.exportPDF', $a);
		// Descarga la vista en PDF
		return $pdf->stream($name.'.pdf');
		return $pdf->download($name.'.pdf');

		// $dompdf = new DOMPDF(); // Create new instance of dompdf
	 //    $dompdf->load_html($pdf_html); // Load the html
	 //    $dompdf->render(); // Parse the html, convert to PDF
	 //    $pdf_content = $dompdf->output(); // Put contents of pdf into variable for later

		
	}

}
