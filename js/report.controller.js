app.controller('reportController', ['$scope', '$interval', '$http', '$cookies', '$timeout', '$rootScope', '$location', 'appLibs', function ($scope, $interval, $http, $cookies, $timeout, $rootScope, $location, $uibModal, appLibs) {

	//Save first path, will return when login successful
	if ($rootScope.History.length == 0)
		$rootScope.History.push($location.absUrl());


	if ($rootScope.IsLoggedIn) {
		if ($rootScope.LoggedEmployee.AccessLevel == 0) {
			window.location.href = "#/"; //Redirect to login
		} else {}
	} else {
		window.location.href = "#/login";
	}


	$scope.ReceiptByDesk = {};
	$scope.DeskChoose = {};
	$scope.ReceiptChoose = {};
	$scope.DoanhThu = 0;
	$scope.HD = 0;
	$scope.SLy = 0;

	$scope.Product = {};
	$scope.Search = {};
	$scope.Receipt = {};
	$http.post('api/report.api.php', JSON.stringify({
		Username: 'admin'
		, Password: 'e10adc3949ba59abbe56e057f20f883e'
		, AccessLevel: '1'
		, Command: 'getinfo'
	})).success(function (data, status, headers, config) {
		console.log(data.Info[0].Amount);
		$scope.DoanhThu = data.Info[0].Amount;
		$scope.HD = data.Info[0].NumOfReceipts;
		$scope.SLy = data.Info[0].Quantity;
	}).error(function (data, status, headers, config) {
		$scope.DoanhThu = 0;
		$scope.HD = 0;
		$scope.SLy = 0;
		console.log("Error Connection: Get ReceiptByDesks");
	});

	$scope.Displays = {
		Employees: 1
		, Receipts: 2
		, Edit: 3
		, Desk: 4
		, Day: 5
		, Week: 6
		, Month: 7
		, Product: 8
		, ProductsList: 9
		, OrderItems: 10
	}; //Object: Key-Value
	$scope.Display = $scope.Displays['Employees'];


	$scope.search = {};


	/*Loại thời gian lọc theo ngày, tuần này, tháng này*/
	$scope.search.dateType = 0;
	$scope.typeStaticsDate = function (index) {
		return $scope.search.dateType == index;
	};
	/*Xóa loại thời gian*/
	$scope.resetTypeStaticsDate = function (index) {
		$scope.search.dateType = 0;
	};

	$scope.KeyWord = '';
	/*Lấy danh sách hóa đơn của một type cụ thể*/
	$scope.getListReceipt = function (pageno, x) {
		console.log('GET LIST RECEIPTS');
		if ($scope.search.typeStatics == 1) {
			$scope.searchType.command = 'getReceipByEmployee';
			if (x != undefined)
				$scope.KeyWord = x.Username;
		} else if ($scope.search.typeStatics == 2) {
			$scope.searchType.command = 'getReceipByDesk';
			if (x != undefined)
				$scope.KeyWord = x.DeskNo;
		} else if ($scope.search.typeStatics == 3) {
			$scope.searchType.command = 'getReceipByProduct';
			if (x != undefined)
				$scope.KeyWord = x.ProductId;
		} else if ($scope.search.typeStatics == 4) {
			$scope.searchType.command = 'getReceipByDate';
			if (x != undefined)
				$scope.KeyWord = x.ThoiGian;
		} else if ($scope.search.typeStatics == 5) {
			$scope.searchType.command = 'getReceipByWeek';
			if (x != undefined)
				$scope.KeyWord = x.ThoiGian;
		} else if ($scope.search.typeStatics == 6) {
			$scope.searchType.command = 'getReceipByMonth';
			if (x != undefined)
				$scope.KeyWord = x.ThoiGian;
		}
		$rootScope.receipts = [];
		$http.post('api/report.api.php', JSON.stringify({
			Username: 'admin'
			, Password: 'e10adc3949ba59abbe56e057f20f883e'
			, AccessLevel: '1'
			, Command: $scope.searchType.command
			, KeyWord: $scope.KeyWord
			, Zone_ID: $scope.search.zoneStatics
			, FromDate: $scope.datetime.fromDate
			, ToDate: $scope.datetime.toDate
			, PageNo: pageno
			, ItemsPerPage: $scope.itemsPerPage
		})).success(function (data, status, headers, config) {
			$rootScope.receipts = data;
			$scope.total_count = $rootScope.receipts.count;
			console.log(data);
		}).error(function (data, status, headers, config) {
			console.log("Error Connection: Get ReceiptByDesks");
		});
	};

	$scope.cacheObject = {};
	/*Hiển thị danh sách hóa đơn của một đối tượng*/
	$scope.showAllReCeipt = function (x) {
		console.log("CHU YYYYYYYYYYYYYYYY");
		$scope.cacheObject = x;
		$scope.Display = $scope.Displays['Receipts'];
		$scope.getListReceipt(1, x);
	};

	/*Hiển thị danh sách hóa đơn của một đối tượng*/
	$scope.showAllReCeiptOfProduct = function (x) {
		$scope.cacheObject = x;
		$scope.Display = $scope.Displays['ProductDetail'];
		$scope.getListReceipt(1, x);
	};


	/*Loại thống kê*/
	$scope.search.typeStatics = 1;
	$scope.typeStatics = [{
		name: "Nhân viên"
		, id: 1
		}, {
		name: "Bàn"
		, id: 2
		}, {
		name: "Sản phẩm"
		, id: 3
		}, {
		name: "Ngày"
		, id: 4
		}, {
		name: "Tuần"
		, id: 5
		}, {
		name: "Tháng"
		, id: 6
		}];


	$scope.zoneStatics2 = [];
	$http.post('api/categories.api.php', JSON.stringify({
		Username: $rootScope.LoggedEmployee.Username
		, Password: $rootScope.LoggedEmployee.Password
		, Command: 'getCategories'
	})).success(function (data, status, headers, config) {
		$scope.Categories = data.Categories;
		$scope.OldCategories = data.Categories;
		console.log(data);

		$scope.zoneStatics2.push({ name: "Tất cả" , id: 0 });
		if($scope.Categories != null){
			var nz = $scope.Categories.length;
			for (i = 0; i < 16; i++) {
				$scope.zoneStatics2.push({
					name: $scope.Categories[i].Name
					, id: $scope.Categories[i].Id
				});
			}
		}
	}).error(function (data, status, headers, config) {
		console.log("Error Connection: Get Categories");
	});
	$scope.search.zoneStatics = 0;
	$scope.zoneStatics1 = [
		{
			name: "Tất cả"
			, id: 0
		}, {
			name: "Khu A"
			, id: 1
		}, {
			name: "Khu B"
			, id: 2
		}, {
			name: "Khu C"
			, id: 3
		}, {
			name: "Khu D"
			, id: 4
		}];
	
	$scope.zoneStatics = $scope.zoneStatics1;

	$scope.openNav = function () {
		document.getElementById("mySidenav").style.width = "180px";
		document.getElementById("main").style.marginLeft = "180px";
	};
	$scope.closeNav = function () {
		document.getElementById("mySidenav").style.width = "0";
		document.getElementById("main").style.marginLeft = "0";
	};
	$scope.openNav();
	$scope.datetime = {};
	var data = new Date($.now());
	var d = new Date
		, dformat = [d.getFullYear(), d.getMonth() + 1, d.getDate()
               ].join('-') + ' ';
	$scope.datetime.fromDate = dformat + '00:00:00';
	$scope.datetime.toDate = dformat + '23:59:59';


	//Set default current index button
	$rootScope.currentIndex = 1;
	//Set new current index button
	$scope.setCurrentIndex = function (index) {
		$rootScope.currentIndex = index;
	};
	//Check current index button
	$scope.isCurrentIndex = function (index) {
		return $rootScope.currentIndex == index;
	};


	$scope.searchType = {};
	$scope.searchType.type = 1;
	$scope.searchType.time = false;
	$scope.searchType.employee = false;
	$scope.searchReceipt = function () {
		$scope.changeView();
		if ($scope.search.typeStatics == 1) {
			$scope.searchType.command = 'getByEmployee';
		} else if ($scope.search.typeStatics == 2) {
			$scope.searchType.command = 'getByDesk';
		} else if ($scope.search.typeStatics == 3) {
			$scope.searchType.command = 'getByProduct';
		} else if ($scope.search.typeStatics == 4) {
			$scope.searchType.command = 'getByDay';
		} else if ($scope.search.typeStatics == 5) {
			$scope.searchType.command = 'getByWeek';
		} else if ($scope.search.typeStatics == 6) {
			$scope.searchType.command = 'getByMonth';
		}
		//		$scope.search.typeStatics = 1;
		$scope.page.pageNo = 1;
		$scope.getData($scope.page.pageNo);
	};
	$scope.disableSelectbox = false;
	$scope.changeView = function () {
		console.log($scope.search.typeStatics);
		if ($scope.search.typeStatics == 1) {
			$scope.Display = $scope.Displays['Employees'];
			$scope.disableSelectbox = false;
			$scope.zoneStatics = $scope.zoneStatics1;
		} else if ($scope.search.typeStatics == 2) {
			$scope.Display = $scope.Displays['Desk'];
			$scope.disableSelectbox = false;
			$scope.zoneStatics = $scope.zoneStatics1;
		} else if ($scope.search.typeStatics == 3) {
			$scope.Display = $scope.Displays['Product'];
			$scope.disableSelectbox = false;
			$scope.zoneStatics = $scope.zoneStatics2;
		} else if ($scope.search.typeStatics == 4) {
			$scope.Display = $scope.Displays['Day'];
			$scope.disableSelectbox = true;
		} else if ($scope.search.typeStatics == 5) {
			$scope.Display = $scope.Displays['Week'];
			$scope.disableSelectbox = true;
		} else if ($scope.search.typeStatics == 6) {
			$scope.Display = $scope.Displays['Month'];
			$scope.disableSelectbox = true;
		}
		$rootScope.statics = [];

	}




	$scope.page = {};


	//Config show data table and pagination
	$scope.page.pageNo = 1;
	$scope.total_count = 0;
	$scope.itemsPerPage = 6;
	$scope.employee = {};
	$scope.employee.Username = '';
	$scope.getData = function (pageno) {
		$rootScope.statics = [];
		$http.post('api/report.api.php', JSON.stringify({
			Username: 'admin'
			, Password: 'e10adc3949ba59abbe56e057f20f883e'
			, AccessLevel: '1'
			, Command: $scope.searchType.command
			, KeyWord: $scope.search.Key
			, Zone_ID: $scope.search.zoneStatics
			, FromDate: $scope.datetime.fromDate
			, ToDate: $scope.datetime.toDate
			, PageNo: pageno
			, ItemsPerPage: $scope.itemsPerPage
		})).success(function (data, status, headers, config) {
			$rootScope.statics = data;
			$scope.total_count = $rootScope.statics.count;
			console.log(data);
		}).error(function (data, status, headers, config) {
			console.log("Error Connection: Get ReceiptByDesks");
		});
	};
	$scope.getData(1);
	$scope.loadStuffAdd = function () {
		$scope.getData(1);
		$scope.page.pageNo = 1;
	};
	$scope.loadStuff = function () {
		$scope.getData($scope.page.pageNo);
	};


	//Set time to today
	$scope.setDay = function () {
		var data = new Date($.now());
		var d = new Date
			, dformat = [d.getFullYear(), d.getMonth() + 1, d.getDate()
               ].join('-') + ' ';
		$scope.datetime.fromDate = dformat + '00:00:00';
		$scope.datetime.toDate = dformat + '23:59:59';
		$scope.search.dateType = 1;
	};
	//Set time to this week
	$scope.setWeek = function () {
		d = new Date(new Date());
		var day = d.getDay()
			, diff = d.getDate() - day + (day == 0 ? -6 : 1);
		$scope.datetime.fromDate = (new Date(d.setDate(diff)), dformat = [d.getFullYear(), d.getMonth() + 1, d.getDate()
               ].join('-') + ' ') + '00:00:00';
		$scope.search.dateType = 2;
	};
	//Set time to this month
	$scope.setMonth = function () {
		d = new Date(new Date());
		var day = d.getDay()
			, diff = d.getDate() - day + (day == 0 ? -6 : 1);
		$scope.datetime.fromDate = (new Date(d.setDate(diff)), dformat = [d.getFullYear(), d.getMonth() + 1, 1
               ].join('-') + ' ') + '00:00:00';
		$scope.search.dateType = 3;
	};
	$scope.setMonth();

	//Back to last view
	$scope.backView = function (paramIndex) {
		if (paramIndex == 1) {
			$scope.Display = $scope.Displays['Employees'];
		} else if (paramIndex == 2) {
			$scope.Display = $scope.Displays['Receipts'];
		} else if (paramIndex == 3) {
			$scope.Display = $scope.Displays['Edit'];
		}
	};

	
	
	
		/********  CHINH SUA HOA DON ***************/
       $scope.Changeinfo = function(Id,QuantityOI,AmountOld,TotalOld,Price,Id1) {
		console.log( Id +" " + QuantityOI + " " + AmountOld+ " " +TotalOld);
		if(QuantityOI !="")
		{
		$scope.Receipt.Total=TotalOld-AmountOld + QuantityOI*Price;
		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'changeViewReceipt'
				, Total: $scope.Receipt.Total
				, Amount: QuantityOI*Price
				, Quantity: QuantityOI
				, Id: Id
				,ReId: Id1

			}))
			.success(function (data, status, headers, config) {
				console.log(data);
				$scope.getReceiptInfo();
			   }).error(function (data, status, headers, config) {
				console.log("Error Connection: Get products");
			});
	    }
				
	};
	$scope.trashReceipt = function (x) {
		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: "deleteReceipt"
				, ReceiptId: x.Id
			}))
			.success(function (data, status, headers, config) {
				console.log(data);
				$rootScope.showAlert("HÓA ĐƠN: " + x.Id + " Đã bị xóa");
//                                       	$scope.searchReceipt();
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Post order items");
			});
		$scope.getListReceipt($scope.page.pageNo,$scope.cacheObject);
	};	
	
	/********  GET PRODUCTS ***************/
	$scope.getProducts = function () {
		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'getProducts'
			}))
			.success(function (data, status, headers, config) {
				$scope.Products = data.Products;
				$scope.OldProducts = data.Products;
				console.log(data);
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get products");
			});
	};


	$scope.getProducts();

	$scope.cacheView = '';
	$scope.clickReceiptOnDesk = function (x) {
		$scope.Receipt.DeskNo = x.DeskNo;
		$scope.Receipt.Id = x.Id;
		//Copy for display
		$scope.Products = $scope.OldProducts;
		$scope.cacheView = $scope.Display;
		$scope.Display = $scope.Displays['OrderItems'];


		//Reset sub-total
		$scope.Receipt.SubTotal = 0;
		$scope.Receipt.Total = 0;

		//Create new list of order items
		$scope.OrderItems = [];
		//Check busy desks
		$scope.IsServing = true; //Start to serve

		/* Get Order Items */
		$scope.getReceiptInfo();

	};


	/*********** GET ORDER ITEMS ******************* */
	$scope.getReceiptInfo = function () {

		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'getReceiptInfo'
				, DeskNo: $scope.Receipt.DeskNo
				, ReceiptId: $scope.Receipt.Id
			}))
			.success(function (data, status, headers, config) {
				$scope.OrderItems = data.OrderItems;
				$scope.Receipt = data.Receipt;
				//					$scope.Receipt.CheckInTime = appLibs.ToVNTime($scope.Receipt.CheckInTime);
				//					$scope.Receipt.UpdatedTime = appLibs.ToVNTime($scope.Receipt.UpdatedTime);

				var numOfItems = 0;
				var subTotal = 0;
				for (var i = 0; i < $scope.OrderItems.length; i++) {
					//Update served time
					//						$scope.OrderItems[i].ServedTime = appLibs.ToVNTime($scope.OrderItems[i].ServedTime);
					numOfItems += parseInt($scope.OrderItems[i].Quantity);
					subTotal += parseInt($scope.OrderItems[i].Quantity) * parseInt($scope.OrderItems[i].Price);
				}

				$scope.Receipt.SubTotal = subTotal;
				$scope.Receipt.NumOfItems = numOfItems;
				$scope.Receipt.ExtraPaid = parseInt($scope.Receipt.NumOfItems) * parseInt($scope.Receipt.ExtraPaidPerItem);
				$scope.Receipt.Total = $scope.Receipt.SubTotal + $scope.Receipt.ExtraPaid;

				console.log(data);

				//Update quantity's OldProducts
				for (var i = 0; i < $scope.OldProducts.length; i++) {
					$scope.OldProducts[i]["Quantity"] = 0;
					for (var j = 0; j < $scope.OrderItems.length; j++) {
						if ($scope.OrderItems[j].ProductId == $scope.OldProducts[i].Id)
							$scope.OldProducts[i]["Quantity"] = $scope.OrderItems[j].Quantity;
					}
				}
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get order items");
			});
	};


	/********  onExtraPaidPerItemChanged ***************/
	$scope.onExtraPaidPerItemChanged = function () {
		console.log('onExtraPaidPerItemChanged');
		$scope.Receipt.ExtraPaid = parseInt($scope.Receipt.NumOfItems) * parseInt($scope.Receipt.ExtraPaidPerItem);
		$scope.Receipt.Total = parseInt($scope.Receipt.SubTotal) + parseInt($scope.Receipt.ExtraPaid);
	}

	/********  GET PRODUCTS ***************/
	$scope.getProducts = function () {
		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'getProducts'
			}))
			.success(function (data, status, headers, config) {
				$scope.Products = data.Products;
				$scope.OldProducts = data.Products;
				console.log(data);
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get products");
			});
	};

	/*********** CLICK ON THE PRODUCT *******************/
	$scope.clickOnProduct = function (index, isIncrease) {
		console.log($scope.Products[index].Name);

		//Incsease quantity on display
		if (isIncrease)
			$scope.Products[index].Quantity++;
		else {
			$scope.Products[index].Quantity--;
			if ($scope.Products[index].Quantity < 0)
				$scope.Products[index].Quantity = 0;
		}

		//Update on Old Product from display
		for (var i = 0; i < $scope.OldProducts.length; i++) {
			if ($scope.OldProducts[i].Id == $scope.Products[index].Id)
				$scope.OldProducts[i].Quantity = $scope.Products[index].Quantity;
		}

		//Calculate subtotal
		$scope.Receipt.SubTotal = 0;
		for (var i = 0; i < $scope.OldProducts.length; i++) {
			$scope.Receipt.SubTotal += $scope.OldProducts[i]["Price"] * $scope.OldProducts[i]["Quantity"];
		}
	};

	/********  ON SEARCH PRODUCT ***************/
	$scope.onSearchProducts = function () {
		var query = appLibs.ToUnsignedVnStr($scope.Search.Query);
		if ($scope.Search.Query.length == 0) {
			$scope.Products = $scope.OldProducts;
		} else {

			var product;
			var products = [];
			var index = -1;
			for (var i = 0; i < $scope.OldProducts.length; i++) {
				var name = appLibs.ToUnsignedVnStr($scope.OldProducts[i].Name);
				if (name.search(query) >= 0) {
					index++;
					product = $scope.OldProducts[i];
					product.IndexInArr = index;
					products.push(product);
				}
			}
			$scope.Products = products;
		}
	}

	/********  SHOW DISPLAY ***************/
	$scope.showDisplay = function (display) {
		$scope.Display = display;
	}

	/*********** SUBMIT CHANGED RECEIPT *******************/
	$scope.submitChangedReceipt = function () {

		$scope.OrderItems = [];
		for (var i = 0; i < $scope.OldProducts.length; i++) {
			if ($scope.OldProducts[i]["Quantity"] > 0) {
				var orderItem = {
					Id: 0, // New item
					ReceiptId: $scope.Receipt.Id, // Old receipt
					ProductId: $scope.OldProducts[i]["Id"]
					, Name: $scope.OldProducts[i]["Name"]
					, Price: $scope.OldProducts[i]["Price"]
					, Quantity: $scope.OldProducts[i]["Quantity"]
					, IsUpdated: 0
				, };
				// Push new item to list
				$scope.OrderItems.push(orderItem);

			}
		}

		//Submit
		if ($scope.OrderItems.length != 0) {
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "postUpdatedOrderItems"
					, ReceiptId: $scope.Receipt.Id
					, SubTotal: $scope.Receipt.SubTotal
					, NumOfItems: $scope.Receipt.NumOfItems
					, ExtraPaidPerItem: $scope.Receipt.ExtraPaidPerItem
					, ExtraPaid: $scope.Receipt.ExtraPaid
					, Total: $scope.Receipt.Total
					, Paid: $scope.Receipt.Paid
					, DueChange: $scope.Receipt.DueChange
					, Status: $scope.Receipt.DueChange > 0 ? 2 : 1
					, Note: $scope.Receipt.Note
					, EmployeeId: $scope.EmployeeId
					, OrderItems: $scope.OrderItems
				}))
				.success(function (data, status, headers, config) {
					console.log(data);
					if (Boolean(data.Status)) {

						//Clear Quantity
						for (var i = 0; i < $scope.OldProducts.length; i++)
							$scope.OldProducts[i]["Quantity"] = 100;
						$scope.Products = $scope.OldProducts;


						console.log("CHANGED RECEIPT!");
						console.log($scope.Display);
						//Show Desk
						if ($scope.Display == $scope.Displays['ProductsList']) {
							$scope.Display = $scope.Displays['OrderItems'];
							$http.post('api/home.api.php', JSON.stringify({
									Username: $rootScope.LoggedEmployee.Username
									, Password: $rootScope.LoggedEmployee.Password
									, Command: 'getReceiptInfo'
									, DeskNo: $scope.Receipt.DeskNo
									, ReceiptId: $scope.Receipt.Id
								}))
								.success(function (data, status, headers, config) {
									$scope.OrderItems = data.OrderItems;
									$scope.Receipt = data.Receipt;
									console.log("Phuc vu");
									var n = $scope.OrderItems.length;
									console.log(n);
									for (var i = 0; i < n; i++) {
										console.log($scope.OrderItems[i]);
										if ($scope.OrderItems[i].IsServed == 0) {
											$scope.clickOnServing($scope.OrderItems[i]);
											console.log("Phuc vu");
										}
									} 
									var numOfItems = 0;
									var subTotal = 0;
									for (var i = 0; i < $scope.OrderItems.length; i++) { 
										numOfItems += parseInt($scope.OrderItems[i].Quantity);
										subTotal += parseInt($scope.OrderItems[i].Quantity) * parseInt($scope.OrderItems[i].Price);
									}

									$scope.Receipt.SubTotal = subTotal;
									$scope.Receipt.NumOfItems = numOfItems;
									$scope.Receipt.ExtraPaid = parseInt($scope.Receipt.NumOfItems) * parseInt($scope.Receipt.ExtraPaidPerItem);
									$scope.Receipt.Total = $scope.Receipt.SubTotal + $scope.Receipt.ExtraPaid;

									console.log(data);

									//Update quantity's OldProducts
									for (var i = 0; i < $scope.OldProducts.length; i++) {
										$scope.OldProducts[i]["Quantity"] = 0;
										for (var j = 0; j < $scope.OrderItems.length; j++) {
											if ($scope.OrderItems[j].ProductId == $scope.OldProducts[i].Id)
												$scope.OldProducts[i]["Quantity"] = $scope.OrderItems[j].Quantity;
										}
									}
								}).error(function (data, status, headers, config) {
									console.log("Error Connection: Get order items");
								});
						} else {
							//Clear quantity when display desks
							for (var i = 0; i < $scope.OldProducts.length; i++)
								$scope.OldProducts[i].Quantity = 0;
							$scope.Display = $scope.Displays['OrderItems'];
						}

					}

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post order items");
				});


			//Notify server
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "addOrderItemWasServer"
					, ReceiptId: $scope.Receipt.Id
					, DeskNo: $scope.Receipt.DeskNo
				}))
				.success(function (data, status, headers, config) {
					console.log(data);
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Update statusComplete is false");
				});

		} else {
			// thuc hien ham trong nay ham giong nhu la ha da phuc vu
			console.log("bang khong " + $scope.OrderItems.length);
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "deleteReceipt"
					, ReceiptId: $scope.Receipt.Id, // Table: Receipts: Update
					SubTotal: $scope.Receipt.SubTotal, // Table: Receipts: New/Update
					Paid: $scope.Receipt.Paid
					, DueChange: $scope.Receipt.DueChange
					, Status: $scope.Receipt.DueChange > 0 ? 2 : 2, // 2. Completed, 1.Ordered
					Note: $scope.Receipt.Note
					, EmployeeId: $scope.EmployeeId, // Table: Receipts: New
					OrderItems: $scope.OrderItems
				}))
				.success(function (data, status, headers, config) {
					console.log(data);


					if (Boolean(data.Status)) {
						console.log("DELETED RECEIPT!");
						console.log($scope.Display);
						//Show Desk
						if ($scope.Display == $scope.Displays['Products']) {
							$scope.getReceiptInfo();
							$scope.showDisplay($scope.Displays['OrderItems']);
							console.log("a");
						} else {
							//Clear quantity when display desks
							for (var i = 0; i < $scope.OldProducts.length; i++)
								$scope.OldProducts[i].Quantity = 0;
 						}

					}

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post order items");
				});
		}
	};

	/*********** SUBMIT NEW RECEIPT *******************/
	$scope.submitNewReceipt = function () {

		//Gather new items
		$scope.OrderItems = [];
		for (var i = 0; i < $scope.OldProducts.length; i++) {
			if ($scope.OldProducts[i]["Quantity"] > 0) {
				var orderItem = {
					Id: 0, // New item
					ReceiptId: 0, // New receipt
					ProductId: $scope.OldProducts[i]["Id"]
					, Name: $scope.OldProducts[i]["Name"]
					, Quantity: $scope.OldProducts[i]["Quantity"]
					, Price: $scope.OldProducts[i]["Price"]
				, };
				$scope.OrderItems.push(orderItem);
			}
		}

		//Submit
		/* Post order items */
		if ($scope.OrderItems.length != 0) {
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "postNewOrderItems"
					, ReceiptId: 0
					, DeskNo: $scope.Receipt.DeskNo
					, SubTotal: $scope.Receipt.SubTotal
					, Total: $scope.Receipt.SubTotal
					, Paid: 0
					, DueChange: $scope.Receipt.SubTotal
					, ReceiptStatus: $scope.Receipt.DueChange > 0 ? 2 : 1, // 2. Completed, 1.Ordered
					EmployeeId: $rootScope.LoggedEmployee.Id
					, OrderItems: $scope.OrderItems
				}))
				.success(function (data, status, headers, config) {
					console.log(data);
					if (Boolean(data.Status)) {

						$scope.IsServing = true; //Start to serve
						$scope.showDisplay($scope.Displays["OrderItems"]);
						$scope.Receipt.Id = data.ReceiptId;
						$scope.getReceiptInfo();

						//Clear Quantity
						for (var i = 0; i < $scope.OldProducts.length; i++)
							$scope.OldProducts[i]["Quantity"] = 0;
						$scope.Products = $scope.OldProducts;

					}


				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post order items");
				});
		} else {
			$scope.showDisplay($scope.Displays["OrderItems"]);
		}
	}

	/*********** SAVE RECEIPT *******************/
	$scope.saveReceipt = function () {

			if ($scope.IsServing)
				$scope.submitChangedReceipt();
			else
				$scope.submitNewReceipt();


	}
		/*************SAVERECEIPTINFO*************/
	$scope.saveReceiptInfo = function () {
		if ($scope.OrderItems.length == 0) {
			console.log("bang khong " + $scope.OrderItems.length);
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "deleteReceipt"
					, ReceiptId: $scope.Receipt.Id
				}))
				.success(function (data, status, headers, config) {
					console.log(data);
					if (Boolean(data.Status)) {
//                    	$scope.searchReceipt();
					}
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post order items");
				});


		} else {
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "saveReceiptInfo"
					, ReceiptId: $scope.Receipt.Id
					, SubTotal: $scope.Receipt.SubTotal
					, NumOfItems: $scope.Receipt.NumOfItems
					, ExtraPaidPerItem: $scope.Receipt.ExtraPaidPerItem
					, ExtraPaid: $scope.Receipt.ExtraPaid
					, Total: $scope.Receipt.Total
					, Paid: $scope.Receipt.Paid
					, DueChange: $scope.Receipt.DueChange
					, Status: $scope.Receipt.DueChange > 0 ? 2 : 1
					, Note: $scope.Receipt.Note
					, EmployeeId: $scope.EmployeeId
				}))
				.success(function (data, status, headers, config) {
					console.log('DA LUU');
//                    $scope.searchReceipt();
					$rootScope.showAlert("Hóa đơn đã được cập nhật");
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Serve items");
				});
		}
		$scope.Display = $scope.cacheView;
		$scope.Display = $scope.Displays['Receipts'];
		$scope.getListReceipt($scope.page.pageNo,$scope.cacheObject);
		console.log('Tao la bao');
 	}


	/*********** SERVE ORDER ITEMS ******************* */
	$scope.serveOrderItems = function () {

		//Find not-served-yet order items
		var items = [];
		for (var i = 0; i < $scope.OrderItems.length; i++)
			if ($scope.OrderItems[i].IsServed == 0)
				items.push($scope.OrderItems[i]);

			//Send these items to server
		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'serveOrderItems'
				, DeskNo: $scope.Receipt.DeskNo
				, ReceiptId: $scope.Receipt.Id
				, OrderItems: items
			, }))
			.success(function (data, status, headers, config) {
				console.log(data);
				//Refesh order Items
				$scope.getReceiptInfo();
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Serve items");
			});
	}

	/** ********* PAY ******************* */
	$scope.pay = function () {
		console.log("pay");
		console.log($scope.OrderItems);
		if ($scope.OrderItems.length == 0)
			return;

		for (var i = 0; i < $scope.OrderItems.length; i++) {
			if ($scope.OrderItems[i].IsServed == 0) {
				$rootScope.showAlert(lang['ItemsNotServed']);
				return;
			}

		}
		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'pay'
				, ReceiptId: $scope.Receipt.Id
				, DeskNo: $scope.Receipt.DeskNo
				, CheckOutEmpId: $rootScope.LoggedEmployee.Id
			, }))
			.success(function (data, status, headers, config) {
 				console.log(data);
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: pay");
			});
	}

	/*********** CLICK ON SERVING ******************* */
	$scope.clickOnServing = function (item) {
		//alert(item.Id + ', ' + item.IsServed);
		var isServed = 1 - item.IsServed;
		//
		//			//Check Bartender &
		//			if ($rootScope.LoggedEmployee.AccessLevel != 5) {
		//				$rootScope.showAlert(lang['AccessDenied']);
		//				return;
		//			}

		//count number of served order items
		var counter = 0;
		for (var i = 0; i < $scope.OrderItems.length; i++) {
			if ($scope.OrderItems[i].IsServed == 1)
				counter++;
		}

		var isCompletedServing = 0;
		if (counter == $scope.OrderItems.length - 1 && isServed)
			isCompletedServing = 1;

		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'serveOrderItem'
				, OrderItemId: item.Id
				, IsServed: isServed
				, IsCompletedServing: isCompletedServing
				, ReceiptId: $scope.Receipt.Id
			, }))
			.success(function (data, status, headers, config) {

				console.log(data);
				$scope.getReceiptInfo();
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: pay");
			});
	}

	/*********** ADD ORDER ITEM ******************* */
	$scope.addOrderItem = function () {
		$scope.showDisplay($scope.Displays['ProductsList']);
		//Clear Quantity
		for (var i = 0; i < $scope.OldProducts.length; i++)
			$scope.OldProducts[i]["Quantity"] = 0;

		$scope.Products = $scope.OldProducts;
	}

	/*********** DELETE ORDER ITEM ******************* */
	$scope.deleteOrderItem = function (orderItemId, receiptId) {

		//Pha che & thu ngan ko duoc xoa
		if ($rootScope.LoggedEmployee.AccessLevel == 4 ||
			$rootScope.LoggedEmployee.AccessLevel == 5) {
			$rootScope.showAlert(lang['ItemServed']);
			return;
		}

		// Find item in list
		for (var i = 0; i < $scope.OrderItems.length; i++) {
			if ($scope.OrderItems[i].Id == orderItemId) {
				//Check Employee if it was served
				if ($scope.OrderItems[i].IsServed == 1 &&
					($rootScope.LoggedEmployee.AccessLevel == 3)) {
					$rootScope.showAlert(lang['ItemServed']);
					return;
				}
				break;
			}
		}

		// Calculate SubTotal
		$scope.Receipt.SubTotal -= $scope.OrderItems[i].Quantity * $scope.OrderItems[i].Price;
		$scope.Receipt.Total = $scope.Receipt.SubTotal + $scope.Receipt.ExtraPaid;

		/* Delete Order Item */
		$http.post('api/home.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, Command: 'deleteOrderItem'
				, OrderItemId: orderItemId
				, ReceiptId: receiptId
				, SubTotal: $scope.Receipt.SubTotal
				, NumOfCurrentOrderItems: $scope.OrderItems.length
			}))
			.success(function (data, status, headers, config) {

				console.log(data);
				// Refesh order Items
				$scope.getReceiptInfo();
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Delete Order Item");
			});
	}

}]);