//**************** HOME CONTROLLER ********************//
app.controller('logoutController', [ '$rootScope', '$scope', '$interval',
		'$http', '$cookies', '$timeout',
		function($rootScope, $scope, $interval, $http, $cookies, $timeout) {

			console.log("LOGOUT!");
			$cookies.remove("Username");
			$cookies.remove("Password");
			$rootScope.IsLoggedIn = false;
			window.location.href = "#/login";
			//Stop get desks info while viewing other functions
			$interval.cancel($rootScope.refeshTimer);
			
		} ]);
