app.controller('productController', ['$scope', '$interval', '$http', '$cookies', '$timeout', '$rootScope', '$location'


	, function ($scope, $interval, $http, $cookies, $timeout, $rootScope, $location) {

		$scope.Displays = {
			List: 1
			, Edit: 2
		}; //Object: Key-Value
		$scope.Display = $scope.Displays['List'];
		$scope.IsNewProduct = true;
		$scope.Product = {};
		$scope.Search = {};

		console.log("PRODUCTS!");


		$timeout(function () {
				$scope.init();
			}, 100 // 0.1s to login
		);

		/************* INIT *********************** */
		$scope.init = function () {
			//Stop get desks info while viewing other functions
			$interval.cancel($rootScope.refeshTimer);

			//Save first path, will return when login successful
			if ($rootScope.History.length == 0)
				$rootScope.History.push($location.absUrl());

			if ($rootScope.IsLoggedIn){
				if ($rootScope.LoggedEmployee.AccessLevel >= 3 && $rootScope.LoggedEmployee.AccessLevel <=5){
					window.location.href = "#/"; //Rediriect
					$rootScope.showAlert(lang['AccessDenied']);
				} else {
					$scope.getProducts();
					$scope.getCategories();
				}
			}else {
					window.location.href = "#/login";
			}

		}

		/******** GET PRODUTCS ***************/
		$scope.getProducts = function () {

			$http.post('api/products.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'getProducts'
			})).success(function (data, status, headers, config) {
				$scope.Products = data.Products;
				n = $scope.Products.length;
				for (i = 0; i < n; i++) {
					s = $scope.Products[i].Name;
					s = s.toLowerCase();
					s = s.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
					s = s.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
					s = s.replace(/ì|í|ị|ỉ|ĩ/g, "i");
					s = s.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
					s = s.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
					s = s.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
					s = s.replace(/đ/g, "d");
					$scope.Products[i].NameSearch = s;
				}
				console.log(data);
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get Products");
			});
		}

		/******** GET CATEGORIES ***************/
		$scope.getCategories = function () {

			$http.post('api/categories.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'getCategories'
			})).success(function (data, status, headers, config) {
				$scope.Categories = data.Categories;
				console.log(data);
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get Categories");
			});
		}


		/******** SEARCH PRODUCT ************** */
		$scope.onSearchProducts = function () {
			//console.log($scope.Se	arch.Query);
			var query = $scope.Search.Query.toLowerCase();
			if ($scope.Search.Query.length == 0) {
				$scope.Products = $scope.OldProducts;
			} else {

				var product;
				var products = [];
				var index = -1;
				for (var i = 0; i < $scope.OldProducts.length; i++) {
					if ($scope.OldProducts[i].Name.toLowerCase().search(query) >= 0) {
						index++;
						product = $scope.OldProducts[i];
						product.IndexInArr = index;
						products.push(product);
					}
				}
				$scope.Products = products;
			}

		}

		/******** NEW PRODUCT ************** */
		$scope.newProduct = function () {
			$scope.Display = $scope.Displays['Edit'];
			$scope.IsNewUnit = true;
			$scope.Product = {
				Id: 0
				, Price: 0
				, CategoryId: 0
				, IsFavorite: 0
				, Image: $scope.Categories[0].Image
			};
			$scope.Product.SelectedCategory = $scope.Categories[0];
		}

		/******** EDIT PRODUCT ************** */
		$scope.editProduct = function (IndexInArr) {
			$scope.Display = $scope.Displays['Edit'];
			$scope.IsNewProduct = false;
			$scope.Product = $scope.Products[IndexInArr];

			var catIndex = $scope.findCategoryIndex($scope.Product.CategoryId);
			$scope.Product.SelectedCategory = $scope.Categories[catIndex];

			console.log($scope.Product);
		}

		/********  FIND INDEX OF CATEGORY BY ***************/
		$scope.findCategoryIndex = function (catId) {
			for (var i = 0; i < $scope.Categories.length; i++) {
				if ($scope.Categories[i].Id == catId)
					return i;
			}
			return 0;
		}

		/******** SAVE PRODUCT ************** */
		$scope.saveProduct = function () {
			var isFav = $scope.Product.IsFavoriteModel == true ? 1 : 0;
			var command = "newProduct";

			if ($scope.IsNewProduct == false) {
				command = "editProduct";
			}

			//Send through HTTP
			$http.post('api/products.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: command
					, Id: $scope.Product.Id
					, Name: $scope.Product.Name
					, Price: $scope.Product.Price
					, CategoryId: $scope.Product.SelectedCategory.Id
					, IsFavorite: isFav
					, Image: $scope.Product.Image
				, }))
				.success(function (data, status, headers, config) {
					console.log(data);

					if (Boolean(data.Status)) {
						$scope.getProducts();
						$scope.showDisplay($scope.Displays['List']);
					} else
						$rootScope.showAlert(data.Message);

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post product");
				});
		}

		/******** DELETE PRODUCT ************** */
		$scope.deleteProduct = function (id) {
			//Send through HTTP
			$http.post('api/products.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "deleteProduct"
					, Id: id
				, }))
				.success(function (data, status, headers, config) {
					console.log(data);

					$scope.getProducts();
					$scope.showDisplay($scope.Displays['List']);

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post product");
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
			fd.append('dir', 'product/');
			fd.append('oldImage', $scope.Product.Image);
			fd.append('nameImage', $scope.Product.CategoryId+$scope.Product.Name);
			$.ajax({
				url: "upload.php"
				, type: "POST"
				, data: fd
				, processData: false
				, contentType: false
				, success: function (response) {
					data = JSON.parse(response);
					console.log(data);
					$scope.Product.Image = data.FileName;
					$rootScope.showAlert(data.Message);
				}
				, error: function (jqXHR, textStatus, errorMessage) {
					console.log(errorMessage); // Optional
				}
			});
		}

		$scope.changedCategorie = function (Categorie) {
			$scope.Product.Image = Categorie.Image;
		};

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
							$scope.getProducts();
					 }).error(function(data, status, headers, config) {console.log("Error Connection: getProducts");});
		}


		/*********** SEARCH FILTER *******************/
		$scope.Search = {};
		$scope.filterUnsigned = function (item) {
			if (!$scope.Search.Name) {
				return true;
			}
			str = $scope.Search.Name;
			str = str.toLowerCase();
			str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
			str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
			str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
			str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
			str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
			str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
			str = str.replace(/đ/g, "d");
			s = item.NameSearch;
			return (s.indexOf(str) > -1);
		};
}]);
