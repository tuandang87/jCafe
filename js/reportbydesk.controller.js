app.controller('reportByDeskController', ['$scope', '$interval', '$http', '$cookies', '$timeout', '$rootScope', '$location', function ($scope, $interval, $http, $cookies, $timeout, $rootScope, $location) {

	$scope.Displays = {
		Desks: 1
		, Receipts: 2
		, OrderItems: 3
		, EditReciept: 4
	}; //Object: Key-Value
	$scope.Display = $scope.Displays['Desks'];
	$scope.ReceiptByDesk = {};
	$scope.DeskChoose = {};
	$scope.ReceiptChoose = {};
	console.log("RECEIPTS BY DESK!");
	$timeout(function () {
			$scope.init();
		}, 100 // 0.1s 
	);


	/************* INIT *********************** */
	$scope.init = function () {
		//Stop get desks info while viewing other functions
		$interval.cancel($rootScope.refeshTimer);

		//Save first path, will return when login successful
		if ($rootScope.History.length == 0)
			$rootScope.History.push($location.absUrl());

		if ($rootScope.LoggedEmployee.AccessLevel == 0) {
			window.location.href = "#/"; //Redirect
		} else {
			$scope.getAllDeskByToday();
		}
	};


	/******** GET ALL DESK BY TODAY ***************/
	$scope.getAllDeskByToday = function () {
		$http.post('api/reportbydesk.api.php', JSON.stringify({
			Username: $rootScope.LoggedEmployee.Username
			, Password: $rootScope.LoggedEmployee.Password
			, AccessLevel: $rootScope.LoggedEmployee.AccessLevel
			, Command: 'getAllDeskByToday'
		})).success(function (data, status, headers, config) {
			$scope.DesksByToday = data.DesksByToday;
			$scope.TotalReciepts = 0;
			$scope.TotalAmounts = 0;
			n = $scope.DesksByToday.length;
			for (i = 0; i < n; i++) {
				$scope.TotalReciepts += parseInt($scope.DesksByToday[i].NumOfReceipts);
				$scope.TotalAmounts += parseInt($scope.DesksByToday[i].Amount);
			}
			console.log(data);
		}).error(function (data, status, headers, config) {
			console.log("Error Connection: Get ReceiptByDesks");
		});
	};


	/******** GET ALL RECEIPTS BY DESK ***************/
	$scope.clickDeskByToday = function (deskByToday) {
		$scope.DeskChoose = deskByToday;
		$scope.deskNoHeader = deskByToday.DeskNo;
		$scope.Display = $scope.Displays['Receipts'];
		$http.post('api/reportbydesk.api.php', JSON.stringify({
			Username: $rootScope.LoggedEmployee.Username
			, Password: $rootScope.LoggedEmployee.Password
			, DeskNo: deskByToday.DeskNo
			, Command: 'getReceiptByDesks'
		})).success(function (data, status, headers, config) {
			$scope.ReceiptByDesks = data.ReceiptByDesks;
			$scope.TotalAmount = 0;
			n = $scope.ReceiptByDesks.length;
			for (i = 0; i < n; i++) {
				$scope.TotalAmount += parseInt($scope.ReceiptByDesks[i].Total);
			}
			console.log(data);
		}).error(function (data, status, headers, config) {
			console.log("Error Connection: Get ReceiptByDesks");
		});
	};


	/******** GET ORDER ITEMS BY RECEIPT ***************/
	$scope.clickReceiptOnDesk = function (receiptOnDesk) {
		$scope.ReceiptChoose = receiptOnDesk;
		$scope.receiptIdHeader = receiptOnDesk.Id;
		$scope.ExtraPaidPerItem = receiptOnDesk.ExtraPaidPerItem;
		$scope.ExtraPaid = receiptOnDesk.ExtraPaid;
		$scope.NumOfItems = receiptOnDesk.NumOfItems;
		$scope.Display = $scope.Displays['OrderItems'];
		$http.post('api/reportbydesk.api.php', JSON.stringify({
			Username: $rootScope.LoggedEmployee.Username
			, Password: $rootScope.LoggedEmployee.Password
			, ReceiptId: receiptOnDesk.Id
			, Command: 'getOrderItemsByReceipt'
		})).success(function (data, status, headers, config) {
			$scope.OrderItemsByReceipt = data.OrderItemsByReceipt;
			$scope.AmountOfOrderItems = 0;
			n = $scope.OrderItemsByReceipt.length;
			for (i = 0; i < n; i++) {
				$scope.AmountOfOrderItems += parseInt($scope.OrderItemsByReceipt[i].Quantity) * parseInt($scope.OrderItemsByReceipt[i].Price);
			}
			console.log(data);
		}).error(function (data, status, headers, config) {
			console.log("Error Connection: Get ReceiptByDesks");
		});
	};


	/******** GET PRODUTCS FOR EDIT RECEIPT***************/
	$scope.getProducts = function () {
		$http.post('api/products.api.php', JSON.stringify({
			Username: $rootScope.LoggedEmployee.Username
			, Password: $rootScope.LoggedEmployee.Password
			, Command: 'getProducts'
		})).success(function (data, status, headers, config) {
			$scope.ProductsForEdit = data.Products;
			console.log(data);
			delete data;
			console.log(data);
		}).error(function (data, status, headers, config) {
			console.log("Error Connection: Get Products");
		});
	};


	/*********** GET ORDER ITEMS IN RECEIPT******************* */
	$scope.getReceiptInfo = function () {
		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'getReceiptInfo'
				, DeskNo: $scope.DeskChoose.DeskNo
				, ReceiptId: $scope.ReceiptChoose.Id
			}))
			.success(function (data, status, headers, config) {
				$scope.OrderItems = data.OrderItems;
				$scope.Receipt = data.Receipt;
				console.log(data);
				//Update quantity's Products For Edit
				for (var i = 0; i < $scope.ProductsForEdit.length; i++) {
					$scope.ProductsForEdit[i]["Quantity"] = 0;
					for (var j = 0; j < $scope.OrderItems.length; j++) {
						if ($scope.OrderItems[j].ProductId == $scope.ProductsForEdit[i].Id)
							$scope.ProductsForEdit[i]["Quantity"] = $scope.OrderItems[j].Quantity;
					}
				}
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get order items");
			});
	};
	$scope.getProducts();


	$scope.editReciept = function () {
		$scope.Display = $scope.Displays['EditReceipt'];
		$scope.getReceiptInfo();
	};


	/*********** CLICK ON THE PRODUCT *******************/
	$scope.clickOnProduct = function (index, isIncrease) {
		console.log($scope.ProductsForEdit[index].Name);
		//Incsease quantity on display
		if (isIncrease)
			$scope.ProductsForEdit[index].Quantity++;
		else {
			$scope.ProductsForEdit[index].Quantity--;
			if ($scope.ProductsForEdit[index].Quantity < 0)
				$scope.ProductsForEdit[index].Quantity = 0;
		}

		//Calculate subtotal
		$scope.Receipt.SubTotal = 0;
		for (var i = 0; i < $scope.ProductsForEdit.length; i++) {
			$scope.Receipt.SubTotal += $scope.ProductsForEdit[i]["Price"] * $scope.ProductsForEdit[i]["Quantity"];
		}
	};


	/*********** SUBMIT CHANGED RECEIPT *******************/
	$scope.submitChangedReceipt = function () {
		// Compare OrderItems and Products to find new either OrderItem Or
		// Update items
		for (var i = 0; i < $scope.ProductsForEdit.length; i++) {
			// Check whether product i is in the order items list
			var isInList = false;
			for (var j = 0; j < $scope.OrderItems.length; j++) {
				if ($scope.OrderItems[j].ProductId == $scope.ProductsForEdit[i].Id) {
					isInList = true;
					break;
				}
			}
			// Update old items
			if (isInList) {
				// Change quantity if a product has been updated
				if ($scope.OrderItems[j].Quantity != $scope.ProductsForEdit[i]["Quantity"]) {
					$scope.OrderItems[j].Quantity = $scope.ProductsForEdit[i]["Quantity"];
					$scope.OrderItems[j].IsUpdated = 1;
				}
			} else {
				if ($scope.ProductsForEdit[i]["Quantity"] > 0) {
					var orderItem = {
						Id: 0, // New item
						ReceiptId: $scope.ReceiptChoose.Id, // Old receipt
						ProductId: $scope.ProductsForEdit[i]["Id"]
						, Name: $scope.ProductsForEdit[i]["Name"]
						, Price: $scope.ProductsForEdit[i]["Price"]
						, Quantity: $scope.ProductsForEdit[i]["Quantity"]
						, IsUpdated: 0
					, };
					// Push new item to list
					$scope.OrderItems.push(orderItem);
				}
			}
		} // end of i
		console.log("ORDER ITEMS");
		console.log($scope.OrderItems);
		//Submit
		if ($scope.OrderItems.length != 0) {
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "postUpdatedOrderItems"
					, ReceiptId: $scope.ReceiptChoose.Id, // Table: Receipts: Update
					SubTotal: $scope.Receipt.SubTotal, // Table: Receipts: New/Update
					Paid: $scope.Receipt.Paid
					, DueChange: $scope.Receipt.DueChange
					, Status: $scope.Receipt.DueChange > 0 ? 2 : 1, // 2. Completed, 1.Ordered
					Note: $scope.Receipt.Note
					, EmployeeId: $scope.EmployeeId, // Table: Receipts: New
					OrderItems: $scope.OrderItems
				}))
				.success(function (data, status, headers, config) {
					console.log(data);
					if (Boolean(data.Status)) {
						//UPDATE ALL RECEIPT DATA
						$scope.clickReceiptOnDesk($scope.ReceiptChoose);
						$scope.clickDeskByToday($scope.DeskChoose);
						$scope.getAllDeskByToday();
						//Back to last view
						$scope.Display = $scope.Displays['OrderItems'];
					}
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post order items");
				});
		}
	};
	
	
	/*********** SEARCH FILTER *******************/
	$scope.Filter = {};
	$scope.filterUnsigned = function (item) {
		if (!$scope.Filter.KeyWord) {
			return true;
		}
		str = $scope.Filter.KeyWord;
		str = str.toLowerCase();
		str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
		str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
		str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
		str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
		str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
		str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
		str = str.replace(/đ/g, "d");
		stra = item.Name;
		stra = stra.toLowerCase();
		stra = stra.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
		stra = stra.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
		stra = stra.replace(/ì|í|ị|ỉ|ĩ/g, "i");
		stra = stra.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
		stra = stra.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
		stra = stra.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
		stra = stra.replace(/đ/g, "d");
		return (stra.indexOf(str) > -1);
	};


	//Back to last view
	$scope.backView = function (paramIndex) {
		if (paramIndex == 1) {
			$scope.Display = $scope.Displays['Desks'];
		} else if (paramIndex == 2) {
			$scope.Display = $scope.Displays['Receipts'];
		} else if (paramIndex == 3) {
			$scope.Display = $scope.Displays['OrderItems'];
		}
	};
}]);