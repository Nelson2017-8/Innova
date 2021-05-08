@if(isset($table_template))
<div class="col-12">
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title"><i class="{{ $icoCard }} px-3"></i>{{ $titleCard }}</h3>

        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="row py-0">
                <div class="col-sm-11 col-md-6 py-0 pr-0">
                    <div class="col-sm-12 p-0 dt-buttons btn-group flex-wrap">
                        <div class="dropdown">
                            <button class="btn rounded-0 btn-light buttons-collection dropdown-toggle buttons-colvis" tabindex="0" aria-controls="control-sd-1" type="button" aria-haspopup="true" aria-expanded="false" id="orderBy" data-toggle="dropdown">
                                <span class="small"><i class="fi fi-nav-icon-list pr-2"></i>Ordenar por</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="orderBy">
                                @foreach($table_headers as $key => $value)
                                    @if(!empty($value))
                                        <a data-href="?orderBy{{ '='.$value.'&t='.$table }}" class="dropdown-item get-links" onmouseenter="$(this).attr('href', location.origin + $(this).attr('data-href') ); console.log(location.origin + $(this).attr('data-href')) " href="?orderBy{{ '='.$value.'&t='.$table }}"><i class="fi fi-preview pr-2"></i>{{ $key }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="btn rounded-0 btn-light buttons-collection dropdown-toggle buttons-colvis" tabindex="0" aria-controls="control-sd-2" type="button" aria-haspopup="true" aria-expanded="false" id="btn-group-exportar" data-toggle="dropdown">
                                <span class="small"><i class="fi fi-download pr-2"></i>Exportar</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btn-group-exportar">
                                <a class="dropdown-item" href="{{ route('export-pdf', $table) }}"><i class="fa fa-file-pdf pr-2"></i> PDF</a>
                                <a class="dropdown-item" href="{{ $exportExcel }}"><i class="fa fa-file-excel pr-2"></i> Excel</a>
                                <a class="dropdown-item" target="new" href="{{ route('export-print', $table) }}"><i class="fi fi-print pr-2"></i> Imprimir</a>
                            </div>
                        </div>
                        <a href="{{ $add_register }}" class="btn rounded-0 btn-light buttons-collection" tabindex="0" aria-controls="control-sd-1" id="orderBy">
                            <span class="small"><i class="fi fi-plus-a pr-2"></i>Agregar Registro</span>
                        </a>
                    </div>
                </div>
                <!--<div class="bg-light col-md-2 col-sm-1 p-0"> </div> -->
                <div class="col-sm-12 col-md-6 p-0">
                    <div id="example1_filter" class="col-12 dataTables_filter p-0">
                        <div class="form-group pl-0 my-0 col-12">
                            <div class="input-group">
                                <input autocomplete="off" type="search" class="form-control rounded-0 autocomplete" data-url="{{ $dataUrl }}" name="search" id="{{ $table }}-consult" placeholder="Buscar" required data-input="" data-table="{{ $table }}">
                                <span class="input-group-append">
                                    <a href="#" data-area='{{ $table }}-consult' class="search-btn-table btn btn-primary btn-flat"><i class="fas fa-search"></i></a>
                                </span>
                                <div id="{{ $table }}-consult-suggestions" class="suggestions pa"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row py-0">
                <div class="col-12">
                    @yield('table-'.$table)
                </div>
            </div>
            @if( !isset($pag_links) )
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">   Se encontraron {{ $pagination['total_result'] }} resultados.
                        @if($paginate_link !== '')
                        Página {{ $pagination['page_actual'] }} de {{ $pagination['page_total'] }}
                        @endif
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="pag-{{$table}}">
                        <?php echo $paginate_link; ?>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
</div>

<!-- Modal -->
<div class="modal fade confirm-delete-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel " aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body pb-0">
        <h5 class="modal-title text-center text-danger" id="exampleModalLabel">
            <span style="font-size: 3em"><i class="fi fi-close"></i></span>
            <br>
            <span style="font-size: 1.5em">Alerta!</span>
        </h5>
        <p class="pt-3" style="font-size: 1em">
            Estas a punto de eliminar un registro <b>¿Estás seguro de querer continuar?</b>
        </p>
      </div>
      <div class="modal-footer border-0 mt-0 pt-0">
        <a href="#" class="btn btn-secondary" data-dismiss="modal">Cancelar</a>
        <a href="#" class="btn btn-danger confirm">Continuar</a>
      </div>
    </div>
  </div>
</div>

@endif
