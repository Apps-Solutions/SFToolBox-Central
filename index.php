<?php
require 'init.php';
 
if (isset($_POST['command']) && $_POST['command'] != ''){
	$command = $_POST['command'];
} else if (isset($_GET['command']) && $_GET['command'] != ''){
	$command = $_GET['command'];
} else $command = HOME;

global $mensaje, $error;
if (isset($_GET['msg'])) $mensaje .= $_GET['msg'];
if (isset($_GET['err'])) $error .= $_GET['err']; 
$msg_class = "oculto";
$err_class = "oculto";

if (isset($_SESSION["cookie_http_vars"]) && !empty($_SESSION["cookie_http_vars"])){

	foreach ($_SESSION["cookie_http_vars"] as $key => $value) {
	    foreach ($_SESSION["cookie_http_vars"] as $key => $value) {
	        $FORM[$key] = (!is_array($value) ? utf8_encode($value) : $value);
	    }
	} 
    
    if (isset($FORM['MsgErr']))
        $error .= nl2br($FORM['MsgErr']);
    if (isset($FORM['MsgSas']))
        $mensaje .= nl2br($FORM['MsgSas']);

	$_SESSION["cookie_http_vars"] = array(); 
} 

if ($mensaje != '') {
	$msg_class = "";
	$timer = "mensaje";
} 
if ($error != ''){
	$err_class = "";
	$timer = "error";
}
$Index->logic($command); 
  
if (!$Session->logged_in()){
	require_once 'frm.login.php';
	die(); 
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" )> 
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<meta content="width=device-width, initial-scale=1" name="viewport" />
	    <title><?php echo $Index->get_title(); ?></title>
		<link href="img/favicon.ico" rel="shortcut icon" />
	<!-- CSS -->
	    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" /> 
		<link href="css/font-awesome.css" type="text/css" rel="stylesheet" />
		<link href="css/estilo.css" type="text/css" rel="stylesheet" />
		
		<?php echo $Index->get_css(); ?>
		
		<style>
			/*** COLORES ***/
			<?php 
			$color1 = $Settings->get_settings_option('global_css_color1');
			$color2 = $Settings->get_settings_option('global_css_color2');
			$color3 = $Settings->get_settings_option('global_css_color3');
			?>
			
			#content { background-color: <?php echo $color1; ?>; /* #439743 */ }
			header 	 { background-color: <?php echo $color2; ?>;}
			#sidebar-left { background-color: <?php echo $color3; ?>;}
			
			.pagination > li > a, .pagination > li > span { color: <?php echo $color1; ?>; }
			.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
			    background-color: <?php echo $color1; ?>;
			    border-color: 	  <?php echo $color1; ?>;
			    color: #FFFFFF;
			}
			.pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus {
			    background-color: #EEEEEE;
			    border-color: #DDDDDD;
			    color: <?php echo $color1; ?>;
			}
			
			.modal-header { background-color: <?php echo $color1; ?>; color: #FEFEFE;  } 
			.avatar > img { background: none repeat scroll 0 0 <?php echo $color1; ?>; }
			
			.table-hover > tbody > tr:hover > td, .table-hover > tbody > tr:hover > th { background-color: <?php echo $color1; ?>; } 
			.form-control:focus { border-color: <?php echo $color1; ?>; }
			
			.btn-default:hover {  background-color: <?php echo $color1; ?>; color: <?php echo $color2; ?>; }
			
			.content-info, .tabs-content {background-color: <?php echo $color2; ?>;}
			
			.tabs-links .nav { background-color: <?php echo $color3; ?>; }
			
			.contact-widget-info {
			    background-color: <?php echo $color1; ?>; color: #FEFEFE;  
			} 
			
			.tab-bg-info {
			    background: <?php echo $color3; ?>;
			    border-bottom: medium none;
			}
			
			
			#user-info label{
				color: <?php echo $color1; ?>;
				font-weight: 700;
			}
			
			#user-info .nav-tabs > li > a:hover { color: <?php echo $color1; ?>; }
		</style>
	<!-- /CSS -->
	<!-- JS -->
		<script src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js" type="text/javascript"></script>
		<script src="js/jquery-ui.min.js" type="text/javascript"></script>
		<script src="js/jquery.form-validator.min.js" type="text/javascript"></script>
		<script src="js/menu.js" type="text/javascript"></script>
		<script src="js/func.js" type="text/javascript"></script>
		
		<?php echo $Index->get_js(); ?>
	<!-- /JS -->
	</head> 
	<body> 
    <!-- ERRORES Y MENSAJES --> 
	<div id='cont_msg' style="z-index: 99999999; position:fixed; width:95%;"> 
		<div  id='msg_div'  class="div_msg bg-info text-info <? echo  $msg_class; ?> " >
			<div class="row" >
				<div class="col-xs-2 col-sm-1 text-center"> <i class="fa fa-info"></i> </div>
				<div class="col-xs-8 col-sm-10 "> <p id='msg_span' ><? echo  $mensaje; ?></p></div>
				<div class="col-xs-2 col-sm-1 text-center"> 
					<button class="close" type="button" onclick="javascript:$('#msg_div').hide();"> × </button>  
				</div>  
			</div> 
		</div>
		<div  id='err_div' class="div_msg bg-danger text-danger <? echo  $err_class; ?> " >
			<div class="row " style="padding: 1em .7em;">
				<div class="col-xs-2 col-sm-1 text-center"> <i class="fa fa-warning"></i> </div>
				<div class="col-xs-8 col-sm-10 "> <p id='err_span' ><? echo  $error; ?></p></div>
				<div class="col-xs-2 col-sm-1 text-center"> 
					<button class="close" type="button" onclick="javascript:$('#err_div').hide();"> × </button>  
				</div> 
			</div> 
		</div>  
	</div> 
	 
    <?php 
        require_once DIRECTORY_VIEWS . DIRECTORY_BASE . 'header.php'; 
		$sidebar = $Settings->get_settings_option('show_sidebar', 0, FALSE, TRUE ); 
	?>
	<div class="container-fluid <?php echo ( $sidebar == 'true' ) ? "sidebar-show" : "" ?>" id="main" style="min-height: 675px;">
		<div class="row">
			<div class="col-xs-2 col-sm-2" id="sidebar-left">
		<?php
			echo $Index->get_menu(); 
		?> 	
			</div>
			<div class="col-xs-12 col-sm-10" id="content">  
				<?php 
			    	include_once $Index->get_content(); 
			    ?>  
			</div>
    	</div>
    </div>
    <?php 
        require_once DIRECTORY_VIEWS . DIRECTORY_BASE . 'footer.php'; 
	?>
</body>
</html>