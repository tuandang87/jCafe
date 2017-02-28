//**************** HOME CONTROLLER ********************//

app.controller('homeController', ['$rootScope', '$scope', '$interval', '$http', '$cookies', '$timeout', '$location', 'appLibs', function ($rootScope, $scope, $interval, $http, $cookies, $timeout, $location, appLibs) {

		$scope.Displays = {
			Desks: 1
			, Products: 2
			, OrderItems: 3
		}; //Object: Key-Value
		$scope.Display = $scope.Displays['Desks'];
		$scope.IsServing = false;
		$scope.Product = {};
		$scope.Search = {};
		$scope.Receipt = {};
		
		console.log("HOME!");

		//Bão
		/*********** FIRST LOAD INIT DESKS ******************* */
		$scope.initDesksStatus = function () {
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: 'initDesksStatus'
				}))
				.success(function (data, status, headers, config) {
					$scope.Desks = data.Desks;
					$scope.getDesks();
					$scope.getProducts();
					console.log(data);
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Get desks");
				});
				$timeout(function () {
				}, 500);
		};
		$scope.Status = {};
		$scope.Status.client = 0;
		$scope.Status.forLoop = 0;// 360 loop is 45 minutes(loop every 5s)


		$timeout(function () {
				$scope.init();
			}, 500 // 0.1s to login
		);

		/************* INIT *********************** */
		$scope.init = function () {
			//Save first path, will return when login successful
			if ($rootScope.History.length == 0)
				$rootScope.History.push($location.absUrl());


			if ($rootScope.IsLoggedIn){
				if ($rootScope.LoggedEmployee.AccessLevel == 0) {
					window.location.href = "#/"; //Redirect to login
				} else {
					$scope.initDesksStatus();

					//Set timer to refesh desks
					$rootScope.refeshTimer = $interval(function () {
						$scope.getDesks();
					}, 5000); //5s to get desks
				}
			}
			else {
					window.location.href = "#/login";
			}
		}

		//Bão
		/*********** INCREASE STATUS OF DESKS ******************* */
		$scope.increaseStatusOfDesk = function (DeskNo) {
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: 'increaseStatusOfDesk'
					, DeskNo: DeskNo
				}))
				.success(function (data, status, headers, config) {
					console.log('***THỰC HIỆN THAO TÁC LÊN BÀN: ' + DeskNo);
//					$scope.getDesks();
//					console.log('***GET DESK VÌ CÓ THAO TÁC LÊN BÀN' + data);
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Get desks");
				});
		};


		/*********** GET DESKS ******************* */
		$scope.getDesks = function () {
			$scope.Status.forLoop++;
			if($scope.Status.forLoop == 360){
					window.location.href = "#/"; //Refresh when 30 minutes run 
			}
			$http.post('api/home.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: 'getDesks'
					, ClientSession: $scope.Status.client
				}))
				.success(function (data, status, headers, config) {
					$scope.Status.client = data.ServerSession;

					//Save data
					$scope.NumOfDesks = data.NumOfDesks;
					n = data.Desks.length;
					for (i = 0; i < n; i++) {
						if (data.Desks[i].IsUpdate == 1) {
							$scope.Desks[i] = data.Desks[i];
//							console.log('***UPDATE THONG TIN BAN SO: ' + data.Desks[i].No);
						}
					}
					//					$scope.Desks = data.Desks;
//					console.log(data);

					//Put busy desks into queue
					$scope.GuestQueue = [];
					$scope.ServingQueue = [];
					for (var i = 0; i < $scope.Desks.length; i++) {
						if ($scope.Desks[i].ReceiptId != 0) {

							//Order of guest
							$scope.GuestQueue.push($scope.Desks[i]);

							//Order of serving
							if ($scope.Desks[i].IsCompletedServing == 0) {


								//Mark Overtime desks
								var timeNow = new Date();
								var timeReceipt = new Date($scope.Desks[i].CheckInTime.replace(/\s+/g, 'T').concat('.000+07:00')).getTime();
								var timeDiff = Math.abs(timeReceipt - timeNow);

								var timeInMs = 1000 * 60 * 15; //15 minutes
								$scope.Desks[i].IsOverTime = 0;
								if ($scope.Desks[i].IsCompletedServing && timeDiff >= timeInMs) {
									$scope.Desks[i].IsOverTime = 1;
								}

								$scope.ServingQueue.push($scope.Desks[i]);
							}
						}
					}

					//Sort the waiting desks according to FIFO by Receipt ID
					$scope.GuestQueue.sort(function (a, b) {
						return parseInt(a.ReceiptId) > parseInt(b.ReceiptId)
					});
					$scope.ServingQueue.sort(function (a, b) {
						ad = new Date(a.CheckInTime.replace(/\s+/g, 'T').concat('.000+07:00')).getTime();
						bd = new Date(b.CheckInTime.replace(/\s+/g, 'T').concat('.000+07:00')).getTime();
						return (ad > bd);
					});
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Get desks");
				});
		}

		/*********** CLICK ON THE DESK *******************/
		$scope.clickOnDesk = function (IndexInArr) {
			$scope.Receipt.DeskNo = $scope.Desks[IndexInArr].No;
			$scope.Receipt.Id = $scope.Desks[IndexInArr].ReceiptId;
			//Check busy desks
			if (Boolean($scope.Desks[IndexInArr].IsBusy)) {
				$scope.IsServing = true; //Start to serve
				$scope.showDisplay($scope.Displays['OrderItems']); //Show  order items

				/* Get Order Items */
				$scope.getReceiptInfo();

			} else {
				$scope.IsServing = false; //Start to serve
				$scope.showDisplay($scope.Displays['Products']);

				//Clear quantity's products
				for (var i = 0; i < $scope.OldProducts.length; i++) {
					$scope.OldProducts[i]["Quantity"] = 0;
				}

				//Copy for display
				$scope.Products = $scope.OldProducts;


				//Reset sub-total
				$scope.Receipt.SubTotal = 0;
				$scope.Receipt.Total = 0;

				//Create new list of order items
				$scope.OrderItems = [];
			}
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
					$scope.Receipt.CheckInTime = appLibs.ToVNTime($scope.Receipt.CheckInTime);
					$scope.Receipt.UpdatedTime = appLibs.ToVNTime($scope.Receipt.UpdatedTime);

					var numOfItems = 0;
					var subTotal = 0;
					for (var i = 0; i < $scope.OrderItems.length; i++) {
						//Update served time
						$scope.OrderItems[i].ServedTime = appLibs.ToVNTime($scope.OrderItems[i].ServedTime);
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
		}

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
		}

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

			if (display == $scope.Displays['Desks'])
				$scope.getDesks();
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
							if ($scope.Display == $scope.Displays['Products']) {
								$scope.getReceiptInfo();
								$scope.showDisplay($scope.Displays['OrderItems']);
							} else {
								//Clear quantity when display desks
								for (var i = 0; i < $scope.OldProducts.length; i++)
									$scope.OldProducts[i].Quantity = 0;
								$scope.showDisplay($scope.Displays['Desks']);
							}

						}
						$scope.increaseStatusOfDesk($scope.Receipt.DeskNo);
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
								$scope.showDisplay($scope.Displays['Desks']);
							}

						}
						$scope.increaseStatusOfDesk($scope.Receipt.DeskNo);
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
							$scope.increaseStatusOfDesk($scope.Receipt.DeskNo);

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
							$scope.showDisplay($scope.Displays['Desks']);

						}
						$scope.increaseStatusOfDesk($scope.Receipt.DeskNo);
					}).error(function (data, status, headers, config) {
						console.log("Error Connection: Post order items");
					});


			} else {
//				console.log('XYX' + $scope.Receipt.Id + $scope.Receipt.ExtraPaidPerItem + $scope.Receipt.ExtraPaid + $scope.Receipt.SubTotal + $scope.Receipt.Total);
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
						$scope.increaseStatusOfDesk($scope.Receipt.DeskNo);
						console.log('DA LUU');
						$scope.showDisplay($scope.Displays['Desks']);
						// $scope.getReceiptInfo();
					}).error(function (data, status, headers, config) {
						console.log("Error Connection: Serve items");
					});
			}

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
					$scope.increaseStatusOfDesk($scope.Receipt.DeskNo);
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
					$scope.increaseStatusOfDesk($scope.Receipt.DeskNo);
					$scope.showDisplay($scope.Displays['Desks']);
					console.log(data);
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: pay");
				});
		}

		/*********** CLICK ON SERVING ******************* */
		$scope.clickOnServing = function (item) {
			//alert(item.Id + ', ' + item.IsServed);
			var isServed = 1 - item.IsServed;

			//Check Bartender &
			if ($rootScope.LoggedEmployee.AccessLevel != 5) {
				$rootScope.showAlert(lang['AccessDenied']);
				return;
			}

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
					$scope.increaseStatusOfDesk($scope.Receipt.DeskNo);
					console.log(data);
					$scope.getReceiptInfo();
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: pay");
				});
		}

		/*********** ADD ORDER ITEM ******************* */
		$scope.addOrderItem = function () {
			$scope.showDisplay($scope.Displays['Products']);
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
					$scope.increaseStatusOfDesk($scope.Receipt.DeskNo);
					console.log(data);
					// Refesh order Items
					$scope.getReceiptInfo();
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Delete Order Item");
				});
		}

		/*************** MOVE DESKS *********************/
		$scope.MoveDesk = function (id, toDeskNo, fromDeskNo) {
				console.log(" CHUYEN QUA BAN KO CO NGUOI NGOI");
				$http.post('api/home.api.php', JSON.stringify({
						Username: $rootScope.LoggedEmployee.Username
						, Password: $rootScope.LoggedEmployee.Password
						, Command: 'changedesk'
						, ReId: id
						, Desk: toDeskNo
					}))
					.success(function (data, status, headers, config) {
						$scope.increaseStatusOfDesk(fromDeskNo);
						$scope.getDesks();
						$scope.increaseStatusOfDesk(toDeskNo); 
						$scope.getDesks();
						$scope.showDisplay($scope.Displays['Desks']);
						console.log("DA XONG");
					}).error(function (data, status, headers, config) {
						console.log("Error Connection: MoveDesk");
					});

			}
			/*************** CONNECT DESKS *********************/
		$scope.ConnectDesks1 = function (FromReceiptId, fromDeskNo, toDeskNo) {
			console.log(fromDeskNo);
			console.log(toDeskNo);
                        if(fromDeskNo==toDeskNo) {
		             $rootScope.showAlert("thao tác không được thực hiện vì số bàn giống nhau!");
		             return;
			}
			//Find IndexInArr of fromDeskNo and toDeskNo
			IndexInArrFromDesk = -1;
			IndexInArrToDesk = -1;
			for (var i = 0; i < $scope.Desks.length; i++) {
				if ($scope.Desks[i].No == fromDeskNo) {
					IndexInArrFromDesk = i;
					break;
				}
			}

			for (var i = 0; i < $scope.Desks.length; i++) {
				if ($scope.Desks[i].No == toDeskNo) {
					IndexInArrToDesk = i;
					break;
				}
			}

			console.log(IndexInArrFromDesk);
			console.log(IndexInArrToDesk);

			if (IndexInArrFromDesk == -1 || IndexInArrToDesk == -1) {
				$rootScope.showAlert(lang['Error']);
				return;
			}

			if (Boolean($scope.Desks[IndexInArrToDesk].IsBusy) == false) {
//				console.log("Chuyen qua ban khong co nguoi");
				$scope.MoveDesk(FromReceiptId, toDeskNo, fromDeskNo);
			} else {
				console.log(" GOP QUA BAN CO NGUOI NGOI" + FromReceiptId + IndexInArrToDesk + $scope.Desks[IndexInArrToDesk].ReceiptId);
				$http.post('api/home.api.php', JSON.stringify({
						Username: $rootScope.LoggedEmployee.Username
						, Password: $rootScope.LoggedEmployee.Password
						, Command: 'ConnectDesks'
						, ReIdOld: FromReceiptId
						, ReIdNew: $scope.Desks[IndexInArrToDesk].ReceiptId
						, Desk: toDeskNo
						, FromDesk: fromDeskNo
						, Note: $scope.Desks[IndexInArrFromDesk].Note
						, FromComp: $scope.Desks[IndexInArrFromDesk].IsCompletedServing
						, ToComp: $scope.Desks[IndexInArrToDesk].IsCompletedServing
					}))
					.success(function (data, status, headers, config) {
						$scope.increaseStatusOfDesk(fromDeskNo);
						$scope.getDesks();
						$scope.increaseStatusOfDesk(toDeskNo); 
						$scope.getDesks();
						$scope.Display = $scope.Displays['Desks'];
						console.log("DA XONG");
					}).error(function (data, status, headers, config) {
						console.log("Error Connection: ConnectDesks1");
					});
			}
			$scope.increaseStatusOfDesk(fromDeskNo);
			$scope.getDesks();
			$scope.increaseStatusOfDesk(toDeskNo); 
			$scope.getDesks();
			//	$scope.getReceiptTo(Receiptidfrom,todesk);
		}
}]);