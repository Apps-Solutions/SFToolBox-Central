<?php
require_once DIRECTORY_CLASS . "class.contact.php";

if ( IS_ADMIN ){ 
	if ( isset( $_GET['u'] ) && $_GET['u'] > 0 ){ 
		require_once DIRECTORY_CLASS . "class.user.php";
		$id_user =  $_GET['u']; 
		$user = new User( $id_user ); 
		$contact = $user->contact; 
	} else if ( isset( $_GET['idc'] ) && is_int( $_GET['idc'] ) && $_GET['idc'] > 0 ){ 
		$contact = new Contact( $_GET['idc'] );	
	} else { 
		$contact = new Contact();
	}
	
} else {
	global $Session; 
	require_once DIRECTORY_CLASS . "class.user.php";
	$user = new User( $Session->get_id() );
	$contact = $user->contact;
}

?>
<script>
$(document).ready(function() {
	$.validate({
		form : '#frm_contact',
		language : validate_language
		//, modules : 'file'
	});
});
</script>
<div id="section-header" class="row">
	<div class="col-xs-12 ">
		<h2> Contacto </h2>
	</div> 
</div> 
<div id='form-content' class=' content-info row '> 
	<form id="frm_contact" class="form-horizontal" role="form" method="post" action="agenda.php" enctype="multipart/form-data" >
	<div class="col-xs-12 col-sm-12"> 
		<fieldset class="row">  
			<div class="row ">
				<div class="col-xs-12 col-sm-6">  
					<label>Tipo de contacto</label>
					<?php if ( IS_ADMIN ) { ?>
						<select class="form-control" id='inp_contact_type' name='contact_type' class="form-control" 
							<?php echo ( $contact->id_user > 0 ) ? "disabled='disabled'" : "" ?> onchange='check_type();'>
						<?php 	echo $catalogue->get_catalgue_options( 'contact_type', $contact->id_type ) ?>
						</select> 
					<?php 	} else {
								echo  "<span>" . $contact->type . "</span>"; 
					 		}
					 ?>
				</div>  
				<div class="col-xs-12 col-sm-6">  
					<label>Usuario</label>
					<?php if ( IS_ADMIN ) { ?>
						<select class="form-control" id='inp_co_us_id_user' name='co_us_id_user' class="form-control"
							<?php echo ( $contact->id_type == 2 ) ? "disabled='disabled'" : "" ?> onchange='check_type();'>
						<?php echo $catalogue->get_catalgue_options( 'users_contact_edition', $contact->id_user, 'Sin relación de usuario', $contact->id_user ) ?>
						</select> 
					<?php 	} else {
								echo  "<span>" . $usuario->user . "</span>"; 
					 		}
					 ?>
				</div> 
			</div>
		</fieldset>
		<div class="row "> &nbsp; </div>
		<fieldset class="row">
			<div class="row ">
				<legend class="border-bottom"> Información personal </legend>
			</div>
			<div class="row ">
				<div class="col-xs-12 col-sm-6">  
					<label>Nombre</label> 
					<input type="text" id="inp_name" name="name" class="form-control"
						data-validation="required" value="<?php echo $contact->name ?>" /> 
				</div>
				<div class="col-xs-12 col-sm-6">  
					<label>Apellido</label> 
					<input type="text" id="inp_lastname" name="lastname" class="form-control"
						 value="<?php echo $contact->lastname ?>" /> 
				</div>
			</div>
			<div class="row ">
				<div class="col-xs-12 col-sm-6"> 
					<label >Sexo</label>
					<div class='row'> 
					<div class="col-xs-6"> 
						<div class="input-group"> 
							<span class="input-group-addon"> 
								<input type="radio" id="inp_sex_M" name="sex" data-validation="required" value="M" 
									<?php echo ($contact->sex == 'M' ? "checked='checked'" : "" ) ?> /> 
							</span> 
							<label class="form-control"><i class='fa fa-male'></i> M </label> 
						</div>
					</div> 
					<div class="col-xs-6"> 
						<div class="input-group"> 
							<span class="input-group-addon"> 
								<input type="radio" id="inp_sex_F" name="sex" data-validation="required" value="F" 
									<?php echo ($contact->sex == 'F' ? "checked='checked'" : "" ) ?> />
							</span> 
							<label class="form-control"><i class='fa fa-female'></i> F </label> 
						</div>
					</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">  
					<label>E-mail</label> 
					<input type="email" id="inp_email" name="email" class="form-control"
						data-validation="email" data-validation-optional="true" 
						value="<?php echo $contact->email ?>" /> 
				</div>
			</div>
			<div class="row ">
				<div class="col-xs-12 col-sm-6">  
					<label>Teléfono</label> 
					<input type="text" id="inp_telephone" name="telephone" class="form-control"
						data-validation="required" value="<?php echo $contact->telephone ?>" /> 
				</div>
				<div class="col-xs-12 col-sm-6">  
					<label>Celular</label> 
					<input type="text" id="inp_cellphone" name="cellphone" class="form-control"
						 value="<?php echo $contact->cellphone ?>" /> 
				</div>
			</div>
		</fieldset> 
		<div class="row "> &nbsp; </div>
		<fieldset class="row"> 
			<div class="row ">
				<legend class="border-bottom"> Imágen </legend>
			</div>
			<div class="row ">  
				<div class="col-xs-12 col-sm-6"> 
					<div class="input-group"> 
						<span class="input-group-addon" style="height:60px;"> 
							<img src='<?php echo $contact->avatar ?>' height='45' /> 
						</span>
						<input type='file' id='inp_avatar' name='avatar' class="form-control" style="height:60px;" />   
					</div>
				</div> 
			</div>
		</fieldset>
		<div class="row "> &nbsp; </div>
		<fieldset class="row" style="margin: 10px 0;"> 
			<div class="row ">
				<legend class="border-bottom"> Información de contacto </legend>
			</div>
			<?php echo $contact->meta->get_frm_html( 'inp_',"col-xs-12 col-sm-6"   ); ?> 
		</fieldset>
		<div class="row "> &nbsp; </div>
		<div class="row border-top" style="margin: 10px 0;"></div>
		<div class="row ">
			<input type="hidden" name="action" value="edit_contact" />
			<input type="hidden" name="id_contact" value="<?php echo $contact->id_contact ?>" />
			<input type="hidden" name="cb" value="<?php echo $_GET['cb'] ?>" />
			<button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Cancelar </button>
			<button type="submit" class="btn btn-default pull-right"><i class="fa fa-check"></i> Guardar </button>
		</div>
	</div>
	</form>
</div>