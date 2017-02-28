
<div  ng-controller="reportingController" >

	<!-- HEADER -->
	<div class="row" ng-include="'templates/header.php'"></div>
	<div class="row" ng-include="'templates/headerreport.php'"></div>
	<div ng-include="'templates/alert.php'"></div>
	<!-- LIST OF PRODUCTS -->
	<div class="container center-content-div" ng-if="Display == Displays['Main']">
		<div class="row" ng-include="'pages/reporting.employees.php'"></div>
	</div>
	<div class="container center-content-div" ng-if="Display == Displays['ReportsOfEmployee']">
		<div class="row" ng-include="'pages/reporting.reportsofemployee.php'"></div>
	</div>
	<div class="container center-content-div" ng-if="Display == Displays['OrderItems']">
		<div class="row" ng-include="'pages/reporting.viewpropertiesreport.php'"></div>
	</div>


</div>