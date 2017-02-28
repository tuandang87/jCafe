<script>
document.getElementById("filechooser").onchange = function () {
    document.getElementById("uploadFileName").value = this.value.replace(/.*[\/\\]/, '');
};
</script>

<div class="box" style="margin-left: 2%; margin-right:2%;">
	<div class="row">
		<label class="small"> {{lang['Image']}}</label>
		<img class="large" ng-src="{{ 'img/' + Product.Image}}">
	</div>

  <div class="row" style="margin-top: 5px;" >
    <div class="col-xs-2"> <!-- <input type="text" id="uploadFileName" class="large"> --> </div>
    <div class="col-xs-5">
      <label class="custom-file-upload btn-info">
          <input type="file" id="filechooser"/>
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
		<label class="small"> {{lang['Name']}}</label>
		<span><input type="text" class="xlarge" ng-model="Product.Name"></span>
	</div>

	<div class="row">
		<label class="small"> {{lang['Category']}}</label>
		<span>
			<select ng-model="Product.SelectedCategory" ng-change="changedCategorie(Product.SelectedCategory)"
				ng-options = "c.Name for c in Categories" />
		</span>
	<div/>

	<div class="row">
		<label class="small"> {{lang['Price']}}</label>
		<span><input type="text" class="large" ng-model="Product.Price" value="{{Product.Price|number:0}}"></span>
	</div>

	<div class="row">
		<label class="small"> {{lang['IsFavorite']}}</label>
		<span>&nbsp;<input type="checkbox" ng-model="Product.IsFavoriteModel" ng-checked="Product.IsFavorite == 1"></span>
	</div>

	<div class="row" style="margin-top: 20px;"></div>

	<div class="row">
		<div class="col-xs-2"></div>
		<div class="col-xs-4">
			<button class="btn btn-success center" ng-click="saveProduct()" >
				<span class="fa fa-floppy-o" aria-hidden="true"></span>
				{{lang['Save']}}
			</button></div>
		<div class="col-xs-4">
			<button class="btn btn-danger center" ng-click="showDisplay(Displays['List'])">
				<span class="fa fa-times" aria-hidden="true"></span>
				{{lang['Cancel']}}
			</button>
		</div>
		<div class="col-xs-2"></div>
	</div>
</div>
