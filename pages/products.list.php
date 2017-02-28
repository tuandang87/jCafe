
<div class="row" style="margin-left: 5px; margin-top: 5px;">
	<div class="col-xs-4">
		<input type="button" value="{{::lang['NewProduct']}}" ng-click="newProduct()" class="btn btn-info" />
	</div>
	<div class="col-xs-8">
		<input type="text" ng-model="Search.Name"
			placeholder="{{lang['Search']}}"
			class="form-control"/>
	</div>
</div>

<div class="row">
	<div class="row table-header">
		<div class="col-xs-2 text-center">{{::lang['Id']}}</div>
		<div class="col-xs-4 ">{{::lang['Name']}}</div>
		<div class="col-xs-2 ">{{::lang['Price']}}</div>
			<div class="col-xs-1 ">{{::lang['IsFavorite']}}</div>
		<div class="col-xs-2 ">{{::lang['Image']}}</div>
	 <!--	<div class="col-xs-2 ">{{::lang['Category']}}</div> -->
		<div class="col-xs-1 ">{{::lang['Del']}}</div>
	</div>
	<div class="row table-row" ng-repeat="p in Products | filter : filterUnsigned">
		<div class="col-xs-2 text-center">{{::p.Id}}</div>
		<div class="col-xs-4 " ng-click="editProduct(p.IndexInArr)">{{::p.Name}}</div>
		<div class="col-xs-2 ">{{::p.Price | number:0}}</div>
		<div class="col-xs-1">
			<input type="checkbox" class="checkbox" ng-click="clickOnIsFavorite(p)"
				ng-checked="p.IsFavorite == 1">
		</div>
		<div class="col-xs-2 " ng-click="editProduct(p.IndexInArr)"><img class="small" ng-src="{{:: 'img/' + p.Image}}"></div>
		<!--	<div class="col-xs-2">{{::p.CategoryName}}</div> -->

		<div class="col-xs-1  ">
			<button class="btn btn-danger slim" ng-click="deleteProduct(p.Id)">{{::lang['Del']}}</button>
		</div>
	</div>
</div>
