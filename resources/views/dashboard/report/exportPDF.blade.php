<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Vista previa - Reporte PDF</title>
        <link rel="icon" href="{{ $favicon }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/adminLTE/css/adminlte.min.css') }}">
        <style>
            .salto-pagina {
                page-break-after: always;
            }
            /* Salto de pagina*/
            hr {
              page-break-after: always;
              border: 0;
              margin: 0;
              padding: 0;
            }
            table{
                font-size: x-small;
            }
            tfoot tr td{
                font-weight: bold;
                font-size: x-small;
            }

            @page {
                margin: 0cm 0cm;
                font-family: Arial;
            }
            header {
                position: relative;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #fff;
                color: black;
                text-align: center;
                line-height: 1.5cm;
            }

            footer {
                /*position: fixed; !** APACERE EN TODAS LAS PAGINAS **!*/
                position: static;
				/*margin-top: 1em;*/
                bottom: 2em;
                left: 1cm;
                right: 1cm;
                height: .5cm;

                /** Extra personal styles **/
                background-color: #fff;
                color: black;
                text-align: center;
                line-height: 1.5cm;
            }

            /** Defina ahora los márgenes reales de cada página en el PDF **/
            body {
                margin-top: 2cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }


            footer a{
                color: #DD2C00
            }
            footer a:hover{
                color: #B71C1C
            }
            main table, main .table td, main .table th{
                font-size: 12px!important;
            }
			
			header table{
				margin-top: 0em;
				margin-left: 0em
			}

		</style>
    </head>
    <body id="exportable">
        <header class="w-100">
            <table border="0" class="w-100">
                <tbody>
                    <tr>
                        <td style="width: 200px;" class="text-right" class="logo">
                            <img style="margin-left: 50px; margin-top: 20px; margin-right: 20px;" width="120" src="{{ $logo }}">
                        </td>
                        <th class="text-left">
                            <h3 class="pt-3">{{ $titleAll }}</h3>
                        </th>
                    </tr>
                </tbody>
            </table>
        </header>
        <main class="container">
            <div class="w-100">
                <table class="table table-striped table-sm table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th colspan="2">Reporte</th>
                            <th colspan="2">Empresa</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $name }}</td>
                            <th>Nombre:</th>
                            <td>{{ $titleAll }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de emisión:</th>
                            <td>{{ $fecha }}</td>
                            <th>Ubicación:</th>
                            <td>{{ $address }}</td>
                        </tr>
                        <tr>
                            <th>Usuario que emitio el reporte:</th>
                            <td>{{ $username }}</td>
                            <th>Código Postal:</th>
                            <td>{{ $zip_postal }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-100 mt-5">
                <h3>Reporte "{{ $nameTable }}"</h3>
            </div>
            <div class="w-100">
                @include('tablas.'.$view)
            </div>
        </main>




{{--        <footer>--}}
{{--            <div class="px-4 w-100 mb-5 mb-5">--}}
{{--                <table border="0" class="w-100 mb-5">--}}
{{--                    <tbody>--}}
{{--                        <tr>--}}
{{--                            <td class="text-left">--}}
{{--                                <h6 class="text-left">Link de la aplicación: <a href="{{ url('/') }}" title="{{ $_SESSION['data']['title'] }}">{{ url('/') }}</a></h6>--}}
{{--                            </td>--}}
{{--                            <th class="text-right">--}}
{{--                                <h6 class="text-right">Conocer a los <a href="{{ url('/developers') }}">Desarrolladores</a></h6>--}}
{{--                            </th>--}}
{{--                        </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </footer>--}}

        @if( isset($print) )
            <script>
                document.body.onload = function(){
                    if ( window.print ) {
                        window.print();
                        setInterval(function () {
                            window.close();
                        }, 3000)
                    }
                }
            </script>
        @endif
    </body>
</html>
