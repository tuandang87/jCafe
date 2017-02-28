<script>
	document.getElementById("filechooser").onchange = function () {
		document.getElementById("uploadFileName").value = this.value.replace(/.*[\/\\]/, '');
	};
</script>

<div class="box" style="margin-left: 1%; margin-right:1%;">
	<div class="row">
		<label  class="small"> {{lang['Image']}}</label>
		<img class="large" ng-src="{{ 'img/' + Employee.Image}}">
	</div>

	<div class="row">
		<div class="col-xs-2"> <!-- <input type="text" id="uploadFileName" class="large"> --></div>
		<div class="col-xs-5">
			<label class="custom-file-upload btn-info">
				<input type="file" id="filechooser" />
				<i class="fa fa-cloud-upload"></i> {{lang['ChooseImage']}}
			</label>

		</div>
		<div class="col-xs-5">
			<button class="btn btn-warning" ng-click="uploadFile()">
				<span class="fa fa-cloud-upload" aria-hidden="true"></span> {{lang['UploadImage']}}
			</button>
		</div>
	</div>

	<div class="row">
		<label  class="medium"> {{lang['Username']}}</label>
		<span><input type="text" class="large" ng-model="Employee.Username"></span>
	</div>
	<div class="row">
		<label  class="medium"> {{lang['Password']}}</label>
		<span><input type="password" class="large" ng-model="Employee.Password" ></span>
	</div>
	<div class="row">
		<label  class="medium"> {{lang['AccessLevel']}}</label>
		<span></span>
		<select name="singleSelect" id="singleSelect" ng-model="Employee.AccessLevel">
			<option value="">-{{lang['SelectAccessLevel']}}-</option>
			<option value="1">{{lang['Admin']}}</option>
			<option value="2">{{lang['Manager']}}</option>
			<option value="3">{{lang['User']}}</option>
			<option value="4">{{lang['Cashier']}}</option>
			<option value="5">{{lang['Bartender']}}</option>
		</select>
		<br>
	</div>
	<div class="row">
		<label  class="medium"> {{::lang['Region']}}</label>
		<span><input type="text" class="large" ng-model="Employee.RegionNo" ></span>
	</div>
	<div class="row">
		<label  class="medium"> {{lang['FirstName']}}</label>
		<span><input type="text" class="large" ng-model="Employee.FirstName" ></span>
	</div>
	<div class="row">
		<label  class="medium"> {{lang['LastName']}}</label>
		<span><input type="text" class="large" ng-model="Employee.LastName" ></span>
	</div>



	<div class="row">
		<label  class="medium"> {{lang['Email']}}</label>
		<span><input type="text" class="xlarge" ng-model="Employee.Email"></span>
	</div>


	<div class="row">
		<label  class="medium"> {{lang['Phone']}}</label>
		<span><input type="text" class="xlarge" ng-model="Employee.Phone"></span>
	</div>


	<div class="row">
		<label  class="medium"> {{lang['Birthday']}}</label>
		<span><input type="text" class="xlarge" ng-model="Employee.Birthday"></span>
	</div>


	<div class="row">
		<label  class="medium"> {{lang['Address']}}</label>
		<span><input type="text" class="xlarge" ng-model="Employee.Address"></span>
	</div>

	<div class="row" style="margin-top: 10px;"> </div>
	<div class="row">
		<div class="col-xs-1"></div>
		<div class="col-xs-3">
			<button ng-if="EditButton == true" class="btn btn-success center" ng-click="saveEmployee()">
				<span class="fa fa-floppy-o" aria-hidden="true"></span> {{lang['Save']}}
			</button>
		</div>

		<div class="col-xs-3">
			<button ng-if="DeleteButton == true" class="btn btn-danger center" ng-click="deleteEmployee()">
				<span class="fa fa-times" aria-hidden="true"></span> {{lang['Delete']}}
			</button>
		</div>

		<div class="col-xs-3">
			<button class="btn btn-warning center" ng-click="showDisplay(Displays['List'])">
				<span class="fa " aria-hidden="true"></span> {{lang['Cancel']}}
			</button>
		</div>
		<div class="col-xs-2"></div>
	</div>


</div>
