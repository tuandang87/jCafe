<div class="text-center">

	<div class="panel panel-info ">
		<div class="panel-heading " >{{lang['Reporting']}}</div>
		<div class="panel-body">
			<div class="table-responsive" id="today">
				<table class="table table-hover">
					<thead>
						<th class="text-center">{{lang['Employees']}}</th>
						<th class="text-center">{{lang['TotalReceipts']}}</th>
						<th class="text-center">{{lang['Total']}}</th>
					</thead>
					<tbody style="font-weight: normal;" ng-repeat="r in EmployeesReports">
						<tr ng-click="getAllReportsOfEmployee(r)">
							<td align="center">{{r.EmpName}}</td>
							<td align="center">{{r.NumOfReceipts| number:0}}</td>
							<td align="center">{{r.Amount}}</td>
						
						</tr>
					</tbody>
				</table>
			</div>
			<p>{{lang['TotalReceipts']}} : <b> {{TotalReciepts | number:0}} </b></p>
			<p>{{lang['Amount']}} : <b> {{TotalAmounts | number:0}} </b></p>

		</div>
	</div>
</div>
