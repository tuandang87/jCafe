<table align="center">
   <tr></tr>
   <tr class="row">
 		<td class="row-xs-2" >
 			<div style="margin-left:0px;">Từ ngày:</div>
 		</td>
 		<td class="row-xs-2">
 		    <input type="text" style="border-radius:3px;border:color=blue;" placeholder="dd/mm/yyyy" ng-model="Fromday">
 		</td>
 		<td class="row-xs-2">
 			<div >Đến ngày</div>
 		</td>
 		<td class="row-xs-2">
 		    <input  type="text" style="border-radius:3px;border:color=blue;" placeholder="dd/mm/yyyy" ng-model="Today">
 		</td>
 	</tr>
 	<tr class="row">
 		<td class="row-xs-2">
 			<div style="margin-left:0px;">Từ giờ:</div>
 		</td>
 		<td class="row-xs-2">
 			<input type="text" placeholder="hh:mm" style="border-radius:3px;border:color=blue;" ng-model="Fromhm">
 		</td>
 		<td class="row-xs-2">
 		    <div>Đến giờ</div>
 		</td>
 		<td class="row-xs-2">
 		    <input type="text" placeholder="hh:mm" style="border-radius:3px;border:color=blue;" ng-model="Tohm">
 		</td>
 	</tr>
 	<tr class="row">
 	    <td class="row-xs-21"></td>
 	    <td class="row-xs-2">
 	        <input type="button" value="Hôm nay" class="btn btn-primary" ng-click="getAllEmployeesReportsToday()">
   	    </td>
   	    <td class="row-xs-1"></td>
	 	<td class="row-xs-2">
 			<input  type="button"  value="OK" class="btn btn-primary" ng-click="getAllEmployeesReports(Fromday,Today,Fromhm,Tohm)"/>
 		</td>
 	</tr>
 	<tr class="row"></tr>
 </table>
