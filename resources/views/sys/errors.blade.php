<!-- ERRORES DEL SISTEMA -->
@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible">
        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        	<h5><i class="icon fas fa-ban"></i> Alerta!</h5>
        	<?php echo $error; ?>
        </div>
    @endforeach

@endif


<!-- NOTIFICACIONES -->
@if( isset($_SESSION['notifications']) )
	@if(count($_SESSION['notifications']) > 0)
        @foreach($_SESSION['notifications'] as $key => $value )
        	@if($key == 'error')
	        	<div class="alert alert-danger alert-dismissible">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                	<h5><i class="icon fas fa-ban"></i> Alerta!</h5>
                	<?php echo $value; ?>
                </div>
        	@elseif($key == 'info')
        		<div class="alert alert-info alert-dismissible">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                	<h5><i class="icon fas fa-info"></i> Información</h5>
                	<?php echo $value; ?>
                </div>
        	@elseif($key == 'success')
        		<div class="alert alert-success alert-dismissible">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                	<h5><i class="icon fas fa-check"></i> Éxito</h5>
                	<?php echo $value; ?>
                </div>
        	@elseif($key == 'warning')
        		<div class="alert alert-warning alert-dismissible">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                	<h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
                	<?php echo $value; ?>
                </div>
        	@endif
        @endforeach
	@endif
	<?php unset($_SESSION['notifications']); ?>
@endif
