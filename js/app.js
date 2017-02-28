/****** MAIN APPLICATION HERE ****** */

var app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'ngCookies', 'ngTouch', 'ui.bootstrap', 'ui-notification', 'ngScrollable', 'angularUtils.directives.dirPagination', 'angularjs-datetime-picker']);



/** Configure the Routes */
app.config(['$routeProvider', function ($routeProvider) {
	$routeProvider.when("/", {
		templateUrl: "pages/login.php"
	}).when("/login", {
		templateUrl: "pages/login.php"
	}).when("/logout", {
		templateUrl: "pages/logout.php"
	}).when("/home", {
		templateUrl: "pages/home.php"
	}).when("/report", {
		templateUrl: "pages/report.php"
	}).when("/reportbydesk", {
		templateUrl: "pages/reportbydesk.php"
	}).when("/reporting", {
		templateUrl: "pages/reporting.php"
	}).when("/products", {
		templateUrl: "pages/products.php"
	}).when("/categories", {
		templateUrl: "pages/categories.php"
	}).when("/employees", {
		templateUrl: "pages/employees.php"
	}).when("/settings", {
		templateUrl: "pages/settings.php"
	}).when("/test", {
		templateUrl: "pages/test.php"
	}).when("/404", {
		templateUrl: "pages/404.php"
	}).otherwise("/404", {
		templateUrl: "pages/404.php"
	});
}]).config(function (NotificationProvider) {
	NotificationProvider.setOptions({
		delay: 2500
		, startTop: 70
		, startRight: 10
		, verticalSpacing: 20
		, horizontalSpacing: 20
		, positionX: 'center'
		, positionY: 'top'
	});
});

/** ************** INITIALIZE APP HERE ******************** */
app.run(function ($rootScope, $cookies, $timeout, $http, Notification) {
	$rootScope.lang = lang;
	$rootScope.IsLoggedIn = false;
	$rootScope.LoggedEmployee = {
		AccessLevel: 0
	};
	$rootScope.History = [];

	/** ************** SHOW MODAL ************************ */
	$rootScope.showMessage = function (message) {
		$rootScope.Message = message;
		angular.element('#showModal').trigger('click');
	}

	/** ********* LOGOUT ****************** */
	$rootScope.logout = function () {
		$cookies.remove("Username");
		$cookies.remove("Password");
		$rootScope.IsLoggedIn = false;
		$rootScope.Message = "";
	}

	/** ********* addAlert ****************** */
	$rootScope.Alerts = [];
	$rootScope.showAlert = function (message) {
		//		$rootScope.Alerts.push({
		//			msg : message
		//		});
		Notification.error({
			message: message
		});
	};

	/** ********* closeAlert ****************** */
	$rootScope.closeAlert = function (index) {
		$rootScope.Alerts.splice(index, 1);
	};

	/** ************* PRINT DIV *********************** */
	$rootScope.printDiv = function (divName) {
		//Remove input that cannot remove by ng-if, ng-show
		var elem = document.getElementById('SelectedCustomer');
		var old = elem;
		elem.parentNode.removeChild(elem);

		var printContents = document.getElementById(divName).innerHTML;
		var popupWin = window.open('', '_blank');
		popupWin.document.open();

		var format = '<html><head>';
		format += '<link rel="stylesheet" href="libs/css/bootstrap.css">';
		format += '<link rel="stylesheet" href="css/main.css">';
		format += '</head><body onload="window.print()">';
		format += '<div style="margin: 10px;">';
		format += printContents;
		format += '</div>';
		format += '</body></html>';

		popupWin.document.write(format);
		popupWin.document.close();

		//Append customer name
		document.getElementById("CustomerInfo").appendChild(old);
	}

});



/** ************** SEVICES HERE ******************** */
app.service('appLibs', function () {

	/** ********************** */
	this.ToVnDateFormat = function (date) {
		var days = date.getDate();
		var months = date.getMonth() + 1;
		days = days < 10 ? '0' + days : days;
		months = months < 10 ? '0' + months : months;
		return days + "/" + months + "/" + date.getFullYear();
	}

	/** ********************** */
	this.ToVnDateFormatStr = function (strDate) {
		var date = new Date(strDate);
		return this.ToVnDateFormat(date);
	}

	/** ********************** */
	this.ToDbDateFormat = function (strDate) {
		// strDate: dd//mm/yyyy
		var dates = strDate.split('/');
		return dates[2] + "/" + dates[1] + "/" + dates[0];
	}

	/** ********************** */
	this.ToVNTime = function (strDate) {
		datetemp = new Date(strDate.replace(/\s+/g, 'T').concat('.000+07:00')).getTime();
		//console.log('Ngay thanh: ' + strDate);
		//console.log('Chuyen ngay thanh temp: ' + datetemp);
		var date = new Date(datetemp);
		//console.log('Chuyen ngay thanh: ' + date);
		var hours = date.getHours();
		var minutes = date.getMinutes();
		minutes = minutes < 10 ? '0' + minutes : minutes;
		hours = hours < 10 ? '0' + hours : hours;
		return hours + ':' + minutes;
	}

	this.ToUnsignedVnStr = function (str) {
		str = str.toLowerCase();
		str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
		str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
		str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
		str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
		str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
		str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
		str = str.replace(/đ/g, "d");
		return str;
	}
});

app.filter('ToVnTime', function () {
	return function (strDate) {
		var date = new Date(strDate);
		var hours = date.getHours();
		var minutes = date.getMinutes();
		minutes = minutes < 10 ? '0' + minutes : minutes;
		hours = hours < 10 ? '0' + hours : hours;
		return hours + ':' + minutes;
	};
});
app.filter('toEuros', function() {
  return function(input) {
    return Number(input).toLocaleString("es-ES", {minimumFractionDigits: 0});
  };
});

app.filter('ToVnDateTime', function () {
	return function (strDate) {
		var date = new Date(strDate);
		var hours = date.getHours();
		var minutes = date.getMinutes();
		minutes = minutes < 10 ? '0' + minutes : minutes;
		hours = hours < 10 ? '0' + hours : hours;
		var days = date.getDate();
		var months = date.getMonth() + 1;
		days = days < 10 ? '0' + days : days;
		months = months < 10 ? '0' + months : months;

		return days + "/" + months + "/" + date.getFullYear() + ", " + hours + ':' + minutes;
	};
});
