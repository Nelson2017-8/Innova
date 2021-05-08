<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;

//class TableExport implements FromCollection
class TableExport implements FromArray
{
//	public function collection()
	public function array(): array
	{
		$tabla = DB::table( $_REQUEST['table'] )->select($_REQUEST['campos'])->orderBy('updated_at', 'asc')->get();
		$tabla2 = [$_REQUEST['cabeceras']];
//		dd( $tabla );
//		$tabla = array_unshift(
//			$tabla,
//			$_REQUEST['cabeceras']);
		foreach ($tabla as $key1 => $objeto) {
			foreach ($objeto as $key2 => $value) {
				if ($key2 == 'updated_at' || $key2 == 'create_at'){
					$tabla2[$key1+1][] = Carbon::parse($value)->format('m/d/Y');
				}else{
					$tabla2[$key1+1][] = $value;
				}
//				dd($key2);
			}
		}
//		dd( $tabla2 );
//		dd( $_REQUEST['cabeceras'] );

        return $tabla2;
    }
}
