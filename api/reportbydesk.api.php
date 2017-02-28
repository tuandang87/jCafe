<?php

require 'config.php'; 

if ($IsLoggedIn) {
	switch ($request->Command) {
		case "getAllDeskByToday" :
			
			$pdo = Database::connect ();
			$sql = "SELECT Receipts.DeskNo, COUNT(Receipts.Id) AS NumOfReceipts, SUM(Receipts.Total) AS Amount FROM Receipts WHERE DATE(Receipts.CheckInTime) = DATE(NOW()) GROUP BY Receipts.DeskNo ORDER BY NumOfReceipts DESC";
			$data = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$employee = array (
						"DeskNo" => $row ['DeskNo'],
						"Amount" => $row ['Amount'],
						"NumOfReceipts" => $row ["NumOfReceipts"]
				);
				$data [] = $employee;
			}
			$data = array (
					"Status" => true,
					"Message" => "Get all desk by today successfully",
					"DesksByToday" => $data 
			);
			Database::disconnect ();
			break;
		
		case "getReceiptByDesks" :
			
			$pdo = Database::connect ();
			$sql = "SELECT Receipts.Id, Receipts.CheckInTime, Receipts.NumOfItems, Receipts.ExtraPaidPerItem, Receipts.ExtraPaid, Receipts.Total FROM Receipts WHERE DATE(Receipts.CheckInTime) = DATE(NOW()) AND DeskNo = '$request->DeskNo' ORDER BY Receipts.Id DESC";
			$data = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$employee = array (
						"Id" => $row ['Id'],
						"Date" => $row ['CheckInTime'],
						"Date" => $row ['CheckInTime'],
						"NumOfItems" => $row ['NumOfItems'],
						"ExtraPaidPerItem" => $row ['ExtraPaidPerItem'],
						"ExtraPaid" => $row ['ExtraPaid'],
						"Total" => $row ["Total"]
				);
				$data [] = $employee;
			}
			$data = array (
					"Status" => true,
					"Message" => "Get all receiptsByDesk successfully",
					"ReceiptByDesks" => $data 
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
				$employee = array (
						"Name" => $row ['Name'],
						"Quantity" => $row ['Quantity'],
						"Price" => $row ["Price"]
				);
				$data [] = $employee;
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