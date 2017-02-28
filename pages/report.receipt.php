<div id="header_container">
	<div id="header">
		<div class="header-date">
			<div class="col-md-12">
				<div class="col-md-2">
					<input ng-model="datetime.fromDate" datetime-picker date-format="yyyy-MM-dd HH:mm:ss" ng-click="resetTypeStaticsDate()" />
				</div>
				<div class="col-md-2">
					<input ng-model="datetime.toDate" datetime-picker date-format="yyyy-MM-dd HH:mm:ss" ng-click="resetTypeStaticsDate()" />
				</div>
				<div class="col-md-4">
					<button type="button" class="btn btn-default" ng-class="typeStaticsDate(1) == 0 ? '' : 'btn-info'" ng-click="setDay()">Hôm nay</button>
					<button type="button" class="btn btn-default" ng-class="typeStaticsDate(2) == 0 ? '' : 'btn-info'" ng-click="setWeek()">Tuần này</button>
					<button type="button" class="btn btn-default" ng-class="typeStaticsDate(3) == 0 ? '' : 'btn-info'" ng-click="setMonth()">Tháng này</button>
				</div>
				<div class="col-md-4">
					<select style="height:35px;width:100px;" class="form-control" ng-model="search.typeStatics" ng-options="x.id as x.name for x in typeStatics" ng-change="changeView()"></select>
				</div>
			</div>
		</div>
		<div class="header-date">
			<div class="col-md-12">
				<div class="col-md-2">
					<input ng-model="search.Key" placeholder="Tên/ID" ng-disabled="disableSelectbox"/>
				</div>
				<div class="col-md-2">
				</div>
				<div class="col-md-4">
					<select style="height:35px;width:80px;" class="form-control" ng-model="search.zoneStatics" ng-options="x.id as x.name for x in zoneStatics" ng-disabled="disableSelectbox"></select>
				</div>
				<div class="col-md-4">
					<button type="button" class="btn btn-default" ng-click="searchReceipt()">Tìm kiếm</button>
					<button type="button" class="btn btn-default" ng-click="exportReceipt">Xuất Excel</button>
				</div>
			</div>
		</div>
	</div>
</div>
 <div ng-include="'pages/report.employee.php'" ng-hide="Display != Displays['Employees']"></div>
<div ng-include="'pages/report.receipt.list.php'" ng-hide="Display != Displays['Receipts']"></div>
<div ng-include="'pages/report.receipt.edit.php'" ng-hide="Display != Displays['Edit']"></div>

<div ng-include="'pages/report.desks.php'" ng-hide="Display != Displays['Desk']"></div>
<div ng-include="'pages/report.days.php'" ng-hide="Display != Displays['Day']"></div>
<div ng-include="'pages/report.weeks.php'" ng-hide="Display != Displays['Week']"></div>
<div ng-include="'pages/report.months.php'" ng-hide="Display != Displays['Month']"></div>
<div ng-include="'pages/report.product.php'" ng-hide="Display != Displays['Product']"></div>
<div ng-include="'pages/report.orderItems.php'" ng-hide="Display != Displays['OrderItems']"></div>
<div ng-include="'pages/report.productslist.php'" ng-hide="Display != Displays['ProductsList']"></div>

