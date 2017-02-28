<div class="text-center">

	<div class="panel panel-info ">
		<div class="row panel-heading height-panel-custom">
			<div class="col-xs-3">
				<p class="panel-header-custom-left" ng-click="backView('3')">{{lang['Back']}}</p>
			</div>

		</div>
		<div >
			<div class="table-responsive" id="xys">
				<table class="table table-striped">
					<thead>
						<th class="text-center">{{lang['Product']}}</th>
						<th class="text-center">{{lang['Quantity']}}</th>
						<th class="text-center">{{lang['Price']}}</th>
						<th class="text-center">{{lang['Total']}}</th>
						</thead>
					<tbody style="font-weight: normal;" ng-repeat="r in OrderItemsByReceipt">
						<tr>
							<td align="center">{{r.Name}}</td>
							<td align="center">{{r.Quantity | number:0}}</td>
							<td align="center">{{r.Price | number:0}}</td>
							<td align="center">{{r.Price * r.Quantity |number:0 }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<p>{{lang['NumOfItems']}} : <b> {{NumOfReceipt | number:0}} </b></p>
			<p>{{lang['ExtraPaidPerItem']}} : <b> {{ExtraPaidPerItem | number:0}} </b></p>
			<p>{{lang['ExtraPaid']}} : <b> {{NumOfReceipt * ExtraPaidPerItem}} </b></p>
			<p>{{lang['Total']}} : <b> {{AmountOfOrderItems | number:0}} </b></p>

		</div>
	</div>
</div>
