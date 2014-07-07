<?php 
 //global $user; 
?>
<section class="wrapper" id='user-info'>
	<div class="row">
		<!-- contact-widget -->
		<div class="col-lg-12">
			<div class="contact-widget contact-widget-info">
				<div class="panel-body">
					<div class="col-lg-4 col-sm-4">
						<h4> <?php echo $user->user ?></h4> 
						<div class="row text-center">
							<span class="contact-avatar">
								<img alt="" src="<?php echo $user->contact->get_pic(); ?>">
							</span>
						</div>
						<div class="row">
							<h6> <?php echo $user->profile->profile ?> </h6>
						</div>
					</div>
					<div class="col-lg-4 col-sm-4 follow-info">
						<p> 
							<?php echo $user->contact->name . ($user->contact->lastname != '' ? " " . $user->contact->lastname : "")  ; ?>&nbsp;
						</p> 
						<p> &nbsp; </p>
						<h6>
							<span><i class="fa fa-clock-o"></i><?php echo date('H:i:s',$user->lastlogin) ?></span>
							<span><i class="fa fa-calendar"></i><?php echo date('Y-m-d',$user->lastlogin) ?></span>
							<span><i class="fa fa-map-marker"></i><?php echo $user->contact->sex ?></span>
						</h6>
					</div>
					<div class="col-lg-4 col-sm-4 follow-info "> 
						<p><i class="fa fa-trophy"></i> Cliente<h4><?php echo "" //$user->user->client ?></h4></p>
						<p><i class="fa fa-briefcase"></i> Instancia<h4><?php echo  "" //$user->user->instance ?></h4></p> 
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- page start-->
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<div class="panel-heading tab-bg-info " style="padding-bottom: 0;">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#contact" data-toggle="tab"> <i class="fa fa-user "></i> <span class='hidden-xs'>&nbsp;Ficha de contacto</span> </a>
						</li>
						<li>
							<a href="#recent-activity" data-toggle="tab"><i class="fa fa-clock-o"></i> <span class='hidden-xs'>&nbsp;Actividad reciente</span> </a>
						</li> 
					</ul>
				</div>
				<div class="panel-body">
					<div class="tab-content">
						<!-- contact -->
						<div class="tab-pane active" id="contact">
							<section class="panel"> 
								<div class="panel-body bio-graph-info">
									<h1 class="col-xs-10"> Contacto </h1>
									<div class="col-xs-2">
										<button class="btn btn-default pull-right" style='margin-top: -10px;' onclick="edit_contact(<?php echo $user->id_user ?>)" >
											<i class="fa fa-edit"></i> Editar 
										</button> 
									</div>
									<div class="row ">
										<div class="col-xs-12 col-sm-6">
											<p>
												<label class="col-xs-4">Nombre</label> <?php echo $user->contact->name ?>
											</p>
										</div>
										<div class="col-xs-12 col-sm-6">
											<p>
												<label class="col-xs-4">Apellido</label> <?php echo $user->contact->lastname ?>
											</p>
										</div>
										<div class="col-xs-12 col-sm-6">
											<p>
												<label class="col-xs-4">Sexo</label> <?php echo $user->contact->sex ?>
											</p>
										</div>
										<div class="col-xs-12 col-sm-6">
											<p>
												<label class="col-xs-4">E-mail</label> <?php echo $user->contact->email ?>
											</p>
										</div>
										<div class="col-xs-12 col-sm-6">
											<p>
												<label class="col-xs-4">Teléfono </label> <?php echo $user->contact->telephone ?>
											</p>
										</div>
										<div class="col-xs-12 col-sm-6">
											<p>
												<label class="col-xs-4">Celular </label> <?php echo $user->contact->cellphone ?>
											</p>
										</div> 
									</div>
									<div class='row'>&nbsp;</div>
									<h1 class='col-xs-12'> Meta Datos </h1>
									<div class='row '>
										<?php echo $user->contact->meta->get_contact_list(); ?>
									</div> 
								</div>
							</section>
							<section>
								<div class="row"></div>
							</section>
						</div>
						<!-- recent-activity -->
						<div class="tab-pane" id="recent-activity">
							<div class="contact-activity">
								<div class="act-time">
									<div class="activity-body act-in">
										<span class="arrow"></span>
										<div class="text"> 
											<p class="attribution">
												<a href="#">Edgar Parks</a> at 4:25pm, 30th Octmber 2013
											</p>
											<p> An Awesome Piece of Cake For You Guys! </p>
										</div>
									</div>
								</div>
								<div class="act-time">
									<div class="activity-body act-in">
										<span class="arrow"></span>
										<div class="text"> 
											<p class="attribution">
												<a href="#">Judi Mcginnis</a> at 5:25am, 30th Octmber 2013
											</p>
											<p> Knowledge speaks, but wisdom listens. </p>
										</div>
									</div>
								</div>  
							</div>
						</div> 
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- page end-->
</section>