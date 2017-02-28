app.controller('categoryController', ['$scope', '$interval', '$http', '$cookies', '$timeout', '$rootScope', '$location'

	, function ($scope, $interval, $http, $cookies, $timeout, $rootScope, $location) {

		$scope.Displays = {
			ListCategories: 1
			, EditCategory: 2
			, ListProductsOfCategory: 3
		}; //Object: Key-Value
		$scope.Display = $scope.Displays['ListCategories'];
		$scope.IsNewCategory = true;
		$scope.Category = {};
		$scope.Search = {};

		console.log("CATEGORY!");


		$timeout(function () {
				$scope.init();
			}, 100 // 0.1s to login
		);

		/************* INIT *********************** */
		$scope.init = function () {
				//Save first path, will return when login successful
				if ($rootScope.History.length == 0)
					$rootScope.History.push($location.absUrl());

				if ($rootScope.IsLoggedIn){
					if ($rootScope.LoggedEmployee.AccessLevel >= 3 && $rootScope.LoggedEmployee.AccessLevel <=5){
						window.location.href = "#/"; //Rediriect
						$rootScope.showAlert(lang['AccessDenied']);
					} else {
						$scope.getCategories();
					}
				}else {
						window.location.href = "#/login";
				}
			}
			/************* EDIT CATEGORY *********************** */
		$scope.editProduct = function () {
			window.location.href = "#/products"; //Rediriect
		}

		/******** GET CATEGORIES ***************/
		$scope.getCategories = function () {

				$http.post('api/categories.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: 'getCategories'
				})).success(function (data, status, headers, config) {
					$scope.Categories = data.Categories;
					$scope.OldCategories = data.Categories;
					console.log(data);
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Get Categories");
				});
			}
			/******** GET PRODUCTS OF CATEGORY ***************/
		$scope.getProductsOfCategory = function (id) {
			$scope.Display = $scope.Displays['ListProductsOfCategory'];
			//$scope.IsNewUnit = true;
			$http.post('api/categories.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'getProductsOfCategory'
				, Id: id
			, })).success(function (data, status, headers, config) {
				$scope.ProductsOfCategory = data.ProductsOfCategory;
				//	$scope.OldCategories = data.Categories;
				console.log(data);
				console.log("Connection: Get Products Of Category");
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get Products Of Category");
			});
		}

		/******** Click OnIsFavorite ************** */
		$scope.clickOnIsFavorite = function(p){
			console.log('clickOnIsFavorite');
			$http.post('api/products.api.php', JSON.stringify(
						{Username:$rootScope.LoggedEmployee.Username, Password: $rootScope.LoggedEmployee.Password,
						Command:'setIsFavorite',
						Id: p.Id,
						IsFavorite: 1 -  parseInt(p.IsFavorite),
						}))
				.success(function(data, status, headers, config) {
							console.log(data);
							$scope.getProductsOfCategory(p.CategoryId);
					 }).error(function(data, status, headers, config) {console.log("Error Connection: getProducts");});
		}


		/******** SEARCH CATEGORY ************** */
		$scope.onSearchCategories = function () {
			//console.log($scope.Se	arch.Query);
			var query = $scope.Search.Query.toLowerCase();
			if ($scope.Search.Query.length == 0) {
				$scope.Categories = $scope.OldCategories;
			} else {

				var category;
				var categories = [];
				var index = -1;
				for (var i = 0; i < $scope.OldCategories.length; i++) {
					if ($scope.OldCategories[i].Name.toLowerCase().search(query) >= 0) {
						index++;
						category = $scope.OldCategories[i];
						category.IndexInArr = index;
						categories.push(category);
					}
				}
				$scope.Categories = categories;
			}

		}

		/******** NEW CATEGORY ************** */
		$scope.newCategory = function () {
			$scope.Display = $scope.Displays['EditCategory'];
			$scope.IsNewUnit = true;
			$scope.Category = {
				Id: 0
				, Image: 'noavatar.png'
			};
		}

		/******** EDIT CATEGORY ************** */
		$scope.editCategory = function (IndexInArr) {
			$scope.Display = $scope.Displays['EditCategory'];
			$scope.IsNewCategory = false;
			$scope.Category = $scope.Categories[IndexInArr];
			console.log($scope.Category);
		}

		/******** SAVE CATEGORY ************** */
		$scope.saveCategory = function () {
			var command = "newCategory";

			if ($scope.IsNewCategory == false) {
				command = "editCategory";
			}

			//Send through HTTP
			$http.post('api/categories.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: command
					, Id: $scope.Category.Id
					, Name: $scope.Category.Name
					, Image: $scope.Category.Image
				, }))
				.success(function (data, status, headers, config) {
					console.log(data);

					if (Boolean(data.Status)) {
						$scope.getCategories();
						$scope.showDisplay($scope.Displays['ListCategories']);
					} else
						$rootScope.showAlert(data.Message);

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post category");
				});
		}

		/******** DELETE CATEGORY ************** */
		$scope.deleteCategory = function (id) {
			//Send through HTTP
			$http.post('api/categories.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "deleteCategory"
					, Id: id
				, }))
				.success(function (data, status, headers, config) {
					console.log(data);

					$scope.getCategories();
					$scope.showDisplay($scope.Displays['ListCategories']);

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post Category");
				});
		}

		/******** SHOW DISPLAY ************** */
		$scope.showDisplay = function (display) {
				$scope.Display = display;
			}
			/******** UPLOAD FILE ************** */
		$scope.uploadFile = function () {
			var blobFile = $('#filechooser')[0].files[0];
			var fd = new FormData();
			fd.append("fileToUpload", blobFile);
			fd.append('dir', 'categorie/');
			fd.append('oldImage', $scope.Category.Image);
			fd.append('nameImage', $scope.Category.Name);
			$.ajax({
				url: "upload.php"
				, type: "POST"
				, data: fd
				, processData: false
				, contentType: false
				, success: function (response) {
					data = JSON.parse(response);
					console.log(data);
					$scope.Category.Image = data.FileName;
					$rootScope.showAlert(data.Message);
					$scope.$digest();
				}
				, error: function (jqXHR, textStatus, errorMessage) {
					console.log(errorMessage); // Optional
				}
			});
		}

}]);
