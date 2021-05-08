<?php
    $list_option = [
        'show proveedores' => [
			'link' => route('proveedores.index'),
            'titulo' => 'Mostrar proveedores',
            'icon' => 'fas fa-user-tie',
        ],
        'show compra' => [
			'link' => route('compras.index'),
            'titulo' => 'Mostrar compras',
            'icon' => 'fas fa-weight-hanging',
        ],
        'show vendedor' => [
			'link' => '',
            'titulo' => 'Mostrar vendedores',
            'icon' => 'fas fa-users',
        ],
        'show cliente' => [
			'link' => route('clientes.index'),
            'titulo' => 'Mostrar clientes',
            'icon' => 'fas fa-user-alt',
        ],
        'show sucursal' => [
			'link' => route('sucursal.index'),
            'titulo' => 'Mostrar sucursales',
            'icon' => 'fas fa-code-branch',
        ],
        'show categoria' => [
			'link' => route('categoria.index'),
            'titulo' => 'Mostrar categorías',
            'icon' => 'fas fa-box',
        ],
        'show subcategoria' => [
			'link' => route('subcategoria.index'),
            'titulo' => 'Mostrar subcategorías',
            'icon' => 'fas fa-box',
        ],
        'show almacen' => [
			'link' => route('almacen.index'),
            'titulo' => 'Mostrar almacenes',
            'icon' => 'fas fa-warehouse',
        ],
        'show presupuesto' => [
			'link' => '',
            'titulo' => 'Mostrar presupuestos',
            'icon' => 'fas fa-users',
        ],
        'show usuario' => [
            'link' => route('users.index'),
            'titulo' => 'Mostrar usuarios',
            'icon' => 'fas fa-users',
        ],
        'register proveedores' => [
            'link' => route('proveedores.create'),
            'titulo' => 'Registrar proveedor',
            'icon' => 'fas fa-user-tie',
        ],
        'register compra' => [
            'link' => route('compras.create'),
            'titulo' => 'Registrar compra',
            'icon' => 'fas fa-weight-hanging',
        ],
        'register vendedor' => [
			'link' => '',
            'titulo' => 'Registrar vendedor',
            'icon' => 'fas fa-users',
        ],
        'register cliente' => [
			'link' => route('clientes.create'),
            'titulo' => 'Registrar cliente',
            'icon' => 'fas fa-user-alt',
        ],
        'register sucursal' => [
			'link' => route('sucursal.create'),
            'titulo' => 'Registrar sucursal',
            'icon' => 'fas fa-code-branch',
        ],
        'register categoria' => [
			'link' => route('categoria.create'),
            'titulo' => 'Registrar categoria',
            'icon' => 'fas fa-box',
        ],
        'register subcategoria' => [
			'link' => route('subcategoria.create'),
            'titulo' => 'Registrar subcategoría',
            'icon' => 'fas fa-box',
        ],
        'register almacen' => [
			'link' => route('almacen.create'),
            'titulo' => 'Registrar almacen',
            'icon' => 'fas fa-warehouse',
        ],
        'register presupuesto' => [
			'link' => '',
            'titulo' => 'Registrar presupuesto',
            'icon' => 'fas fa-users',
        ],
        'register usuario' => [
			'link' => route('users.create'),
            'titulo' => 'Registrar usuario',
            'icon' => 'fas fa-users',
        ],
    ];
    if ( !empty($related_options) ) {
        $anothers_options = [];
        foreach ($related_options as $option) {
            if ( !empty($list_option[$option]) ) {
                $anothers_options[] = $list_option[$option];
            }
        }
    }
?>
<div class="col-12 mt-4">
    <h4 class="text-center">Relacionado</h4>
</div>
<div class="col-12 row">
    @foreach($anothers_options as $another)
        <div class="col mt-4">
            <a <?php if (isset($another['target'])) { echo 'target="'.$another['target'].'"'; } ?> href="{{ $another['link'] }}" class="col-lg-10 offset-lg-1 card" <?php if ( isset($another['isModal']) ) { echo $another['isModal']; } ?>>
                <div class="card-body">
                    <div class="container text-center" style="font-size: 3em">
                        <i class="{{ $another['icon'] }}"></i>
                        <h6>{{ $another['titulo'] }}</h6>
                    </div>
                </div>
            </a>
        </div>
        <?php if ( isset($another['modal']) ) { echo $another['modal']; } ?>
    @endforeach
</div>
