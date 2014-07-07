<?php
global $Settings;

?>
<script>
$(document).ready(function() {
	$.validate({
		form : '#frm_appearance',
		language : validate_language
		//, modules : 'file'
	});
});
</script>
<div id="section-header" class="row">
	<div class="col-xs-12 ">
		<h2> Apariencia </h2>
	</div> 
</div> 
<div id='form-content' class=' content-info row '> 
	<form id="frm_appearance" class="form-horizontal" role="form" method="post" action="configuration.php" enctype="multipart/form-data" >
	<div class="col-xs-12 col-sm-12">
		<div class="row">
			<div class="col-xs-12 form-group"> 
				<label class="control-label">Título <span class="text-danger">*</span>:</label>
				<input type="text" id="inp_config_title" class="form-control input-lg text-center" name="title" 
					data-validation="required " value="<?php echo $Settings->get_settings_option("global_sys_title"); ?>" />
			</div>
			<div class="col-xs-12 col-sm-3 text-center">
				<img src="img/logo.png" style="width:100%; max-width: 200px;" />
			</div> 
			<div class='col-xs-12 col-sm-8'>
				<div class="row form-group">
					<label>Logotipo</label>
					<input type="file" id="inp_config_logo" name="logo" class="" data-validation-optional="true"  data-validation="mime size" 
						data-validation-allowing="jpg, png, gif" data-validation-max-size="512kb"  />
				</div>
				<div class="row"> &nbsp; </div>
				<div class="row text-center">
					<div class="col-xs-12 col-sm-4 form-group">
						<label >Color Principal</label>
						<span class="col-xs-12">
							<input type="color" id="inp_config_color_1" name="color1" value="<?php echo $Settings->get_settings_option("global_css_color1"); ?>" />	
						</span>	
					</div>
					<div class="col-xs-12 col-sm-4 form-group">
						<label>Color Secundario</label>
						<span class="col-xs-12">
							<input type="color" id="inp_config_color_2" name="color2" value="<?php echo $Settings->get_settings_option("global_css_color2"); ?>" />	
						</span>	
					</div>
					<div class="col-xs-12 col-sm-4 form-group">
						<label>Color Auxiliar</label>
						<span class="col-xs-12">
							<input type="color" id="inp_config_color_3" name="color3" value="<?php echo $Settings->get_settings_option("global_css_color3"); ?>" />	
						</span>	
					</div> 
				</div>
			</div>  
		</div>
		<div class="row border-top" style="margin: 10px 0;"></div>
		<div class="row ">
			<input type="hidden" name="action" value="edit_appearance" />
			<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Cancelar </button>
			<button type="submit" class="btn btn-default pull-right"><i class="fa fa-save"></i> Guardar </button>
		</div>
	</div>
	</form>
</div>