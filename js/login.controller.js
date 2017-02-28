//**************** HOME CONTROLLER ********************//
app.controller('loginController', [ '$rootScope', '$scope', '$interval',
		'$http', '$cookies', '$timeout',
		function($rootScope, $scope, $interval, $http, $cookies, $timeout) {

			console.log("LOGIN!");
			
			/** ***** LOGIN ************ */
			$scope.login = function(data) {
				
				$rootScope.LoggedEmployee.Username = data.Username;
				$rootScope.LoggedEmployee.Password = data.Password;
				
				/* Send login data */
				$http.post('api/login.api.php', JSON.stringify(
						{Username:$rootScope.LoggedEmployee.Username, 
							Password:$rootScope.LoggedEmployee.Password, 
							Command:'login',
				})).success(function(data, status, headers, config) {
					
			        console.log(data);
			        if (Boolean(data.Status)){
			        	
			        	// Save username & password into cookies
			        	var expireDate = new Date();
			    		expireDate.setDate(expireDate.getTime() + (12*60)*60000); // minutes
			    		$cookies.put('Username', $rootScope.LoggedEmployee.Username, {'expires': expireDate});
			    		$cookies.put('Password', data.LoggedEmployee.Password, {'expires': expireDate});
			        	
			        	// Save logged in employee id
			    		$rootScope.IsLoggedIn = true;
			    		$rootScope.LoggedEmployee = data.LoggedEmployee;
						
						
						if ($rootScope.History.length == 0)
							window.location.href = "#/home"; //default after login with no history
						else
							window.location.href = $rootScope.History[0];
			        }
			        else
			        	//$rootScope.showMessage(lang["FailToLogin"]);
			        	$rootScope.showAlert(lang["FailToLogin"]);
			        
			     }).error(function(data, status, headers, config) {console.log("Error Connection: Login");}); 
			}
			
			/**************** INITIALIZE APP HERE ****************************/
			$rootScope.LoggedEmployee.Username = $cookies.get("Username");
			$rootScope.LoggedEmployee.Password = $cookies.get("Password");
			if ( typeof $rootScope.LoggedEmployee.Username != "undefined" &&  
				typeof $rootScope.LoggedEmployee.Password != "undefined" && $rootScope.LoggedEmployee.Password.length > 6)
				$scope.login({Username:$rootScope.LoggedEmployee.Username, Password:$rootScope.LoggedEmployee.Password});
			
		} ]);
