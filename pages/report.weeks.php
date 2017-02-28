<table class="table table-striped table-hover">
		<br>

	<div class="row">
		<div class="col-md-2 col-sm-3">
			Tổng cộng: <b>{{statics.count}} kết quả</b>
		</div>
		<div class="col-md-2 col-sm-3">
			Tổng số hóa đơn: <b>{{statics.totalInfo.sreceipt}}</b> </div>
		<div class="col-md-3 col-sm-3">
			Tổng: <b>{{statics.totalInfo.ssubtotal | toEuros}}</b> </div>
		<div class="col-md-2 col-sm-3">
			Phụ thu: <b>{{statics.totalInfo.sextrapaid | toEuros}}</b> </div>
		<div class="col-md-3 col-sm-3">
			Tổng tiền: <b>{{statics.totalInfo.stotal | toEuros}}</b> </div>
	</div>

	<br>
	<thead>
		<tr>
			<th><span>Tuần</span></th>
			<th><span>Số hóa đơn</span></th>
			<th><span>Tổng</span></th>
			<th><span>Phụ thu</span></th>
			<th><span>Tổng tiền</span></th>
		</tr>
	</thead>
	<tbody>
		<tr ng-show="ambulances.data.length <= 0">
			<td colspan="5" style="text-align:center;">Loading new data!!</td>
		</tr>
		<tr dir-paginate="x in statics.data|itemsPerPage:itemsPerPage" total-items="total_count" current-page="page.pageNo" pagination-id="week-list" ng-click="showAllReCeipt(x)">
			<td>{{x.ThoiGian}}</td>
			<td>{{x.CountReceipt}}</td>
			<td>{{x.SubTotals}}</td>
			<td>{{x.ExtraPaids}}</td>
			<td>{{x.Totals}}</td>
		</tr>
	</tbody>
</table>
<div class="header-pagination">
	<dir-pagination-controls pagination-id="week-list" max-size="30" direction-links="true" boundary-links="true" on-page-change="getData(newPageNumber)"></dir-pagination-controls>
</div>