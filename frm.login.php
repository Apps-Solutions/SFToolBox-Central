<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"> 
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta content="width=device-width, initial-scale=1" name="viewport">
	    <title> ..:: Meta Tracker ::..</title>
		<link href="img/favicon.ico" rel="shortcut icon">
	<!-- CSS -->
	    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/> 
		<link href="css/font-awesome.css" type="text/css" rel="stylesheet">
		<link href="css/estilo.css" type="text/css" rel="stylesheet">
	<!-- /CSS -->
	<!-- JS -->
		<script src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/func.js"></script>
	<!-- /JS -->
		<style>
			body {
				background: #666;
			}
			h3 {
				border: none;
			}
			#page-login .box {
			    margin-top: 15%;
			}
			.box {
			    background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
			    border: 1px solid #F8F8F8;
			    border-radius: 3px;
			    box-shadow: 0 0 4px #D8D8D8;
			    display: block;
			    margin-bottom: 60px;
			    position: relative;
			    z-index: 1999;
			}
			.box-content {
			    background: none repeat scroll 0 0 #FCFCFC;
			    border-radius: 0 0 3px 3px;
			    padding: 15px;
			    position: relative;
			}
		</style>
	</head> 
	<body> 
		<div class="container-fluid">
			<div id="page-login" class="row">
				<div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
					<form action="login.php" method="post" > 
						<div class="box">
							<div class="box-content">
								<div class="text-center">
									<h3 ><img src='img/logo.png' alt="Tracker" /></h3> 
								</div>
								<div class="text-center">
									<span class='error'> <?php $error ?> </span>
								</div>
								<div class="form-group">
									<label class="control-label" for="inp_user">Usuario</label>
									<input type="text" class="form-control" name="user" id="inp_user"/>
								</div>
								<div class="form-group">
									<label class="control-label" for="inp_password">Contraseña</label>
									<input type="password" class="form-control" name="password" id="inp_password"/>
								</div>
                                <div class="checkbox">
                                    <label for="inp_hold">
                                        <input type="checkbox" name="hold" id="inp_hold"/>
                                        No cerrar sesión
                                    </label>
                                </div>
								<div class="text-center">
									<input type='submit' class="btn btn-success" value='Entrar'> 
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
