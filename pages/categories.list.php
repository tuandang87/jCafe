<div class="row" style="margin-left: 5px; margin-top: 5px;">
	<div class="col-xs-4">
		<input type="button" value="{{lang['NewCategory']}}" ng-click="newCategory()" class="btn btn-info" />
	</div>
	<div class="col-xs-8">
		<input type="text" ng-model="Search.Query"
			ng-change="onSearchCategories()"
			placeholder="{{lang['Search']}}"
			class="form-control" />
	</div>
</div>

<div class="row">
	<div class="row table-header">
		<div class="col-xs-2 text-center">{{lang['Id']}}</div>
		<div class="col-xs-3 ">{{lang['Name']}}</div>
		<div class="col-xs-3 ">{{lang['Image']}}</div>
		<div class="col-xs-2 ">{{lang['Edit']}}</div>
		<div class="col-xs-2 ">{{lang['Del']}}</div>
	</div>
	<div class="row table-row " ng-repeat="c in Categories" ng-click="getProductsOfCategory(c.Id)">
		<div class="col-xs-2 text-center">{{c.Id}}</div>
		<div class="col-xs-3 ">{{c.Name}}</div>
		<div class="col-xs-3 "><img class="small" ng-src="{{ 'img/' + c.Image}}"></div>
		<div class="col-xs-2 ">
			<button class="btn btn-warning slim" ng-click="editCategory(c.IndexInArr)">{{lang['Edit']}}</button>
		</div>
		<div class="col-xs-2  ">
			<button class="btn btn-danger slim" ng-click="deleteCategory(c.Id)">{{lang['Del']}}</button>
		</div>
	</div>
</div>
