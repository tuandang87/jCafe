<?php require_once '../api/config.php';?>

<div ng-controller="loginController">

	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="row">
			<div class="col-xs-12">
				<p class="text-header"><?php echo $settings["Name"]; ?></p>
			</div>
		</div>
	</nav>

	<form ng-submit="login(data)" class="containter"
		style="margin-left: 25%; margin-right: 25%; margin-top: 20%;">
		<div class="form-group">
			<input type="text" class="form-control text-center"
				ng-model="data.Username" name="Username"
				placeholder="{{lang['Username']}}"/>
		</div>
		<div class="form-group">
			<input type="password" class="form-control text-center"
				ng-model="data.Password" name="Password"
				placeholder="{{lang['Password']}}"/>
		</div>
		<div class="form-group"">
			<input type="submit" id="login"
				value="{{lang['Login']}}"
				class="btn btn-primary" 
				style="display: block; margin: 0 auto; width: 100px;" />
		</div>
	</form>

	<nav class="navbar navbar-inverse navbar-fixed-bottom">
		<div class="row">
			<div class="col-xs-12">
				<p class="text-center" style="margin-top: 10px;"><?php echo $settings["Address"]; ?></p>
			</div>
		</div>
	</nav>
	<div ng-include="'templates/alert.php'"></div>
</div>
