<nav class="navbar navbar-inverse" style="border-color:LightGrey;">
	<div class="row">
		<div class="col-xs-3">
			<p class="nav-btn-left" ng-click="backView(Displays['Receipts'])">{{lang['Cancel']}}</p>
		</div>
		<div class="col-xs-6">
			<p class="nav-btn-center">{{lang['Total']}}: {{Receipt.SubTotal/1000}} K</p>
		</div>
		<div class="col-xs-3">
			<p class="nav-btn-right" ng-click="submitChangedReceipt()">OK</p>
		</div>
	</div>
</nav>

<div class="row" style="margin-top:0px; margin-left:10px; margin-right:10px;" >
	<input type="text" ng-model="Filter.KeyWord" placeholder="{{lang['Search']}}" class="form-control" />
</div>
<div class="row home-products ">
	<div class="row home-product-header">
		<div class="col-xs-7 home-product-col">{{lang['Product']}} s</div>
		<div class="col-xs-1 home-product-col">{{lang['Price']}} </div>
		<div class="col-xs-2 home-product-col"></div>
		<div class="col-xs-2 home-product-col"></div>
	</div>
	<div class=" scroll-list" >
		<div class="row home-product-row" ng-repeat="p in ProductsForEdit | filter: filterUnsigned ">
			<div class="col-xs-7 home-product-col">{{p.Name}} </div>
			<div class="col-xs-1 home-product-col">{{p.Price/1000}}</div>
			<div class="col-xs-2">
				<button class="btn btn-success btn-xlarge" ng-click="clickOnProduct(p.IndexInArr, true)">{{p.Quantity}} x</button>
			</div>
			<div class="col-xs-2">
				<button class="btn btn-danger btn-xlarge-minus" ng-click="clickOnProduct(p.IndexInArr, false)">-</button>
			</div>
		</div>
	</div>
</div> 