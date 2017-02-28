app.controller('employeeController', ['$scope', '$interval', '$http', '$cookies', '$timeout', '$rootScope', '$location'
   , function ($scope, $interval, $http, $cookies, $timeout, $rootScope, $location) {

		$scope.Displays = {
			List: 1
			, Edit: 2
		}; //Object: Key-Value

		$scope.EditButton = false;
		$scope.DeleteButton = false;
		$scope.AddButton = true;
		if($rootScope.LoggedEmployee.AccessLevel == 3){
			$scope.AddButton = false;
		}
		$scope.Display = $scope.Displays['List'];
		$scope.Employee = {};
		$scope.Search = {};

		console.log("EMPLOYEES!");


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

      if ($rootScope.IsLoggedIn){
				if ($rootScope.LoggedEmployee.AccessLevel >= 3 && $rootScope.LoggedEmployee.AccessLevel <=5){
					window.location.href = "#/"; //Rediriect
					$rootScope.showAlert(lang['AccessDenied']);
				} else {
						$scope.getEmployees();
				}
			}else {
					window.location.href = "#/login";
			}

		}

		/******** GET EMPLOYEES ***************/
		$scope.getEmployees = function () {

			$http.post('api/employees.api.php', JSON.stringify({
				Username: $rootScope.LoggedEmployee.Username
				, Password: $rootScope.LoggedEmployee.Password
				, AccessLevel: $rootScope.LoggedEmployee.AccessLevel
				, Command: 'getEmployees'
			})).success(function (data, status, headers, config) {
				$scope.Employees = data.Employees;
				$scope.OldEmployees = data.Employees;
				console.log(data);
			}).error(function (data, status, headers, config) {
				console.log("Error Connection: Get Employees");
			});
		}

		/******** SEARCH EMPLOYEE ************** */
		$scope.onSearchEmployees = function () {
			//console.log($scope.Se	arch.Query);
			var query = $scope.Search.Query.toLowerCase();
			if ($scope.Search.Query.length == 0) {
				$scope.Employees = $scope.OldEmployees;
			} else {

				var employee;
				var employees = [];
				var index = -1;
				for (var i = 0; i < $scope.OldEmployees.length; i++) {
					if ($scope.OldEmployees[i].Name.toLowerCase().search(query) >= 0) {
						index++;
						employee = $scope.OldEmployees[i];
						employee.IndexInArr = index;
						employees.push(employee);
					}
				}
				$scope.Employees = employees;
			}

		}

		/******** NEW EMPLOYEE ************** */
		$scope.newEmployee = function () {
			$scope.IsNewEmployee = true;
			$scope.EditButton = true;
			$scope.DeleteButton = false;
			$scope.Display = $scope.Displays['Edit'];
			$scope.IsNewUnit = true;
			$scope.Employee = {
				Id: 0,
				RegionNo: 1,
				Image: "noavatar.png",
				Birthday: "1990-01-01",
			};
		}

		/*********** CLICK ON THE EMPLOYEE *******************/
		$scope.clickOnEmployee = function (employee) {
			$scope.Employee = employee;
			$scope.IsNewEmployee = false;
			$scope.Display = $scope.Displays['Edit'];
			if ($rootScope.LoggedEmployee.AccessLevel == 1) {
				$scope.EditButton = true;
				$scope.DeleteButton = true;
			} else if ($rootScope.LoggedEmployee.AccessLevel == 2) {
				$scope.EditButton = employee.Username == $rootScope.LoggedEmployee.Username ? true : false;
				$scope.DeleteButton = employee.Username == $rootScope.LoggedEmployee.Username ? true : false;
			} else if ($rootScope.LoggedEmployee.AccessLevel == 3) {
				$scope.EditButton = employee.Username == $rootScope.LoggedEmployee.Username ? true : false;
				$scope.DeleteButton = employee.Username == $rootScope.LoggedEmployee.Username ? true : false;
			}
		};
		/******** EDIT EMPLOYEE ************** */
		$scope.editProduct = function (IndexInArr) {
			$scope.Display = $scope.Displays['Edit'];
			$scope.IsNewProduct = false;
			$scope.Employee = $scope.Employees[IndexInArr];
			console.log($scope.Employee);
		}

		/******** SAVE EMPLOYEE ************** */
		$scope.saveEmployee = function () {
			var command = "newEmployee";

			if ($scope.IsNewEmployee == false) {
				command = "editEmployee";
			}

			//Send through HTTP
			$http.post('api/employees.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username,
					Password: $rootScope.LoggedEmployee.Password,
					Command: command,
					EmployeeId: $scope.Employee.Id,
					EUsername: $scope.Employee.Username,
					EPassword: $scope.Employee.Password,
					AccessLevel: $scope.Employee.AccessLevel,
          RegionNo: $scope.Employee.RegionNo,
					FirstName: $scope.Employee.FirstName,
					LastName: $scope.Employee.LastName,
					Image: $scope.Employee.Image,
					Email: $scope.Employee.Email,
					Phone: $scope.Employee.Phone,
					Birthday: $scope.Employee.Birthday,
					Address: $scope.Employee.Address,

				}))
				.success(function (data, status, headers, config) {
					console.log(data);

					if (Boolean(data.Status)) {
						$scope.getEmployees();
						$scope.showDisplay($scope.Displays['List']);
					} else
						$rootScope.showAlert(data.Message);

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post employee");
				});
		}

		/******** DELETE EMPLOYEE ************** */
		$scope.deleteEmployee = function () {
			//Send through HTTP
			$http.post('api/employees.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: "deleteEmployee"
					, Id: $scope.Employee.Id
				, }))
				.success(function (data, status, headers, config) {
					console.log(data);

					$scope.getEmployees();
					$scope.showDisplay($scope.Displays['List']);

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Post employee");
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
			fd.append('dir', 'user/');
			fd.append('oldImage', $scope.Employee.Image);
			fd.append('nameImage', $scope.Employee.Username);
			$.ajax({
				url: "upload.php"
				, type: "POST"
				, data: fd
				, processData: false
				, contentType: false
				, success: function (response) {
					data = JSON.parse(response);
					console.log(data);
					$scope.Employee.Image = data.FileName;
					$rootScope.showAlert(data.Message);
					$scope.$digest();
				}
				, error: function (jqXHR, textStatus, errorMessage) {
					console.log(errorMessage); // Optional
				}
			});
		}
}]).directive('backImg', function () {
	return function (scope, element, attrs) {
		var url = attrs.backImg;
		element.css({
			'background-image': 'url(' + url + ')'
			, 'background-size': 'cover'
			, 'background-repeat': 'no-repeat'
			, 'background-position': 'center center'
		});
	};
});
