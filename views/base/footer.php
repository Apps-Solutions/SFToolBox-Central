<?php
global $Settings;
?>
<footer>
	<div class="row">
		<div class="hidden-xs col-sm-2 align-center">
			<div class="preloader" style="display: none;">
				<img alt="preloader" src="img/loader.gif">
			</div>
		</div> 
		<div class="col-xs-12 col-sm-10">
			<div class='row'>
				<div class='col-xs-6'>
					Sellcom &copy; <?php echo date('Y') ?>
				</div>
				<div class='col-xs-6 text-right'>
					<?php
						$central = $Settings->get_settings_option('global_backend_version', 0, TRUE);
						$app	 = $Settings->get_settings_option('global_app_version', 0, TRUE);
					?> 
					<label class="hidden-xs">Versión</label> 
					<i class="fa fa-cloud"></i> <?php echo $central->value ?> <small class="hidden-xs"> (<?php echo date('Y-m-d',$central->timestamp) ?>) </small> &nbsp;
					<i class="fa fa-android"></i> <?php echo $app->value ?> <small class="hidden-xs"> (<?php echo date('Y-m-d',$app->timestamp) ?>) </small>
				</div>
			</div> 
		</div>
	</div>  
</footer>