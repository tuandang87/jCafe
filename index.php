<?php require_once 'api/config.php'; ?>
	<!DOCTYPE html>

	<html class="no-js">

	<head>

		<!-- Meta-Information -->
		<title>
			<?php echo $SHOP_NAME; ?>
		</title>
		<meta charset="utf-8">
		<meta name="description" content="Jerry Software Solution Inc.">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSS -->
		<link rel="stylesheet" href="libs/css/bootstrap.min.css">

		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/home.css">
		<link rel="stylesheet" href="css/employee.css">
		<link rel="stylesheet" href="css/reportbydesk.css">
		<link rel="stylesheet" href="css/product.css">
		<link rel="stylesheet" href="css/report.css">

		<!--<link rel="stylesheet" href="libs/css/angular-notifier.min.css">-->
		<link rel="stylesheet" href="libs/css/angular-ui-notification.min.css">
		<link rel="stylesheet" href="libs/css/ng-scrollable.min.css">
		<link rel="stylesheet" href="libs/css/angularjs-datetime-picker.css">
                

		<!-- Javascript -->
		<script src="libs/js/jquery.min.js"></script>
		<!-- Support angular selector, modal -->
		<script src="libs/js/angular.js"></script>
		<script src="libs/js/angular-animate.js"></script>
		<script src="libs/js/angular-touch.js"></script>
		<script src="libs/js/angular-route.js"></script>
		<script src="libs/js/angular-cookies.js"></script>
		<script src="libs/js/bootstrap.js"></script>
		<script src="libs/js/ui-bootstrap-tpls-1.3.3.js"></script>
		<!--<script src="libs/js/angular-notifier.min.js"></script>-->
		<script src="libs/js/angular-ui-notification.min.js"></script>
		<script src="libs/js/ng-scrollable.min.js"></script>
		<script src="libs/js/dirPagination.js"></script>
		<script type="text/javascript" src="libs/js/angularjs-datetime-picker.min.js"></script>
                <script src="libs/js/Chart.min.js"></script>


		<!-- Our Website Javascripts -->
		<script src="js/lang.js"></script>
		<script src="js/app.js"></script>
		<script src="js/login.controller.js"></script>
		<script src="js/logout.controller.js"></script>
		<script src="js/home.controller.js"></script>
		<script src="js/product.controller.js"></script>
		<script src="js/category.controller.js"></script>
		<script src="js/employee.controller.js"></script>
		<script src="js/reportbydesk.controller.js"></script>
		<script src="js/reporting.controller.js"></script>
		<script src="js/report.controller.js"></script>
		<script src="js/setting.controller.js"></script>
		<script src="js/404.controller.js"></script>
		<script src="js/test.controller.js"></script>
                <script src="js/report.overview.js"></script>

	</head>

	<body ng-app="myApp">
		<!-- Our Website Content Goes Here -->
		<!-- 	<div ng-include='"templates/header.php"'></div> -->
		<div ng-view></div>
		<!-- 	<div ng-include='"templates/footer.php"'></div> -->
	</body>

	</html>