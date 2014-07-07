<?php
require_once DIRECTORY_CLASS . "class.datatable.php";

global $Index; 
$listado = new DataTable('lst_admin_users');

$request = new stdClass;
$request->id_profile = (isset($_GET['id_pf']) && $_GET['id_pf'] > 0 ) ? $_GET['id_pf'] : 0;

if ( $request->id_profile > 0 ){
	$listado->set_filter( 'id_profile', $request->id_profile ); 
	$listado->fidx = 'id_profile';
	$listado->fval = $request->id_profile;
}

?>
<script>
	var id_profile 		=  <?php echo $request->id_profile ?>;
	var command 		= '<?php echo $Index->command;  ?>';
	var cmd_frm_contact = '<?php echo FRM_CONTACT;  ?>';
</script>
<div id="dashboard-header" class="row">
	<div class="col-xs-12 ">
		<h2> Usuarios </h2>
	</div>  
</div> 
<div id='users-content' class='row '> 
	<div class="col-xs-12 col-sm-12">
		<div class='row-fluid'>
			<div id="users_links" class="col-xs-12 col-sm-2 pull-right tabs-links" >
				<ul class="nav nav-pills nav-stacked">
					<?php 
						echo $catalogue->get_catalgue_lists( 'profiles', $request->id_profile, $Index->link . '&id_pf=', 'Todos' );
					?>
				</ul>
			</div>
			<div id="users_tabs" class="col-xs-12 col-sm-10 tabs-content">
				<div id="dashboard-overview" class="row" style="visibility: visible; position: relative;"> 
					<div class="col-xs-9">
						<h3 id='lbl_table_users'> 
							<?php
								if ( $request->id_profile > 0 ){
									$profiles = $catalogue->get_catalogue('profiles', TRUE);
									foreach ($profiles as $k => $pf) {
										if ( $pf['id'] == $request->id_profile ){
											$listado->set_title($pf['opt']);
											echo $pf['opt'];
										}
									}
								} else {
									echo "Todos los usuarios.";
								}
							?>  
						</h3> 
					</div>
					<div id='fnc_table_users' class='col-xs-3 pull-right ' style='padding-top: 15px;'> 
						<button class="btn btn-default pull-right" type="button" title="Crear Usuario" onclick='edit_user(0);' data-target="#mdl_frm_user" data-toggle="modal">
							<i class="fa fa-plus"></i>
							<span class='hidden-xs hidden-sm' >Crear Usuario</span>
						</button>
					</div> 
					<div class="col-xs-12 col-sm-12" style="min-height: 400px; overflow-x:auto;"> 
						<table class="table table-striped table-bordered table-hover datatable" id='tbl_usuarios'>
							 <?php $listado->get_list_html();  ?>
						</table> 
					</div>
				</div>
			</div> 
			<div class="clearfix"></div>
		</div>
	</div>
</div>



		</div>
	</div>
</div>
<!-- Modal --> 
<div id="mdl_frm_user" class="modal fade"  role="dialog" aria-labelledby="mdl_frm_user" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="clean_form();"> &times; </button>
				<h4 id="mdl_frm_user_title" class="modal-title">Edición de Usuario</h4>
			</div>
			<form id="frm_user" class="form-horizontal" role="form" method="post" action="users.php" >
				<div class="modal-body">   
					<fieldset> 
						<legend> Usuario </legend> 
							<div class="form-group">
								<div class="hidden-xs col-sm-1"> &nbsp; </div> 
								<div class="col-xs-12 col-sm-4">
									<label class="control-label">Usuario</label>
									<input type="email" id="inp_user" name="user" class="form-control" value="" required  data-validation="required email unique-user" />
								</div> 
								<div class="hidden-xs col-sm-2"> &nbsp; </div>
								<div class="col-xs-12 col-sm-4">
									<label class="control-label">Perfil</label>
									<select id="inp_profile" name="profile" class="form-control" required data-validation="required select-option" > 
										<?php echo $catalogue->get_catalgue_options('profiles'); ?>
									</select>
								</div>
								<div class="hidden-xs col-sm-1"> &nbsp; </div>
							</div> 
							<div class="form-group" id='opc_password'>
								<div class="hidden-xs col-sm-1"> &nbsp; </div>
								<div class="col-xs-12 col-sm-10">
									<label class="control-label"> Contraseña </label>
									<div class="radio"> 
										<label> 
											<input type="radio" name="pwd_option" id="inp_pwd_option_manual" value="pwd_manual" onchange="change_password_option(this.value);" /> 
											Asignar contraseña manualmente. 
										</label> 
									</div> 
									<div id='div_password' class="form-group " style="display:none;" >
										<label class="col-sm-2 control-label">Contraseña:</label>
										<div class="col-sm-4">
											<input class="form-control" type="password" id="inp_password" name="inp_password" data-validation-optional="true"  />
										</div>
										<label class="col-sm-2 control-label"> Confirmación contraseña:</label>
										<div class="col-sm-4">
											<input class="form-control" type="password" id="inp_password_match" data-validation="password-match" data-validation-optional="true" data-validation-target="inp_password" />
										</div>
									</div> 
									<div class="radio"> 
										<label> 
											<input type="radio" name="pwd_option" id="inp_pwd_option_email" value="pwd_email" onchange="change_password_option(this.value);" /> 
											Generar contraseña y enviar por E-mail. 
										</label>
									</div>
								</div>
							</div> 
							
					</fieldset>
					
					<fieldset>
						<legend> 
							 <div class="checkbox"> 
							 	<label> 
							 		<input id="inp_instance_option" type="checkbox" onchange="set_instance_option();" />
							 		Instancia 
							 	</label>
							 </div>
						</legend>
						<div id='div_instance' class='row' style="display:none;" >
							<div class="form-group" > 
								<label class="col-sm-2 control-label">Cliente: </label>
								<div class="col-sm-4">
									<select id="inp_client" name="client" class="form-control">
										<?php echo $catalogue->get_catalgue_options('clients'); ?> 
									</select>
								</div>
								<label class="col-sm-2 control-label">Instancia: </label>
								<div class="col-sm-4">
									<select id="inp_instance" name="instance" class="form-control">
										<?php echo $catalogue->get_catalgue_options('instances'); ?>
									</select>
								</div>
							</div>
						</div> 
					</fieldset>
				</div>
				<div class="modal-footer">
					<input type='hidden' id='inp_id_user' name='id_user' value='' />
					<input type='hidden' id='inp_action'  name='action' value='edit_user' />
					<button type="button" class="btn btn-default" data-dismiss="modal" onclick="cancel_user_edition();">
						Cancelar
					</button>
					<button type="submit" class="btn btn-default" >
						Aceptar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>  

<!-- User Detail Modal  -->
<div id="mdl_detail_user" class="modal fade"  role="dialog" aria-labelledby="mdl_detail_user" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="clean_form();"> &times; </button>
				<h4 id="mdl_frm_user_title" class="modal-title">Detalle de Usuario</h4>
			</div> 
			<div class="modal-body" id='detail_user_content'>
				
			</div>  
			<div class="modal-footer">
				<input type='hidden' id='inp_detail_id_user' name='detail_id_user' value='0' />
				<input type='hidden' id='inp_action'  		 name='action' value='edit_user' /> 
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<i class='fa fa-times'></i> &nbsp;Cerrar
				</button>
			</div> 
		</div>
	</div>
</div>