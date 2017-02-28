<script>
	document.getElementById("filechooser").onchange = function () {
		document.getElementById("uploadFileName").value = this.value.replace(/.*[\/\\]/, '');
	};
</script>
<div class="box" style="margin-left: 2%; margin-right:2%; ">

	<div class="row">
		<label class="small"> {{lang['Image']}}</label>
		<img class="large" ng-src="{{ 'img/' + Category.Image}}">
	</div>


	<div class="row" style="margin-top: 5px;">
		<div class="col-xs-2"> <!-- 	<input type="text" id="uploadFileName" class="large"> --></div>
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

<div class="row" style="margin-top: 20px;"></div>
	<div class="row">
		<label class="small"> {{lang['Name']}}</label>
		<span><input type="text" class="xlarge"  ng-model="Category.Name"></span>
	</div>

	<div class="row" style="margin-top: 20px;"></div>
	<div class="row">
		<div class="col-xs-2" />
		<div class="col-xs-4">
			<button class="btn btn-success center" ng-click="saveCategory()">
				<span class="fa fa-floppy-o" aria-hidden="true"></span> {{lang['Save']}}
			</button>
		</div>
		<div class="col-xs-4">
			<button class="btn btn-danger center" ng-click="showDisplay(Displays['ListCategories'])">
				<span class="fa fa-times" aria-hidden="true"></span> {{lang['Cancel']}}
			</button>
		</div>
		<div class="col-xs-2" />
	</div>
</div>
