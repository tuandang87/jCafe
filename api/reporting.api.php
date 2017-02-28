<?php

require 'config.php'; 

if ($IsLoggedIn) {
	switch ($request->Command) {
		case "getAllEmployeesReports" :
			$pdo = Database::connect ();
			$x=$request->Fromday;
			$y=$request->Today;
			if($x!=$y)
			{$sql = "SELECT Receipts.CheckOutEmpId,Employees.Username, COUNT(Receipts.Id) AS NumOfReceipts, SUM(Receipts.Total) AS Amount FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Receipts.CheckInTime BETWEEN STR_TO_DATE('$x','%Y/%m/%d %T') AND STR_TO_DATE('$y','%Y/%m/%d %T')  GROUP BY Receipts.CheckOutEmpId ORDER BY NumOfReceipts DESC";}
		    else{
		     $sql="SELECT Receipts.CheckOutEmpId,Employees.Username, COUNT(Receipts.Id) AS NumOfReceipts, SUM(Receipts.Total) AS Amount FROM receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Receipts.CheckInTime=STR_TO_DATE($x,'%Y/%m/%d %T') ORDER BY NumOfReceipts DESC";
		    }
			$data = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$employee = array (
						"EmpName" => $row ['Username'],
						"Amount" => $row ['Amount'],
						"NumOfReceipts" => $row ['NumOfReceipts'],
						"Id"=>$row['CheckOutEmpId']
				);
				$data [] = $employee;
			}
			$data = array (
					"Status" => true,
					"Message" => "Get all desk by today successfully",
					"EmployeesReports" => $data 
			);
			Database::disconnect ();
			break;
		case "getAllEmployeesReportsToday" :
			$pdo = Database::connect ();
			$sql="SELECT Receipts.CheckOutEmpId,Employees.Username, COUNT(Receipts.Id) AS NumOfReceipts, SUM(Receipts.Total) AS Amount FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE DATE(Receipts.CheckInTime) = DATE(NOW()) GROUP BY Receipts.CheckOutEmpId ORDER BY NumOfReceipts DESC";
			$data = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$employee = array (
						"EmpName" => $row ['Username'],
						"Amount" => $row ['Amount'],
						"NumOfReceipts" => $row ['NumOfReceipts'],
						"Id"=>$row['CheckOutEmpId']
				);
				$data [] = $employee;
			}
			$data = array (
					"Status" => true,
					"Message" => "Get all desk by today successfully",
					"EmployeesReports" => $data 
			);
			Database::disconnect ();
			break;
		
		case "getAllReportsOfEmployee" :
			$pdo = Database::connect ();
			$x=$request->Fromday;
			$y=$request->Today;
			$z=$request->CheckToday;
			if($z==1)
			{
			  $sql = "SELECT Receipts.Id,Receipts.DeskNo,Receipts.Total, Receipts.CheckInTime, Receipts.UpdatedTime, Receipts.CheckOutEmpId,Receipts.ExtraPaidPerItem,Receipts.ExtraPaid FROM Receipts WHERE DATE(Receipts.CheckInTime) = DATE(NOW()) AND Receipts.CheckOutEmpId=$request->CheckOutId ORDER BY Receipts.CheckOutEmpId";	
			}

		    else if($x!=$y)
		    {
			$sql = "SELECT Receipts.Id,Receipts.DeskNo,Receipts.Total, Receipts.CheckInTime,Receipts.UpdatedTime, Receipts.CheckOutEmpId,Receipts.ExtraPaidPerItem,Receipts.ExtraPaid FROM Receipts WHERE Receipts.CheckInTime BETWEEN STR_TO_DATE('$x','%Y/%m/%d %T') AND STR_TO_DATE('$y','%Y/%m/%d %T') AND Receipts.CheckOutEmpId=$request->CheckOutId ORDER BY Receipts.CheckOutEmpId";
		    }
		    else
		    {
		    	$sql = "SELECT Receipts.Id,Receipts.DeskNo,Receipts.Total, Receipts.CheckInTime,Receipts.UpdatedTime, Receipts.CheckOutEmpId,Receipts.ExtraPaidPerItem,Receipts.ExtraPaid FROM Receipts WHERE Receipts.CheckInTime=STR_TO_DATE($x,'%Y/%m/%d %T') AND Receipts.CheckOutEmpId=$request->CheckOutId ORDER BY Receipts.CheckOutEmpId";
		    }
			$data = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$report = array (
						"Id" => $row ['Id'],
						"DeskNo" => $row ['DeskNo'],
						"Total" => $row ['Total'],
						"Date"=>$row['UpdatedTime'],
						"DateIn"=>$row['CheckInTime'],
						"ExtraPaidPerItem"=> $row['ExtraPaidPerItem'],
						"ExtraPaid"=>$row['ExtraPaid']
				);
				$data [] = $report;
			}
			$data = array (
					"Status" => true,
					"Message" => "Get all desk by today successfully",
					"ReportsOfEmployee" => $data 
			);
			Database::disconnect ();
			break;
        
		case "getOrderItemsByReceipt":
			
			$pdo = Database::connect ();
			$sql = "SELECT Products.Name, OrderItems.Quantity, Products.Price FROM OrderItems INNER JOIN Products ON OrderItems.ProductId = Products.Id WHERE ReceiptId = '$request->ReceiptId'";
			$data = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$item = array (
						"Name" => $row ['Name'],
						"Quantity" => $row ['Quantity'],
						"Price" => $row ["Price"],
						"NumOfReceipts"=>$row['NumOfReceipts']
				);
				$data [] = $item;
			}
			$data = array (
					"Status" => true,
					"Message" => "Get all orderItemsByReceipt successfully",
					"OrderItemsByReceipt" => $data 
			);
			Database::disconnect ();
			break;
		default :
			$data = array (
					"Status" => false,
					"Message" => "Wrong request" 
			);
			break;
	}
} else {
	$data = array (
			"Status" => false,
			"Message" => "Wrong username or password",
			"Request" => $request
	);
}

/* Try code here */

echo json_encode ( $data );
?>