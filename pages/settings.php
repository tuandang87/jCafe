<div ng-controller="settingController">

	<div class="row" ng-include="'templates/header.php'"></div>
	<div ng-include="'templates/alert.php'"></div>

	<div class="box" style="margin-left: 2%; margin-right:2%;">

		<div class="row">
			<label class="medium"> {{lang['Name']}}</label>
			<span><input type="text" class="xlarge" ng-model="Settings.Name"></span>
		</div>


		<div class="row">
			<label class="medium"> {{lang['NumOfDesks']}}</label>
			<span><input type="text" class="medium" ng-model="Settings.NumOfDesks" ></span>
		</div>

		<div class="row">
			<label class="medium"> {{lang['ExtraPaidPerItem']}}</label>
			<span><input type="text" class="medium" ng-model="Settings.ExtraPaidPerItem" ></span>
		</div>

		<div class="row">
			<label class="medium"> {{lang['Address']}}</label>
			<span><input type="text" class="xlarge" ng-model="Settings.Address" ></span>
		</div>

		<div class="row">
			<label class="medium"> {{lang['Phone']}}</label>
			<span><input type="text" class="xlarge" ng-model="Settings.Phone" ></span>
		</div>

		<div class="row">
			<label class="medium"> {{lang['Email']}}</label>
			<span><input type="text" class="xlarge" ng-model="Settings.Email" ></span>
		</div>

		<div class="row">
			<label class="medium"> {{lang['SendReport']}}</label>
			<button class="btn btn-primary" ng-click="sentReport()" >Sent Report
			</button>
		</div>

		<div class="row" style="margin-top: 20px;"></div>

		<div class="row">
			<div class="col-xs-4"></div>
			<div class="col-xs-4">
				<button class="btn btn-success center" ng-click="saveSettings()">
					<span class="fa fa-floppy-o" aria-hidden="true"></span> {{lang['Save']}}
				</button>
			</div>
			<div class="col-xs-4"></div>
		</div>
	</div>
</div>
