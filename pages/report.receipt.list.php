<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th><span>Id</span></th>
 			<th><span>NV Đặt</span></th>
 			<th><span>NV T.Toán</span></th>
			<th><span>Bàn</span></th>
			<th><span>Tổng</span></th>
			<th><span>Phụ thu</span></th>
			<th><span>Tổng tiền</span></th>
			<th><span>Vào bàn</span></th>
			<th><span>Ra bàn</span></th>
			<th><span>Thao tác</span></th>
		</tr>
	</thead>
	<tbody>
		<tr ng-show="ambulances.data.length <= 0">
			<td colspan="5" style="text-align:center;">Loading new data!!</td>
		</tr>
		<tr dir-paginate="x in receipts.data|itemsPerPage:itemsPerPage" total-items="total_count" current-page="pageNo" pagination-id="receipt-list"> 
			<td>{{x.Id}}</td>
			<td>{{x.CheckInEmp}}</td>
			<td>{{x.CheckOutEmp}}</td>
			<td>{{x.DeskNo}}</td>
			<td>{{x.SubTotal}}</td>
			<td>{{x.ExtraPaid}}</td>
			<td>{{x.Total}}</td>
			<td>{{x.CheckInTime}}</td>
			<td>{{x.CheckOutTime}}</td>
			<td>
				 
				<button type="button" class="btn btn-default" ng-click="clickReceiptOnDesk(x)">Sửa</button>
				<button type="button" class="btn btn-default" ng-click="trashReceipt(x)">Xóa</button>
			</td>
		</tr>
	</tbody>
</table>
<div class="header-pagination">
	<dir-pagination-controls pagination-id="receipt-list" max-size="30" direction-links="true" boundary-links="true" on-page-change="getListReceipt(newPageNumber)"></dir-pagination-controls>
</div>