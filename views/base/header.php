<?php

?>
<header class="navbar">
	<div class="container-fluid expanded-panel">
		<div class="row"> 
			<div id="logo" class="col-xs-6 col-sm-6">
				<a href="#" class="show-sidebar">
					<img src='img/logo.png' height='40' /> 
				</a>
			</div>
			<div id="top-panel" class="col-xs-6 col-sm-6" style="padding-right: 0;">
				<ul class="nav navbar-nav pull-right panel-menu">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle account" data-toggle="dropdown">
							<div class="avatar">
								<img src="img/ico_el.png" class="img-rounded" alt="avatar">
							</div> <i class="fa fa-angle-down pull-right"> </i>
							<div class="user-mini pull-right">
								<span><?php echo $Session->get_name(); ?></span><br/>
								<span><?php echo $Session->get_user(); ?></span>
							</div> 
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="#"> <i class="fa fa-user"></i> <span class="hidden-sm text">Profile</span> </a>
							</li> 
							<li>
								<a href="ajax/calendar.html" class="ajax-link"> <i class="fa fa-tasks"></i> <span class="hidden-sm text">Tasks</span> </a>
							</li>
							<li>
								<a href="#"> <i class="fa fa-cog"></i> <span class="hidden-sm text">Settings</span> </a>
							</li>
							<li>
								<a href="index.php?command=<?php echo LOGOUT;?>"> <i class="fa fa-power-off"></i> <span class="hidden-sm text">Logout</span> </a>
							</li>
						</ul>
					</li>
				</ul> 
			</div>
		</div>
	</div>
</header>