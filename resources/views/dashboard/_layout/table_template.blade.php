@if(isset($table_template) || !empty($_GET['ajax']))
	@if(empty($_GET['ajax']))
		<article class="lista-resultado col-12">
	@endif
		<div class="card card-table">
			<div class="card-header bg-primary py-1" style="line-height: 20px!important;">
				<div class="row">
					<!-- TITLE CARD -->
					<div class="col-sm-5">
						<h3 class="text-white" style="font-size: 18px;padding-top: 10px;"><i class="{{ $icoCard }} px-3"></i>{{ $titleCard }}</h3>
					</div>
					<!-- GROUP BUTTONS -->
					<div class="col-sm-7">
						<div class="btn-group float-right">
							<!-- BUTTON ORDENAR -->
							@if( $data['orderBy'] === true )
								<div class="dropdown">
									<button class="btn rounded-0 btn-transparent text-white buttons-collection dropdown-toggle buttons-colvis" tabindex="0" aria-controls="control-sd-1" type="button" aria-haspopup="true" aria-expanded="false" id="orderBy" data-toggle="dropdown">
										<i class="fi fi-nav-icon-list pr-2"></i>Ordenar por
									</button>
									<div class="dropdown-menu" aria-labelledby="orderBy">
										@foreach($data['camposSearch'] as $key => $value)
											@if(!empty($value))
												<a href="{{ route($routePath.'.index', ['orderBy' => $value, 't' => $nameTable]) }}" class="dropdown-item"><i class="fi fi-preview pr-2"></i>{{ $key }}</a>
											@endif
										@endforeach
											<a href="{{ route($routePath.'.index') }}" class="dropdown-item"><i class="fi fi-preview pr-2"></i>Default</a>
									</div>
								</div>
							@endif

							<!-- BUTTON EXPORTAR -->
							@if( $data['export'] === true )
								<div class="dropdown">
									<button class="btn rounded-0 btn-transparent text-white buttons-collection dropdown-toggle buttons-colvis" tabindex="0" aria-controls="control-sd-2" type="button" aria-haspopup="true" aria-expanded="false" id="btn-group-exportar" data-toggle="dropdown">
										<i class="fi fi-download pr-2"></i>Exportar
									</button>
									<div class="dropdown-menu" aria-labelledby="btn-group-exportar">
										<?php
											$campos = [];
											$cabeceras = [];
											foreach ($data['inputs'] as $key => $value){
												$campos[] = $value['name'];
												$cabeceras[] = $value['head'];
											}
										?>
										<a class="dropdown-item" id="generate-pdf" href="{{ route('get-data-pdf', ['table' => $nameTable, 'input' => $campos, 'cabeceras' => $cabeceras ]) }}"><i class="fa fa-file-pdf pr-2"></i> PDF JAVASCRIPT</a>
										<a class="dropdown-item" target="new" href="{{ route('export-pdf', ['table' => $nameTable, 'campos' => $campos, 'cabeceras' => $cabeceras ]) }}"><i class="fa fa-file-pdf pr-2"></i> PDF PHP</a>
										<a class="dropdown-item" href="{{ route('export-excel', ['table' => $nameTable, 'campos' => $campos, 'cabeceras' => $cabeceras ] ) }}"><i class="fa fa-file-excel pr-2"></i> Excel</a>
										<a class="dropdown-item" target="new" href="{{ route('export-print', ['table' => $nameTable, 'campos' => $campos, 'cabeceras' => $cabeceras ] ) }}"><i class="fi fi-print pr-2"></i> Imprimir</a>
									</div>
								</div>
							@endif

							<!-- BUTTON REGISTRAR -->
							@if( $data['add'] === true )
								<div>
									<a href="{{ $add_register }}" class="btn rounded-0 btn-transparent text-white buttons-collection">
										<i class="fi fi-plus-a pr-2"></i>Agregar Registro
									</a>
								</div>
							@endif

							<!-- BUTTON TOGGLE DELETE -->
							@if( $data['delete'] === true )
								<div>
									<a href="#" id="toggle-delete-item-table" class="btn rounded-0 btn-transparent text-white buttons-collection">
										<i class="fas fa-trash pr-2"></i>Eliminar Registro
									</a>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body p-0">
				@if( $data['autocompleteAjax'] === true )
				<div class="row">
					<div class="col-sm-12">
						<div class="row">

							<!-- AJAX FORM SEARCH -->
							<div class="col-12">
								<div id="example1_filter" class="dataTables_filter">
									<div class="form-group px-0 my-0 col-12">
										<form action="{{ $dataUrl }}" class="form-ajax-input-table" method="get">
											<div class="input-group">
												<div class="dropdown">
													<button class="btn rounded-0 border btn-light px-4 dropdown-toggle" tabindex="0" aria-controls="control-sd-2" type="button" aria-haspopup="true" aria-expanded="false" id="btn-group-exportar" data-toggle="dropdown">
														<span class="small" id="dropdown-input-search">{{ array_key_first($data['camposSearch']) }}</span>
													</button>
													<div class="dropdown-menu" aria-labelledby="orderBy">
														@foreach($data['camposSearch'] as $key => $value)
															<button type="button" data-key="{{ $key }}" data-value="{{ $value }}" class="dropdown-item select-input-search">
																<i class="fas fa-caret-right pr-2"></i>{{ $key }}
															</button>
														@endforeach
													</div>

												</div>
												<input type="hidden" value="{{ $nameTable }}" name="t">
												<input type="hidden" value="" name="input" class="input-search">
												<input data-action="{{ $dataUrlInputs }}" autocomplete="off" type="search" class="form-control rounded-0 autocomplete-input" name="search" id="search-{{ $nameTable }}" placeholder="Buscar" required>

												<span class="input-group-append">
													<button type="submit" class="search-btn-table btn btn-primary btn-flat">
														<i class="fas fa-search"></i>
													</button>
												</span>
												<!-- LISTADO DE SUGERENCIAS DROPDOWN -->
												<div class="suggestions"></div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
				@endif
				<div class="row py-0">
					<div class="col-12">
						@yield('table-'.$nameTable)
					</div>
				</div>
				@if( !isset($pag_links) )
				<div class="row">
					<div class="col-sm-12 col-md-5">
						<div class="dataTables_info" id="example2_info" role="status" aria-live="polite"> Se encontraron {{ $pagination['total_result'] }} resultados.
							@if($paginate_link !== '')
							Página {{ $pagination['page_actual'] }} de {{ $pagination['page_total'] }}
							@endif
						</div>
					</div>
					<div class="col-sm-12 col-md-7">
						<div class="dataTables_paginate paging_simple_numbers" id="pag-{{$nameTable}}">
							<?php echo $paginate_link; ?>
						</div>
					</div>
				</div>
				@endif
			</div>
			<!-- /.card-body -->
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
	@if(empty($_GET['ajax']))
		</article>
	@endif
@endif
