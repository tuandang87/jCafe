<?php require_once '../api/config.php';?>

<div class="row" ng-controller="homeController" >
	<!-- DESKS -->
	<div class="container center-content-div" ng-if="Display == Displays['Desks']">
		<div class="row" ng-include="'templates/header.php'"></div>
		<div class="row " ng-include="'pages/home.desks.php'"></div>
	</div>

	<!-- PRODUCTS -->
	<div class="container" ng-if="Display == Displays['Products']">
		<div class="row" ng-include="'pages/home.products.php'"></div>
	</div>

	<!-- ORDER ITEMS -->
	<div class="container" ng-if="Display == Displays['OrderItems']">
		<div class="row" ng-include="'pages/home.orderItems.php'"></div>
	</div>

</div>
