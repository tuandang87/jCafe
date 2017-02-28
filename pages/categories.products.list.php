
<div class="row" style="margin: 10px;">
	<button class="btn btn-info" ng-click="showDisplay(Displays['ListCategories'])">{{::lang['Back']}}</button>
</div>

<div class="row">
	<div class="row table-header">
		<div class="col-xs-2 text-center">{{lang['Id']}}</div>
		<div class="col-xs-4 " style="text-align:left;">{{lang['Name']}}</div>
		<div class="col-xs-2 ">{{lang['Price']}}</div>
		<div class="col-xs-2 ">{{lang['Image']}}</div>
		<div class="col-xs-2 " style="text-align:left;">{{lang['IsFavorite']}}</div>
	</div>
	<div class="row table-row" ng-repeat="p in ProductsOfCategory">
		<div class="col-xs-2 text-center " style="text-align:left;">{{p.Id}}</div>
		<div class="col-xs-4 ">{{p.Name}}</div>
		<div class="col-xs-2 ">{{p.Price | number:0}}</div>
		<div class="col-xs-2 "><img class="small" ng-src="{{ 'img/' + p.Image}}"></div>
		<div class="col-xs-2">
			<input type="checkbox" class="checkbox" ng-click="clickOnIsFavorite(p)"
				ng-checked="p.IsFavorite == 1">
		</div>
	</div>
</div>
