<script type="text/ng-template" id="alert.html">
    <div class="alert" 
		style="background-color:red; color:white; text-align:center; font-size: 18px;" 
		role="alert">
     <div ng-transclude></div>
    </div>
</script>

<uib-alert  ng-repeat="alert in Alerts" type="danger" 
	close="closeAlert($index)"
	style="background-color:white; color: red; border: 1px solid lightgrey;
	margin-left: 20%; margin-right:20%;
	text-align:center; font-size: 18px;" >{{alert.msg}}
</uib-alert>