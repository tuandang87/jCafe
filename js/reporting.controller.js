app.controller('reportingController', 
		['$scope', '$interval', '$http', '$cookies', '$timeout', '$rootScope', '$location',
		 function ($scope, $interval, $http, $cookies, $timeout, $rootScope, $location) {
			
			console.log("REPORTING!");
			
			$scope.Displays = {
		    Main: 1
		   , ReportsOfEmployee: 2
		   , OrderItems:3
	};
            $scope.Display = $scope.Displays['Main'];
            /***SAVE VARIABLE TO RUN FUNCTION BACKVIEW*******/
            var fromday,today,fromhday,tohday;
            var fromold,toold,fromhold,tohold;
            var old;
            var checktoday=0;
            var fromh="00:00:00",toh="23:59:59";


			$timeout(function() {
				$scope.init();
			}, 100 // 0.1s to login
			);

			/************* INIT *********************** */
			$scope.init = function() 
			{
				//Stop get desks info while viewing other functions
				$interval.cancel($rootScope.refeshTimer);
				
				//Save first path, will return when login successful
				if ($rootScope.History.length == 0)
					$rootScope.History.push($location.absUrl());
				
				if ($rootScope.LoggedEmployee.AccessLevel == 0 || 
						$rootScope.LoggedEmployee.AccessLevel == 3) {
					window.location.href = "#/"; //Rediriect
				} else {
					$scope.getAllEmployeesReportsToday();
				}
			}
			/***GET ALL EMPLOYEES AND THEIR'S RECEIPT FROM DAY TO DAY****/
			$scope.getAllEmployeesReports = function (from,to,fromhm,tohm) 
		    {
                checktoday=0;
		    	if(from.length!=8||to.length!=8 ||from[from.length-3]!="/")
		    	{
		    		$rootScope.showAlert("Lỗi!vui lòng nhập đúng định dạng: vd:20/01/2016 ->20/01/16 \n hh:mm vd: 02:02");
		    		return;
		    	}
		    	if(fromhm==null) fromhm="00:00";
		    	if(tohm==null) tohm="23:59";
		    	fromh=fromhm+":00";
		    	toh=tohm+":59";
		    	fromhold=fromh;
		    	tohold=toh;
		    	console.log(fromh+ toh);
		    	$scope.Display = $scope.Displays['Main'];
				checktoday=0;
		    	fromold=from;
                toold=to;
		    	/* CONNECT INPUTTEXT TO FORMAT DATETIME*/
		    	fromday="2"+"0"+from[6]+from[7]+from[5]+from[3]+from[4]+from[2]+from[0]+from[1]+" ";
		    	today="2"+"0"+to[6]+to[7]+to[5]+to[3]+to[4]+to[2]+to[0]+to[1]+" ";
		    	fromhday=fromh[0]+fromh[1]+fromh[2]+fromh[3]+fromh[4]+fromh[5]+fromh[6]+fromh[7];
		    	tohday=toh[0]+toh[1]+toh[2]+toh[3]+toh[4]+toh[5]+toh[6]+toh[7];
		    	fromday=fromday+fromhday;
		    	today=today+tohday;
		    	console.log(fromday);
		    	console.log(today);
		    	$http.post('api/reporting.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, AccessLevel: $rootScope.LoggedEmployee.AccessLevel
					, Command: 'getAllEmployeesReports'
					, Fromday: fromday
					, Today: today
				})).success(function (data, status, headers, config) 
					{
				console.log(data);
				$scope.EmployeesReports = data.EmployeesReports;
				if($scope.EmployeesReports.length==0) $rootScope.showAlert("Nếu bạn tìm kiềm theo giờ vui lòng kiểm tra định sang hh:mm đã đúng chưa nếu đúng rồi nghĩa là không có hóa đơn nào trong t/đ đó");
				$scope.TotalReciepts = 0;
				$scope.TotalAmounts = 0;
				n = $scope.EmployeesReports.length;
				for (i = 0; i < n; i++) 
				{
					$scope.TotalReciepts += parseInt($scope.EmployeesReports[i].NumOfReceipts);
					$scope.TotalAmounts += parseInt($scope.EmployeesReports[i].Amount);
				}
				console.log(data);
				}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get AllEmployeesReports");
				});
			};
			/***GET ALL EMPLOYEES AND THEIR'S RECEIPT TO DAY****/
			$scope.getAllEmployeesReportsToday = function () 
		    {
		    	$scope.Fromday="";
		    	$scope.Today="";
		    	checktoday=1;
		    	$http.post('api/reporting.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, AccessLevel: $rootScope.LoggedEmployee.AccessLevel
					, Command: 'getAllEmployeesReportsToday'
				})).success(function (data, status, headers, config) 
					{
				console.log(data);
				$scope.EmployeesReports = data.EmployeesReports;
				$scope.TotalReciepts = 0;
				$scope.TotalAmounts = 0;
				n = $scope.EmployeesReports.length;
				for (i = 0; i < n; i++) 
				{
					$scope.TotalReciepts += parseInt($scope.EmployeesReports[i].NumOfReceipts);
					$scope.TotalAmounts += parseInt($scope.EmployeesReports[i].Amount);
				}
				console.log(data);
				}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get AllEmployeesReports");
				});
			};
	    /**********GET ALL RECEIPT OF AN EMPLOYEE********/
		$scope.getAllReportsOfEmployee = function (p,from,to) 
		    {
		        old=p;
		        $scope.Display = $scope.Displays['ReportsOfEmployee'];
		    	$http.post('api/reporting.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, AccessLevel: $rootScope.LoggedEmployee.AccessLevel
					, Command: 'getAllReportsOfEmployee'
					, CheckOutId: p.Id
					, Fromday: fromday
					, Today: today
					, CheckToday: checktoday
				})).success(function (data, status, headers, config) 
					{
				console.log(data);
				$scope.ReportsOfEmployee = data.ReportsOfEmployee;
				$scope.TotalReciepts = 0;
				$scope.TotalAmounts = 0;
				n = $scope.ReportsOfEmployee.length;
				$scope.TotalReciepts=n;
				for (i = 0; i < n; i++) 
				{
					$scope.TotalAmounts += parseInt($scope.ReportsOfEmployee[i].Total);
				}
				console.log(data);
				}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get ReportsOfEmployee");
				});
			};
			$scope.getOrderItemsByReceipt = function (receiptOnDesk) {
				$scope.ExtraPaidPerItem = receiptOnDesk.ExtraPaidPerItem;
				$scope.ExtraPaid = receiptOnDesk.ExtraPaid;
				$scope.AmountOfOrderItems=receiptOnDesk.Total;
				$scope.Display = $scope.Displays['OrderItems'];
				$http.post('api/reporting.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, ReceiptId: receiptOnDesk.Id
					, Command: 'getOrderItemsByReceipt'
				})).success(function (data, status, headers, config) {
					$scope.OrderItemsByReceipt = data.OrderItemsByReceipt;
					$scope.NumOfReceipt=0;
					for(var i=0;i<$scope.OrderItemsByReceipt.length;i++)
					{
						$scope.NumOfReceipt+=parseInt($scope.OrderItemsByReceipt[i].Quantity);
					}
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Get ReceiptByDesks");
				});
	};

		$scope.backView = function (paramIndex) 
	{
		if(paramIndex==2)
			{$scope.Display = $scope.Displays['Main'];
		    if(checktoday==1)
		    	$scope.getAllEmployeesReportsToday();
		    else
		    $scope.getAllEmployeesReports(fromold,toold,fromhold,tohold);
		}
		 else if(paramIndex==3)
		 {
		    $scope.Display = $scope.Displays['ReportsOfEmployee'];
		    $scope.getAllReportsOfEmployee(old,fromold,toold);
		 }	
	};
}]);