
<div class="row" style="margin-left: 5px; margin-top: 5px; margin-bottom: 15px;">
 	<div ng-if="AddButton" class="col-xs-4">
		<input type="button" value="{{lang['NewEmployee']}}" ng-click="newEmployee()" class="btn btn-info" />
	</div>
	<div class="col-xs-8">
		<input type="text" ng-model="Search.FirstName"
			placeholder="{{lang['Search']}}"
			class="form-control" />
	</div>
</div>


	<div class="scroll-list">
<div class="row employees ">
 	<div class="col-xs-3 col-sm-2 col-md-1 employee" ng-repeat="employee in Employees |filter:Search"
		ng-click="clickOnEmployee(employee)">
		<div class="row employee-image" back-img="img/{{employee.Image}}"></div>
		<div class='employee-name'>{{employee.Username}}</div>
	</div>
	</div>
</div>
