<div ng-scrollable="{scrollXAlways:'true',scrollX:'bottom',scrollY:'none'}" style="width: 100%; height: 70px;">
	<div class="row guest-queue-label" style="margin-top: 10px;">
		<b></b> <span><button
			class="guest-queue"
			ng-click="clickOnDesk(q.IndexInArr)" ng-repeat="q in GuestQueue">{{::q.No}}</button></span>
	</div>
</div>
<div ng-scrollable="{scrollXAlways:'true',scrollX:'bottom',scrollY:'none'}" style="width: 100%; height: 70px;">
	<div class="row serving-queue-label">
		<b></b> <span><button
			ng-repeat="q in ServingQueue"
			ng-class="q.IsOverTime == '1' ? 'overtime-queue' : 'serving-queue'"
			ng-click="clickOnDesk(q.IndexInArr)" >{{::q.No}}</button></span>
	</div>

</div>

<!-- DESKS -->
<div class="row desks" ng-if="LoggedEmployee.AccessLevel == 1 || LoggedEmployee.AccessLevel == 2 || LoggedEmployee.AccessLevel == 3 || LoggedEmployee.AccessLevel == 4">
	<div class="col-xs-3 col-sm-2 col-md-1 desk" ng-repeat="desk in Desks" ng-click="clickOnDesk(desk.IndexInArr)">
		<div class="row desk-amount">
			<b ng-if="desk.IsBusy">{{desk.Total/1000}} </b> <span style="color: Black;">K</span>
		</div>
		<div class="row">
			<p class="desk-name">{{::desk.No}}</p>
		</div>
		<div class="row desk-image">
			<img ng-if="desk.IsBusy" class="desk-image" src="img/busy-desk.png">

		</div>
	</div>
</div>
