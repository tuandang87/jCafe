<nav class="navbar navbar-inverse navbar-fixed-top"
	style="border-color:LightGrey;">
	<div class="row">
		<div class="col-xs-3">
			<p ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2 || LoggedEmployee.AccessLevel == 3"
				class="nav-btn-left" ng-click="addOrderItem()">{{lang['Add']}}</p>
		</div>
		<div class="col-xs-6">
			<p class="nav-btn-center">{{lang['Review']}} {{lang['Desk']}} {{Receipt.DeskNo}}</p>
		</div>
		<div class="col-xs-3">
			<p class="nav-btn-right" ng-click="saveReceiptInfo()">OK</p>
		</div>
	</div>
</nav>
<div class="row" style="margin-top: 50px;"></div>

<div class="row " style="margin-top: 10px; margin-bottom: 10px; color: chocolate;">
	<div class="col-xs-6">{{lang['Receipt']}}: </div>
	<div class="col-xs-6"> <b>{{Receipt.Id}} </b></div>
</div>
<div class="row " style="margin-top: 10px; margin-bottom: 10px; color: chocolate;">
	<div class="col-xs-6">{{lang['ServingEmployee']}}: </div>
	<div class="col-xs-6"> <b>{{Receipt.Username}} </b></div>
</div>
<div class="row " style="margin-top: 10px; margin-bottom: 10px; color: chocolate;">
	<div class="col-xs-6">{{lang['CheckInTime']}}: </div>
	<div class="col-xs-6"> <b>{{Receipt.CheckInTime}} </b></div>
</div>

<div class="row " style="margin-top: 10px; margin-bottom: 10px; color: chocolate;">
	<div class="col-xs-6">{{lang['UpdatedTime']}}: </div>
	<div class="col-xs-6"> <b>{{Receipt.UpdatedTime}} </b></div>
</div>

<div class="row home-product-header">
	<div class="col-xs-4 home-product-col" >{{lang['Product']}}</div>
	<div class="col-xs-2 home-product-col text-center" >{{lang['Qty']}}</div>
	<div class="col-xs-2 home-product-col" >{{lang['Amount']}}</div>
	<div class="col-xs-1 home-product-col" >{{lang['IsServed']}}</div>
	<div class="col-xs-2 home-product-col text-center" >{{lang['Time']}}</div>
	<div ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2 || LoggedEmployee.AccessLevel == 3"
		class="col-xs-1 home-product-col" >{{lang['Del']}}</div>
</div>
<div class="row home-product-row" ng-repeat="OrderItem in OrderItems" ng-class="OrderItem.IsUpdated == '1' ? 'order-item-updated-row' : 'order-item-row'">
	<div class="col-xs-4 home-product-col" >{{OrderItem.ProductName}}</div>
	<div class="col-xs-2 home-product-col text-center">{{OrderItem.Quantity}}</div>
	<div class="col-xs-2 home-product-col ">{{OrderItem.Amount | number:0}}</div>
	<div class="col-xs-1"><input type="checkbox" ng-checked="OrderItem.IsServed == 1" ng-click="clickOnServing(OrderItem)" /></div>
	<div class="col-xs-2 home-product-col text-center">{{OrderItem.ServedTime}}</div>
	<div ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2 || LoggedEmployee.AccessLevel == 3"
		class="col-xs-1"><button class="btn btn-danger" style="margin-bottom: 5px;" ng-click="deleteOrderItem(OrderItem.Id, OrderItem.ReceiptId)">&times</button></div>
</div>

<div class="row" style="text-align: center; margin-top:20px;">
   		<textarea placeholder="{{lang['Note']}}" class="form-control text-center" ng-model="Receipt.Note"/>
</div>

<div ng-if="LoggedEmployee.AccessLevel == 5" class="row" style="text-align: center; margin-top:20px;">
   		<input type="button"  value="{{lang['Serve']}}" class="btn btn-success" ng-click="serveOrderItems()">
</div>

<div class="box" style="text-align: center; margin-top:10px; margin-bottom: 60px;">

	<div class="row" ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2 || LoggedEmployee.AccessLevel == 3 || LoggedEmployee.AccessLevel == 4" >
		<div class="row">
			<div class="col-xs-4"><b>{{lang['ExtraPaidPerItem']}}</b></div>
			<div class="col-xs-4"><b>{{lang['NumOfItems']}}</b></div>
			<div class="col-xs-4"><b>{{lang['ExtraPaid']}}</b></div>
		</div>
		<div class="row" style="text-align: center; margin-top:10px;">
			<input type="text" class="col-xs-4 text-center" ng-change="onExtraPaidPerItemChanged()" ng-model="Receipt.ExtraPaidPerItem">
			<div class="col-xs-4"> {{Receipt.NumOfItems}} </div>
			<div class="col-xs-4"> {{Receipt.ExtraPaid | number : 0 }} </div>
		</div>
	<div>

	<div ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2 || LoggedEmployee.AccessLevel == 3"
		class="row" style="text-align: center; margin-top:10px;">
   		<input  type="number" placeholder="{{lang['MergeOrMoveText']}}" class="col-xs-7  text-center" ng-model="toDeskNo">
   		<div class="col-xs-1"></div>
   		<input  type="button"  value="{{lang['MergeOrMove']}}" class="col-xs-4 btn btn-primary" ng-click="ConnectDesks1(Receipt.Id, Receipt.DeskNo, toDeskNo)">
  </div>
</div>

<nav class="navbar navbar-inverse navbar-fixed-bottom"
	style="background-color: SaddleBrown; border-color:LightGrey;">
	<div class="row">
		<div class="col-xs-6">
			<p class="nav-btn-left">{{lang['SubTotal']}}: {{Receipt.Total/1000}} K</p>
		</div>
		<div class="col-xs-6">
			<p ng-if="LoggedEmployee.AccessLevel >=1 && LoggedEmployee.AccessLevel <= 4"
				class="nav-btn-right" data-dismiss="modal" ng-click="pay()">{{lang['Pay']}}</p>
		</div>
	</div>
</nav>
