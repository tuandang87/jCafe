<div class="text-center">
    <div class="panel panel-info ">
	   <div class="panel-heading " >
	      <div class="row">
		     <p class="col-xs-2" ng-click="backView('2')">{{lang['Back']}}</p>
		     <p class="col-xs-7">{{lang['Reporting']}}</p>
		     </div>
	   </div>
		<div class="panel-body">
			<div class="table-responsive" id="reofemploy">
				<table class="table table-striped">
					<thead>
						<th class="text-center">{{lang['IdReceipt']}}</th>
						<th class="text-center">{{lang['Desk']}}</th>
						<th class="text-center">{{lang['CheckInTime']}}</th>
						<th class="text-center">{{lang['CheckOutTime']}}</th>
						<th class="text-center">{{lang['Total']}}</th>
					</thead>
					<tbody style="font-weight: normal;" ng-repeat="r in ReportsOfEmployee">
						<tr ng-click="getOrderItemsByReceipt(r,Fromday,Today)">
							<td align="center">{{r.Id}}</td>
							<td align="center">{{r.DeskNo}}</td>
							<td align="center">{{r.DateIn}}</td>
							<td align="center">{{r.Date}}</td>
							<td align="center">{{r.Total | number:0}}</td>

						</tr>
					</tbody>
				</table>
			</div>
			<p>{{lang['TotalReceipts']}} : <b> {{TotalReciepts | number:0}} </b></p>
			<p>{{lang['Amount']}} : <b> {{TotalAmounts | number:0}} </b></p>

		</div>
	</div>
</div>
