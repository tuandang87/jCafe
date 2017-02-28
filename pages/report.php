<div ng-controller="reportController">
	<!-- HEADER -->
	<div class="row" ng-include="'templates/header.php'"></div>
	<div ng-include="'templates/alert.php'"></div>
	<div id="mySidenav" class="sidenav">
		<button type="button" class="btn btn-default btn-block" ng-class="isCurrentIndex(0) == 0 ? '' : 'btn-info'" ng-click="setCurrentIndex(0);">TỔNG QUAN</button>
		<button type="button" class="btn btn-default btn-block" ng-class="isCurrentIndex(1) == 0 ? '' : 'btn-info'" ng-click="setCurrentIndex(1);">THỐNG KÊ</button>
 		<br>
		<br>
		<strong style="margin-left:20px;"> 
		<?php 
          echo "  Hôm nay: " . date("d/m/Y") . "<br>";?></strong>
         <br><br>
         <div><span style="margin-left:10px; margin-top:10px;"> 
         Doanh thu: <b>{{DoanhThu}}</b>
	     </span></div>
         <div><span style="margin-left:10px; margin-top:10px;"> 
         Số HĐ: <b>{{HD}}</b>
	     </span></div>
	     <div><span style="margin-left:10px; margin-top:10px;"> 
         Phu Thu: <b>{{SLy}}</b>
	     </span></div>
         <div><span style="margin-left:10px; margin-top:10px;"> 
         L.Khách: <b>{{HD}}</b>
	     </span></div>
	</div>
	<div id="main">
		<div ng-include="'pages/report.overview.php'" ng-hide="!isCurrentIndex(0)"></div>
		<div ng-include="'pages/report.receipt.php'" ng-hide="!isCurrentIndex(1)"></div>
	</div>>
</div>