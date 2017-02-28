<div  ng-controller="categoryController" >
	<!-- HEADER -->
	<div class="row" ng-include="'templates/header.php'"></div>
	<div ng-include="'templates/alert.php'"></div>
	<!-- LIST OF CATEGORIES -->
	<div class="container center-content-div" ng-if="Display == Displays['ListCategories']">
		<div class="row" ng-include="'pages/categories.list.php'"></div>
	</div>

	<!-- FORM EDIT PRODUCT -->
	<div ng-if="Display == Displays['EditCategory']">
		<div class="row" ng-include="'pages/categories.form.php'"></div>
	</div>
	<div ng-if="Display == Displays['ListProductsOfCategory']">
		<div class="row" ng-include="'pages/categories.products.list.php'"></div>
	</div>
</div>