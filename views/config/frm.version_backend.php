<?php
global $Settings;
$version = $Settings->get_settings_option("global_backend_version",0,TRUE);
?>
<script>
$(document).ready(function() {
	$.validate({
		form : '#frm_version',
		language : validate_language
	});
});
</script>
<div id="section-header" class="row">
	<div class="col-xs-12 ">
		<h2> Backend Version </h2>
	</div> 
</div> 
<div id='form-content' class=' content-info row '> 
	<form id="frm_version" class="form-horizontal" role="form" method="post" action="configuration.php" >
	<div class="col-xs-12 col-sm-12">
		<div class="row">
			<div class="col-xs-12 form-group"> 
				<label class="control-label">Backend Version <span class="text-danger">*</span>: </label>
				<input type="text" id="inp_global_backend_version" name="global_backend_version" class="form-control input-lg text-center" 
					data-validation="required " value="<?php echo $version->value; ?>" />
				<small>(Última actualización: <?php echo date('Y-m-d H:i:s', $version->timestamp) ?>)</small>
			</div> 
		</div>
		<div class="row border-top" style="margin: 10px 0;"></div>
		<div class="row ">
			<input type="hidden" name="action" value="edit_backend_version" />
			<button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> Cancelar </button>
			<button type="submit" class="btn btn-default pull-right"><i class="fa fa-save"></i> Guardar </button>
		</div>
	</div>
	</form>
</div>