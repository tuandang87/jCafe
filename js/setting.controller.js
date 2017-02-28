app.controller('settingController', ['$scope', '$interval', '$http', '$cookies', '$timeout', '$rootScope', '$location'
		 , function ($scope, $interval, $http, $cookies, $timeout, $rootScope, $location) {

		console.log("SETTINGS!");

		$scope.Settings = {};

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

			if ($rootScope.LoggedEmployee.AccessLevel == 0 ||
				$rootScope.LoggedEmployee.AccessLevel == 3 ||
				$rootScope.LoggedEmployee.AccessLevel == 2) {
				window.location.href = "#/"; //Rediriect
			} else {
				$scope.getSettings();
			}
		}

		/********** GET SETTINGS ********************/
		$scope.getSettings = function () {
			/* Get Settings */
			$http.post('api/settings.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: 'getSettings'
				}))
				.success(function (data, status, headers, config) {
					console.log(data);
					$scope.Settings = data.Settings;

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Get products");
				});
		}

		/********** SAVE SETTINGS ********************/
		$scope.saveSettings = function () {

			$scope.Settings.SendReport = 0;
			if ($scope.Settings.BSendReport)
				$scope.Settings.SendReport = 1;

			/* Get Settings */
			$http.post('api/settings.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: 'saveSettings'
					, Settings: $scope.Settings
				}))
				.success(function (data, status, headers, config) {
					console.log(data);

					if (Boolean(data.Status))
						$rootScope.showAlert(lang["SuccessToSaveSettings"]);
					else
						$rootScope.showAlert(lang["FailToSaveSettings"]);

				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Get products");
				});
		}

		/********** GET SETTINGS ********************/
		$scope.sentReport = function () {
			/* Get Settings */
			$http.post('api/settings.api.php', JSON.stringify({
					Username: $rootScope.LoggedEmployee.Username
					, Password: $rootScope.LoggedEmployee.Password
					, Command: 'sentReport'
				}))
				.success(function (data, status, headers, config) {
					console.log(data);
				}).error(function (data, status, headers, config) {
					console.log("Error Connection: Sent report");
				});
		};

}]);