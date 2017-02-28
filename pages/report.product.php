<table class="table table-striped table-hover">
	<br>

	<div class="row">
		<div class="col-md-8 col-sm-3">
			Tổng cộng: <b>{{statics.count}} kết quả</b>
		</div>
		<div class="col-md-2 col-sm-3">
			Số ly: <b>{{statics.totalInfo.squantity | toEuros}}</b> </div>
		<div class="col-md-2 col-sm-3">
			Tổng tiền: <b>{{statics.totalInfo.samount | toEuros}}</b> </div>
	</div>
	<br>
	<thead>
		<tr>
			<th><span>Tên</span></th>
			<th><span>Đơn giá</span></th>
			<th><span>Số ly</span></th>
			<th><span>Tổng</span></th>
		</tr>
	</thead>
	<tbody>
		<tr ng-show="ambulances.data.length <= 0">
			<td colspan="5" style="text-align:center;">Loading new data!!</td>
		</tr>
		<tr dir-paginate="x in statics.data|itemsPerPage:itemsPerPage" total-items="total_count" current-page="page.pageNo" pagination-id="product-list" ng-click="showAllReCeiptOfProduct(x)">
			<td>{{x.Name}}</td>
			<td>{{x.Price}}</td>
			<td>{{x.Quantity}}</td>
			<td>{{x.Amount}}</td>
		</tr>
	</tbody>
</table>
<div class="header-pagination">
	<dir-pagination-controls pagination-id="product-list" max-size="30" direction-links="true" boundary-links="true" on-page-change="getData(newPageNumber)"></dir-pagination-controls>
</div>