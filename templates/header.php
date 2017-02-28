<?php require_once '../api/config.php'; ?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse"
			data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span>
			<span class="icon-bar"></span> <span class="icon-bar"></span>
		</button>
		<!-- You'll want to use a responsive image option so this logo looks good on devices - I recommend using something like retina.js (do a quick Google search for it and you'll find it) -->
		<a class="navbar-brand nav-link" href="#/">Nguyen Res</a>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav navbar-right ">
			<li ng-if="LoggedEmployee.AccessLevel >= 1"><a class="nav-link" href="#/home">{{lang['Home']}}&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
			<li ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2"><a class="nav-link" href="#/report">{{lang['Reporting']}}&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
 			<li ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2"><a class="nav-link" href="#/products">{{lang['Products']}}&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
			<li ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2"><a class="nav-link" href="#/categories">{{lang['Categories']}}&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
			<li ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2"><a class="nav-link" href="#/employees">{{lang['Employees']}}&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
			<li ng-if="LoggedEmployee.AccessLevel == 1"><a class="nav-link" href="#/settings">{{lang['Settings']}}&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
		</ul>
	</div>
</nav>

<div class="row user">
	{{lang['Welcome']}} {{LoggedEmployee.Username}} | <a class="link" href="#/logout">{{lang['Logout']}}</a>
</div>
