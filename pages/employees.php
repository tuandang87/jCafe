
<div  ng-controller="employeeController" >
	<!-- HEADER -->
	<div class="row" ng-include="'templates/header.php'"></div>
	<div ng-include="'templates/alert.php'"></div>
	<!-- LIST OF PRODUCTS -->
	<div class="container center-content-div" ng-if="Display == Displays['List']">
		<div class="row" ng-include="'pages/employees.list.php'"></div>
	</div>

	<!-- FORM EDIT PRODUCT -->
	<div ng-if="Display == Displays['Edit']">
		<div class="row" ng-include="'pages/employees.form.php'"></div>
	</div>
</div>