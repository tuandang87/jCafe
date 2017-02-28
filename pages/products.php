
<div  ng-controller="productController" >
	<!-- HEADER -->
	<div class="row" ng-include="'templates/header.php'"></div>
	<div ng-include="'templates/alert.php'"></div>
	<!-- LIST OF PRODUCTS -->
	<div class="container center-content-div" ng-if="Display == Displays['List']">
		<div class="row" ng-include="'pages/products.list.php'"></div>
	</div>

	<!-- FORM EDIT PRODUCT -->
	<div ng-if="Display == Displays['Edit']">
		<div class="row" ng-include="'pages/products.form.php'"></div>
	</div>
</div>