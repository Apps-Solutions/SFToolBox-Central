<?php
$option;
?>
<li class='list-group-item <?php echo $li_css ?>'> 
	<div class='col-xs-12 col-sm-3'>
		<div class='row'>
			<label class='col-xs-12' >Etiqueta:</label> 
			<span  class='col-xs-12' > <?php echo $option->label ?> </span>
		</div>
	</div> 
	<div class='col-xs-12 col-sm-3'>
		<div class='row'>
			<label class='col-xs-12' >Tipo de dato:</label>
			<span  class='col-xs-12' > <?php echo $option->data_type ?> </span>
		</div> 
	</div>
	<div class='col-xs-12 col-sm-3'>
		<div class='row'>
			<label class='col-xs-12' >Opciones:</label>
			<span class='col-xs-12' > <?php echo $option->options ?> </span> 
		</div>
	</div> 
	<div class='col-xs-12 col-sm-3 text-center'>
	<?php if ($edit && IS_ADMIN){ ?>
		<button onclick='edit_option(<?php echo $option->id_option ?>)' class='btn btn-default'  ><i class='fa fa-edit'></i> Editar </button> 
		<button onclick='delete_option(<?php echo $option->id_option ?>)' class='btn btn-default'><i class='fa fa-trash-o'></i> Borrar </button>
		<br/>
	<?php } ?> 
	</div>
</li>