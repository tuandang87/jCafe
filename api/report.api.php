<?php

require 'config.php'; 

$Command = $request->Command;
$Type =  $request->Type;
$KeyWord =  $request->KeyWord;
$Zone_ID =  $request->Zone_ID;
$FromDate =  $request->FromDate;
$ToDate =  $request->ToDate;
$PageNo =  $request->PageNo;
$ItemsPerPage =  $request->ItemsPerPage;
$a = (intval($PageNo - 1) * intval($ItemsPerPage));
$b = $ItemsPerPage;

if ($IsLoggedIn) {
	switch ($request->Command) { 
		case "getinfo":
		$pdo = Database::connect();
		$sql="SELECT Receipts.CheckInTime, COUNT(Receipts.Id) AS NumOfReceipts, SUM(Receipts.Total) AS Amount, SUM(ExtraPaid) AS Quanti FROM Receipts WHERE DATE(Receipts.CheckInTime) = DATE(NOW()) AND Receipts.Status=2 ";
			$data = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$dt = array (
						"NumOfReceipts" => $row ['NumOfReceipts'],
						"Amount" => $row ['Amount'],
						"Quantity" => $row ['Quanti'],
				);
				$data [] = $dt;
			}
        $sql="SELECT SUM(Quantity) AS Num FROM OrderItems INNER JOIN Receipts ON OrderItems.ReceiptId=Receipts.Id WHERE DATE(Receipts.CheckInTime) = DATE(NOW()) AND Receipts.Status=2 ";
        $temp=0;
        $index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$temp+=$row['Num'];
			}
		
			$data = array (
					"Status" => true,
					"Message" => "Get all desk by today successfully",
					"Info" => $data,
					"SoLy"=>$temp, 
			);
			Database::disconnect ();
			break;
		case "getdata":
		$pdo = Database::connect ();
		$sql="SELECT Date(CheckInTime) as Day, COUNT(Receipts.Id) as NumOfItems, SUM(ExtraPaid) as sumExtra, Sum(Total) as sumTotal FROM Receipts WHERE Receipts.Status=2 GROUP BY Date(CheckInTime)  ORDER BY CheckInTime DESC LIMIT 0,7";
			$data = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$dt = array (
						"Day" => $row ['Day'],
						"NumHD" => $row ['NumOfItems'],
						"NumTotal"=>$row['sumTotal'],
						"NumLy"=>$row['sumExtra']/1000,
				);
				$data [] = $dt;
			}
		$sql="SELECT SUM(Quantity) AS SLLY FROM OrderItems INNER JOIN Receipts ON Receipts.Id=OrderItems.ReceiptId WHERE Receipts.Status=2 GROUP BY Date(CheckInTime)  ORDER BY CheckInTime DESC LIMIT 0,7";
			$data1 = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$dt1 = array (
						"SLLy" => $row ['SLLY'],
				);
				$data1 [] = $dt1;
			}
		
			$data = array (
					"Status" => true,
					"Message" => "Get all desk by today successfully",
					"Reports" => $data,
					"SLL" =>$data1,
			);
			Database::disconnect ();
			break;
		case "getByEmployee":
			$pdo = Database::connect ();
			$count = 0;
			$sreceipt = 0;
			$ssubtotal = 0;
			$sextrapaid = 0;
			$stotal = 0;
			$totalInfo = array ();
			$data = array ();
			if($KeyWord == ''){
				if($Zone_ID == 0){
					$case = "Chọn tất cả các nhân viên trong time";
					/*Count how many record avaible to show*/
					$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Receipts.CheckInTime BETWEEN :f AND :t GROUP By Receipts.CheckOutEmpId";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
 					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$count++;
					}
					$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Employees.Username FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Receipts.CheckInTime BETWEEN :f AND :t";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
 					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$sreceipt += intval($row ['CountReceipt']);
						$ssubtotal += intval($row ['SubTotals']);
						$sextrapaid += intval($row ['ExtraPaids']);
						$stotal += intval($row ['Totals']);
 					}
					$totalInfo = array(
						"sreceipt" => $sreceipt,
						"ssubtotal" => $ssubtotal,
						"sextrapaid" => $sextrapaid,
						"stotal" => $stotal,
					);
					/*Select receipt group by employee*/
					$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Employees.Username FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Receipts.CheckInTime BETWEEN :f AND :t GROUP By Receipts.CheckOutEmpId ORDER BY CountReceipt DESC LIMIT $a,$b";
					$stmt = $pdo->prepare($sqlSelect);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
 					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$item = array (
								"Username" => $row ['Username'], 
								"CountReceipt" => $row ['CountReceipt'],
								"SubTotals" => $row ['SubTotals'],
								"ExtraPaids" => $row ['ExtraPaids'],
								"Totals" => $row ['Totals'],
						);
						$data [] = $item;
					}
				} else {
					$case = "Chọn tất cả các nhân viên trong time và zone";
					/*Count how many record avaible to show*/
					$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Employees.RegionNo = :a AND Receipts.CheckInTime BETWEEN :f AND :t GROUP By Receipts.CheckOutEmpId";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':a', $Zone_ID, PDO::PARAM_STR);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$count++;
					}
					$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Employees.Username FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Employees.RegionNo = :a AND Receipts.CheckInTime BETWEEN :f AND :t";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':a', $Zone_ID, PDO::PARAM_STR);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
 					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$sreceipt += intval($row ['CountReceipt']);
						$ssubtotal += intval($row ['SubTotals']);
						$sextrapaid += intval($row ['ExtraPaids']);
						$stotal += intval($row ['Totals']);
 					}
					$totalInfo = array(
						"sreceipt" => $sreceipt,
						"ssubtotal" => $ssubtotal,
						"sextrapaid" => $sextrapaid,
						"stotal" => $stotal,
					);
					/*Select receipt group by employee*/
					$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Employees.Username FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Employees.RegionNo = :a AND Receipts.CheckInTime BETWEEN :f AND :t GROUP By Receipts.CheckOutEmpId ORDER BY CountReceipt DESC LIMIT $a,$b";
					$stmt = $pdo->prepare($sqlSelect);
					$stmt->bindParam(':a', $Zone_ID, PDO::PARAM_STR);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$item = array (
								"Username" => $row ['Username'], 
								"CountReceipt" => $row ['CountReceipt'],
								"SubTotals" => $row ['SubTotals'],
								"ExtraPaids" => $row ['ExtraPaids'],
								"Totals" => $row ['Totals'],
						);
						$data [] = $item;
					}
				}
				
			}else{
				$case = "Chọn các nhân viên trong time theo tên";
				/*Count how many record avaible to show*/
				$stmt = $pdo->prepare("SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Employees.Username LIKE :a AND Receipts.CheckInTime BETWEEN :f AND :t GROUP By Receipts.CheckOutEmpId");
				$KeyWord = '%'.$KeyWord.'%';
				$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $rq){
						$count++;
				}
				$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Employees.Username FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Employees.Username LIKE :a AND Receipts.CheckInTime BETWEEN :f AND :t";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$sreceipt += intval($row ['CountReceipt']);
					$ssubtotal += intval($row ['SubTotals']);
					$sextrapaid += intval($row ['ExtraPaids']);
					$stotal += intval($row ['Totals']);
				}
				$totalInfo = array(
					"sreceipt" => $sreceipt,
					"ssubtotal" => $ssubtotal,
					"sextrapaid" => $sextrapaid,
					"stotal" => $stotal,
				);
				/*Select receipt group by one employee keyword*/
				$stmt = $pdo->prepare("SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Employees.Username FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Employees.Username LIKE :a AND Receipts.CheckInTime BETWEEN :f AND :t GROUP By Receipts.CheckOutEmpId ORDER BY CountReceipt DESC LIMIT $a,$b");	
				$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$item = array (
							"Username" => $row ['Username'], 
							"CountReceipt" => $row ['CountReceipt'],
							"SubTotals" => $row ['SubTotals'],
							"ExtraPaids" => $row ['ExtraPaids'],
							"Totals" => $row ['Totals'],
					);
					$data [] = $item;
 				}
			}
			
			$data = array ( 
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data,
					"totalInfo" => $totalInfo 
			);
			Database::disconnect ();
			break;
		
		case "getByDesk":
			$pdo = Database::connect ();
			$count = 0;
			$sreceipt = 0;
			$ssubtotal = 0;
			$sextrapaid = 0;
			$stotal = 0;
			$totalInfo = array ();
			$data = array ();
			if($KeyWord == ''){
				if($Zone_ID == 0){
					$case = "Chọn tất cả các bàn trong time";
					/*Count how many record avaible to show*/
					$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts WHERE Receipts.CheckInTime BETWEEN :f AND :t GROUP BY Receipts.DeskNo";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
 					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$count++;
					}
					$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t";
					$stmt = $pdo->prepare($sqlCount);
 					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$sreceipt += intval($row ['CountReceipt']);
						$ssubtotal += intval($row ['SubTotals']);
						$sextrapaid += intval($row ['ExtraPaids']);
						$stotal += intval($row ['Totals']);
					}
					$totalInfo = array(
						"sreceipt" => $sreceipt,
						"ssubtotal" => $ssubtotal,
						"sextrapaid" => $sextrapaid,
						"stotal" => $stotal,
					);
					/*Select receipt group by employee*/
					$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE Receipts.CheckInTime BETWEEN :f AND :t GROUP BY Receipts.DeskNo ORDER BY CountReceipt DESC LIMIT $a,$b";
					$stmt = $pdo->prepare($sqlSelect);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
 					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$item = array (
								"DeskNo" => $row ['DeskNo'], 
								"CountReceipt" => $row ['CountReceipt'],
								"SubTotals" => $row ['SubTotals'],
								"ExtraPaids" => $row ['ExtraPaids'],
								"Totals" => $row ['Totals'],
						);
						$data [] = $item;
					}
				} else {
					$fromDesk = 0; $toDesk = 0;
					switch ($Zone_ID) {
						case "1":
							$fromDesk = 0; $toDesk = 50;
							break;
						case "2":
							$fromDesk = 51; $toDesk = 100;
							break;
						case "3":
							$fromDesk = 101; $toDesk = 150;
							break;
						case "4":
							$fromDesk = 151; $toDesk = 200;
							break;
						default:
							break;
					}
 					$case = "Chọn tất cả các nhân viên trong time và zone";
					/*Count how many record avaible to show*/
					$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts WHERE Receipts.Status = 2 AND (Receipts.DeskNo BETWEEN :a AND :b) AND (Receipts.CheckInTime BETWEEN :f AND :t) GROUP BY Receipts.DeskNo";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
					$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$count++;
					}
					$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE  (Receipts.DeskNo BETWEEN :a AND :b) AND Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
					$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
 					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$sreceipt += intval($row ['CountReceipt']);
						$ssubtotal += intval($row ['SubTotals']);
						$sextrapaid += intval($row ['ExtraPaids']);
						$stotal += intval($row ['Totals']);
					}
					$totalInfo = array(
						"sreceipt" => $sreceipt,
						"ssubtotal" => $ssubtotal,
						"sextrapaid" => $sextrapaid,
						"stotal" => $stotal,
					);
					/*Select receipt group by employee*/
					$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE  (Receipts.DeskNo BETWEEN :a AND :b) AND (Receipts.CheckInTime BETWEEN :f AND :t) GROUP BY Receipts.DeskNo ORDER BY CountReceipt DESC LIMIT $a,$b";
					$stmt = $pdo->prepare($sqlSelect);
					$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
					$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);			$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$item = array (
								"DeskNo" => $row ['DeskNo'], 
								"CountReceipt" => $row ['CountReceipt'],
								"SubTotals" => $row ['SubTotals'],
								"ExtraPaids" => $row ['ExtraPaids'],
								"Totals" => $row ['Totals'],
						);
						$data [] = $item;
					}
				}
				
			}else{
				$case = "Chọn các bàn trong time theo tên";
				/*Count how many record avaible to show*/
				$stmt = $pdo->prepare("SELECT COUNT(Receipts.Id) AS count FROM Receipts WHERE Receipts.DeskNo LIKE :a AND Receipts.CheckInTime BETWEEN :f AND :t GROUP BY Receipts.DeskNo");
				$KeyWord = '%'.$KeyWord.'%';
				$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $rq){
						$count++;
				}
				$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE  Receipts.DeskNo LIKE :a AND Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$sreceipt += intval($row ['CountReceipt']);
					$ssubtotal += intval($row ['SubTotals']);
					$sextrapaid += intval($row ['ExtraPaids']);
					$stotal += intval($row ['Totals']);
				}
				$totalInfo = array(
					"sreceipt" => $sreceipt,
					"ssubtotal" => $ssubtotal,
					"sextrapaid" => $sextrapaid,
					"stotal" => $stotal,
				);
				/*Select receipt group by one employee keyword*/
				$stmt = $pdo->prepare("SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE Receipts.DeskNo LIKE :a AND Receipts.CheckInTime BETWEEN :f AND :t GROUP BY Receipts.DeskNo ORDER BY CountReceipt DESC LIMIT $a,$b");	
				$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$item = array (
							"DeskNo" => $row ['DeskNo'], 
							"CountReceipt" => $row ['CountReceipt'],
							"SubTotals" => $row ['SubTotals'],
							"ExtraPaids" => $row ['ExtraPaids'],
							"Totals" => $row ['Totals'],
					);
					$data [] = $item;
 				}
			}
			
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data, 
					"totalInfo" => $totalInfo 
			);
			Database::disconnect ();
			break;

		case "getByProduct":
			$pdo = Database::connect ();
			$count = 0;
			$sproduct = 0;
			$squantity = 0;
			$samount = 0;
 			$totalInfo = array ();
			$data = array ();
			if($KeyWord == ''){
				if($Zone_ID == 0){
					$case = "Chọn các sản phẩm trong time";
					/*Count how many record avaible to show*/
					$sqlCount = "SELECT COUNT(OrderItems.Id) AS count, OrderItems.ProductId FROM OrderItems WHERE OrderItems.ServedTime BETWEEN :f AND :t GROUP BY OrderItems.ProductId";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$count++;
					}
					$sqlCount = "SELECT SUM(Quantity) as Quantity, SUM(Amount) as Amount FROM OrderItems INNER JOIN Products ON OrderItems.ProductId = Products.Id WHERE OrderItems.ServedTime BETWEEN :f AND :t AND OrderItems.IsServed = 1";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$squantity += intval($row ['Quantity']);
						$samount += intval($row ['Amount']);
					}
					$totalInfo = array(
						"sproduct" => $count,
						"squantity" => $squantity,
						"samount" => $samount,
					);
					/*Select receipt group by employee*/
					$sqlSelect = "SELECT OrderItems.ProductId, Products.Name, OrderItems.Price, SUM(Quantity) as Quantity, SUM(Amount) as Amount FROM OrderItems INNER JOIN Products ON OrderItems.ProductId = Products.Id WHERE OrderItems.ServedTime BETWEEN :f AND :t GROUP BY OrderItems.ProductId ORDER BY Quantity DESC  LIMIT $a,$b";
					$stmt = $pdo->prepare($sqlSelect);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$item = array (
								"ProductId" => $row ['ProductId'], 
								"Name" => $row ['Name'], 
								"Price" => $row ['Price'],
								"Quantity" => $row ['Quantity'],
								"Amount" => $row ['Amount'],
						);
						$data [] = $item;
					}
				}else{
					$case = "Chọn các sản phẩm trong time";
					/*Count how many record avaible to show*/
					$sqlCount = "SELECT COUNT(OrderItems.Id) AS count, OrderItems.ProductId FROM OrderItems INNER JOIN Products ON OrderItems.ProductId = Products.Id WHERE Products.CategoryId = :a AND OrderItems.ServedTime BETWEEN :f AND :t GROUP BY OrderItems.ProductId";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':a', $Zone_ID, PDO::PARAM_STR);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$count++;
					}
					$sqlCount = "SELECT SUM(Quantity) as Quantity, SUM(Amount) as Amount FROM OrderItems  INNER JOIN Products ON OrderItems.ProductId = Products.Id WHERE Products.CategoryId = :a AND OrderItems.ServedTime BETWEEN :f AND :t AND OrderItems.IsServed = 1";
					$stmt = $pdo->prepare($sqlCount);
					$stmt->bindParam(':a', $Zone_ID, PDO::PARAM_STR);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$squantity += intval($row ['Quantity']);
						$samount += intval($row ['Amount']);
					}
					$totalInfo = array(
						"sproduct" => $count,
						"squantity" => $squantity,
						"samount" => $samount,
					);
					/*Select receipt group by employee*/
					$sqlSelect = "SELECT OrderItems.ProductId, Products.Name, OrderItems.Price, SUM(Quantity) as Quantity, SUM(Amount) as Amount FROM OrderItems  INNER JOIN Products ON OrderItems.ProductId = Products.Id WHERE Products.CategoryId = :a AND OrderItems.ServedTime BETWEEN :f AND :t GROUP BY OrderItems.ProductId ORDER BY Quantity DESC  LIMIT $a,$b";
					$stmt = $pdo->prepare($sqlSelect);
					$stmt->bindParam(':a', $Zone_ID, PDO::PARAM_STR);
					$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
					$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
					$stmt->execute();
					$request = $stmt->fetchAll();
					foreach($request as $row){
						$item = array (
								"ProductId" => $row ['ProductId'], 
								"Name" => $row ['Name'], 
								"Price" => $row ['Price'],
								"Quantity" => $row ['Quantity'],
								"Amount" => $row ['Amount'],
						);
						$data [] = $item;
					}
				}
				
			}else{
				$case = "Chọn các bàn trong time theo tên";
				/*Count how many record avaible to show*/
				$stmt = $pdo->prepare("SELECT COUNT(OrderItems.Id) AS count, OrderItems.ProductId FROM OrderItems INNER JOIN Products ON OrderItems.ProductId = Products.Id WHERE (OrderItems.ServedTime BETWEEN :f AND :t) AND Products.Name LIKE :a GROUP BY OrderItems.ProductId");
				$KeyWord = '%'.$KeyWord.'%';
				$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $rq){
						$count++;
				}
				$sqlCount = "SELECT SUM(Quantity) as Quantity, SUM(Amount) as Amount FROM OrderItems INNER JOIN Products ON OrderItems.ProductId = Products.Id WHERE OrderItems.ServedTime BETWEEN :f AND :t AND OrderItems.IsServed = 1 AND Products.Name LIKE :a";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
 				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
 					$squantity += intval($row ['Quantity']);
					$samount += intval($row ['Amount']);
 				}
				$totalInfo = array(
					"sproduct" => $count,
					"squantity" => $squantity,
					"samount" => $samount,
 				);
				/*Select receipt group by one employee keyword*/
				$stmt = $pdo->prepare("SELECT OrderItems.ProductId, Products.Name, OrderItems.Price, SUM(Quantity) as Quantity, SUM(Amount) as Amount FROM OrderItems INNER JOIN Products ON OrderItems.ProductId = Products.Id WHERE (OrderItems.ServedTime BETWEEN :f AND :t) AND Products.Name LIKE :a GROUP BY OrderItems.ProductId ORDER BY Quantity DESC LIMIT $a,$b");	
				$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$item = array (
							"ProductId" => $row ['ProductId'], 
							"Name" => $row ['Name'], 
							"Price" => $row ['Price'],
							"Quantity" => $row ['Quantity'],
							"Amount" => $row ['Amount'],
					);
					$data [] = $item;
 				}
			}
			
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data,
					"totalInfo" => $totalInfo 
			);
			Database::disconnect ();
			break;

		case "getByDay":
			$pdo = Database::connect ();
			$count = 0;
			$sreceipt = 0;
			$ssubtotal = 0;
			$sextrapaid = 0;
			$stotal = 0;
			$totalInfo = array ();
			$data = array (); 
			if($Zone_ID == 0){
				$case = "Chọn tất cả các bàn trong time";
				/*Count how many record avaible to show*/
				$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts WHERE Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t GROUP By DATE(Receipts.CheckInTime)";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$count++;
				}
				$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$sreceipt += intval($row ['CountReceipt']);
					$ssubtotal += intval($row ['SubTotals']);
					$sextrapaid += intval($row ['ExtraPaids']);
					$stotal += intval($row ['Totals']);
				}
				$totalInfo = array(
					"sreceipt" => $sreceipt,
					"ssubtotal" => $ssubtotal,
					"sextrapaid" => $sextrapaid,
					"stotal" => $stotal,
				);
				/*Select receipt group by employee*/
				$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Date(Receipts.CheckOutTime) as ThoiGian FROM Receipts WHERE Receipts.CheckInTime BETWEEN :f AND :t GROUP BY DATE(Receipts.CheckInTime) LIMIT $a,$b";
				$stmt = $pdo->prepare($sqlSelect);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$item = array (
							"ThoiGian" => $row ['ThoiGian'], 
							"CountReceipt" => $row ['CountReceipt'],
							"SubTotals" => $row ['SubTotals'],
							"ExtraPaids" => $row ['ExtraPaids'],
							"Totals" => $row ['Totals'],
					);
					$data [] = $item;
				}
			} else {
				$fromDesk = 0; $toDesk = 0;
				switch ($Zone_ID) {
					case "1":
						$fromDesk = 0; $toDesk = 50;
						break;
					case "2":
						$fromDesk = 51; $toDesk = 100;
						break;
					case "3":
						$fromDesk = 101; $toDesk = 150;
						break;
					case "4":
						$fromDesk = 151; $toDesk = 200;
						break;
					default:
						break;
				}
				$case = "Chọn các hóa đơn theo ngày và khu vực";
				/*Count how many record avaible to show*/
				$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE (Receipts.DeskNo BETWEEN :a AND :b) AND (Receipts.CheckInTime BETWEEN :f AND :t) GROUP By DATE(Receipts.CheckInTime)";
				$stmt = $pdo->prepare($sqlCount);

				$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
				$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);		
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$count++;
				}
				$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE (Receipts.DeskNo BETWEEN :a AND :b) AND Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
				$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$sreceipt += intval($row ['CountReceipt']);
					$ssubtotal += intval($row ['SubTotals']);
					$sextrapaid += intval($row ['ExtraPaids']);
					$stotal += intval($row ['Totals']);
				}
				$totalInfo = array(
					"sreceipt" => $sreceipt,
					"ssubtotal" => $ssubtotal,
					"sextrapaid" => $sextrapaid,
					"stotal" => $stotal,
				);
				/*Select receipt group by employee*/
				$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Employees.Username , Date(Receipts.CheckOutTime) as ThoiGian FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE (Receipts.DeskNo BETWEEN :a AND :b) AND Receipts.CheckInTime BETWEEN :f AND :t GROUP By Receipts.CheckOutEmpId LIMIT $a,$b";
				$stmt = $pdo->prepare($sqlSelect);

				$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
				$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
 				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$item = array (
							"ThoiGian" => $row ['ThoiGian'], 
							"CountReceipt" => $row ['CountReceipt'],
							"SubTotals" => $row ['SubTotals'],
							"ExtraPaids" => $row ['ExtraPaids'],
							"Totals" => $row ['Totals'],
					);
					$data [] = $item;
				}
			}
				 
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data,
					"totalInfo" => $totalInfo 

			);
			Database::disconnect ();
			break;

		case "getByWeek":
			$pdo = Database::connect ();
			$count = 0;
			$sreceipt = 0;
			$ssubtotal = 0;
			$sextrapaid = 0;
			$stotal = 0;
			$totalInfo = array ();
			$data = array (); 
			if($Zone_ID == 0){
				$case = "Chọn theo tuần trong time";
				/*Count how many record avaible to show*/
				$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts WHERE Receipts.CheckInTime BETWEEN :f AND :t GROUP BY WEEK(Receipts.CheckInTime, 1)";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$count++;
				}
				$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$sreceipt += intval($row ['CountReceipt']);
					$ssubtotal += intval($row ['SubTotals']);
					$sextrapaid += intval($row ['ExtraPaids']);
					$stotal += intval($row ['Totals']);
				}
				$totalInfo = array(
					"sreceipt" => $sreceipt,
					"ssubtotal" => $ssubtotal,
					"sextrapaid" => $sextrapaid,
					"stotal" => $stotal,
				);
				/*Select receipt group by employee*/
				$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, WEEK(Receipts.CheckInTime, 1) as ThoiGian FROM Receipts WHERE Receipts.CheckInTime BETWEEN :f AND :t GROUP By WEEK(Receipts.CheckInTime, 1) LIMIT $a,$b";
				$stmt = $pdo->prepare($sqlSelect);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$item = array (
							"ThoiGian" => $row ['ThoiGian'], 
							"CountReceipt" => $row ['CountReceipt'],
							"SubTotals" => $row ['SubTotals'],
							"ExtraPaids" => $row ['ExtraPaids'],
							"Totals" => $row ['Totals'],
					);
					$data [] = $item;
				}
			} else {
				$fromDesk = 0; $toDesk = 0;
				switch ($Zone_ID) {
					case "1":
						$fromDesk = 0; $toDesk = 50;
						break;
					case "2":
						$fromDesk = 51; $toDesk = 100;
						break;
					case "3":
						$fromDesk = 101; $toDesk = 150;
						break;
					case "4":
						$fromDesk = 151; $toDesk = 200;
						break;
					default:
						break;
				}
				$case = "Chọn theo tuần trong time và zone";
				/*Count how many record avaible to show*/
				$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE  (Receipts.DeskNo BETWEEN :a AND :b) AND Receipts.CheckInTime BETWEEN :f AND :t GROUP BY WEEK(Receipts.CheckInTime, 1)";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
				$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$count++;
				}
				$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE (Receipts.DeskNo BETWEEN :a AND :b) AND Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
				$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$sreceipt += intval($row ['CountReceipt']);
					$ssubtotal += intval($row ['SubTotals']);
					$sextrapaid += intval($row ['ExtraPaids']);
					$stotal += intval($row ['Totals']);
				}
				$totalInfo = array(
					"sreceipt" => $sreceipt,
					"ssubtotal" => $ssubtotal,
					"sextrapaid" => $sextrapaid,
					"stotal" => $stotal,
				);
				/*Select receipt group by employee*/
				$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, WEEK(Receipts.CheckInTime, 1) as ThoiGian FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE (Receipts.DeskNo BETWEEN :a AND :b) AND Receipts.CheckInTime BETWEEN :f AND :t GROUP BY  WEEK(Receipts.CheckInTime, 1) LIMIT $a,$b";
				$stmt = $pdo->prepare($sqlSelect);
				$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
				$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$item = array (
							"ThoiGian" => $row ['ThoiGian'], 
							"CountReceipt" => $row ['CountReceipt'],
							"SubTotals" => $row ['SubTotals'],
							"ExtraPaids" => $row ['ExtraPaids'],
							"Totals" => $row ['Totals'],
					);
					$data [] = $item;
				}
			}
				 
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data, 
					"totalInfo" => $totalInfo 
			);
			Database::disconnect ();
			break;

		case "getByMonth":
			$pdo = Database::connect ();
			$count = 0;
			$sreceipt = 0;
			$ssubtotal = 0;
			$sextrapaid = 0;
			$stotal = 0;
			$totalInfo = array ();
			$data = array (); 
			if($Zone_ID == 0){
				$case = "Chọn theo tuần trong time";
				/*Count how many record avaible to show*/
				$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts WHERE Receipts.CheckInTime BETWEEN :f AND :t GROUP BY MONTH(Receipts.CheckInTime)";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$count++;
				}
				$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$sreceipt += intval($row ['CountReceipt']);
					$ssubtotal += intval($row ['SubTotals']);
					$sextrapaid += intval($row ['ExtraPaids']);
					$stotal += intval($row ['Totals']);
				}
				$totalInfo = array(
					"sreceipt" => $sreceipt,
					"ssubtotal" => $ssubtotal,
					"sextrapaid" => $sextrapaid,
					"stotal" => $stotal,
				);
				/*Select receipt group by employee*/
				$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, MONTH(Receipts.CheckInTime) as ThoiGian FROM Receipts WHERE Receipts.CheckInTime BETWEEN :f AND :t GROUP By MONTH(Receipts.CheckInTime) LIMIT $a,$b";
				$stmt = $pdo->prepare($sqlSelect);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$item = array (
							"ThoiGian" => $row ['ThoiGian'], 
							"CountReceipt" => $row ['CountReceipt'],
							"SubTotals" => $row ['SubTotals'],
							"ExtraPaids" => $row ['ExtraPaids'],
							"Totals" => $row ['Totals'],
					);
					$data [] = $item;
				}
			} else {
				$fromDesk = 0; $toDesk = 0;
				switch ($Zone_ID) {
					case "1":
						$fromDesk = 0; $toDesk = 50;
						break;
					case "2":
						$fromDesk = 51; $toDesk = 100;
						break;
					case "3":
						$fromDesk = 101; $toDesk = 150;
						break;
					case "4":
						$fromDesk = 151; $toDesk = 200;
						break;
					default:
						break;
				}
				$case = "Chọn theo tháng trong time và zone";
				/*Count how many record avaible to show*/
				$sqlCount = "SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE (Receipts.DeskNo BETWEEN :a AND :b) AND Receipts.CheckInTime BETWEEN :f AND :t GROUP BY MONTH(Receipts.CheckInTime)";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
				$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$count++;
				}
				$sqlCount = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, Receipts.DeskNo FROM Receipts WHERE (Receipts.DeskNo BETWEEN :a AND :b) AND Receipts.Status = 2 AND Receipts.CheckInTime BETWEEN :f AND :t";
				$stmt = $pdo->prepare($sqlCount);
				$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
				$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$sreceipt += intval($row ['CountReceipt']);
					$ssubtotal += intval($row ['SubTotals']);
					$sextrapaid += intval($row ['ExtraPaids']);
					$stotal += intval($row ['Totals']);
				}
				$totalInfo = array(
					"sreceipt" => $sreceipt,
					"ssubtotal" => $ssubtotal,
					"sextrapaid" => $sextrapaid,
					"stotal" => $stotal,
				);
				/*Select receipt group by employee*/
				$sqlSelect = "SELECT COUNT(Receipts.Id) AS CountReceipt, SUM(SubTotal) as SubTotals, SUM(ExtraPaid) as ExtraPaids, SUM(Total) as Totals, MONTH(Receipts.CheckInTime) as ThoiGian FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE (Receipts.DeskNo BETWEEN :a AND :b) AND Receipts.CheckInTime BETWEEN :f AND :t GROUP BY  MONTH(Receipts.CheckInTime) LIMIT $a,$b";
				$stmt = $pdo->prepare($sqlSelect);
				$stmt->bindParam(':a', $fromDesk, PDO::PARAM_STR);
				$stmt->bindParam(':b', $toDesk, PDO::PARAM_STR);
				$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
				$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
				$stmt->execute();
				$request = $stmt->fetchAll();
				foreach($request as $row){
					$item = array (
							"ThoiGian" => $row ['ThoiGian'], 
							"CountReceipt" => $row ['CountReceipt'],
							"SubTotals" => $row ['SubTotals'],
							"ExtraPaids" => $row ['ExtraPaids'],
							"Totals" => $row ['Totals'],
					);
					$data [] = $item;
				}
			}
				 
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data, 
					"totalInfo" => $totalInfo 
			);
			Database::disconnect ();
			break;

		case "getReceipByEmployee":
			$pdo = Database::connect ();
			$count = 0;
			$data = array ();
			$case = "Chọn các hóa đơn theo username nhân viên";
			/*Count how many record avaible to show*/
			$stmt = $pdo->prepare("SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE (Receipts.CheckInTime BETWEEN :f AND :t) AND Employees.Username = :a");
			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
			$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
			$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $row){
					$count = $row['count'];
			} 
			/*Select receipt group by one employee keyword*/
			$stmt = $pdo->prepare("SELECT Receipts.Id, Receipts.SubTotal, Receipts.ExtraPaid, Receipts.Total, Receipts.CheckInTime, Receipts.CheckOutTime, Receipts.DeskNo, f.Username as CheckInEmp, f2.Username as CheckOutEmp FROM Receipts INNER JOIN Employees f ON f.Id=Receipts.EmployeeId INNER JOIN Employees f2 ON f2.Id=Receipts.CheckOutEmpId WHERE f2.Username = :a AND (Receipts.CheckInTime BETWEEN :f AND :t) ORDER BY Receipts.Id DESC LIMIT $a,$b ");	
			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
			$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
			$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $row){
				$item = array (
						"Id" => $row ['Id'], 
						"CheckInEmp" => $row ['CheckInEmp'], 
						"CheckOutEmp" => $row ['CheckOutEmp'], 
						"DeskNo" => $row ['DeskNo'],
						"SubTotal" => $row ['SubTotal'],
						"ExtraPaid" => $row ['ExtraPaid'],
						"Total" => $row ['Total'],
						"CheckInTime" => $row ['CheckInTime'],
						"CheckOutTime" => $row ['CheckOutTime'],
				);
				$data [] = $item;
			}

			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data 
			); 
			Database::disconnect ();
			break;
			
			
			
		case "getReceipByDesk":
			$pdo = Database::connect ();
			$count = 0;
			$data = array ();
			$case = "Chọn các hóa đơn theo bàn trong thời gian";
			/*Count how many record avaible to show*/
			$stmt = $pdo->prepare("SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE Receipts.DeskNo = :a AND Receipts.CheckInTime BETWEEN :f AND :t");
			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
			$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
			$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $row){
					$count = $row['count'];
			} 
			/*Select receipt group by one employee keyword*/
			$stmt = $pdo->prepare("SELECT Receipts.Id, Receipts.SubTotal, Receipts.ExtraPaid, Receipts.Total, Receipts.CheckOutTime, Receipts.DeskNo, f.Username as CheckInEmp, f2.Username as CheckOutEmp FROM Receipts INNER JOIN Employees f ON f.Id=Receipts.EmployeeId INNER JOIN Employees f2 ON f2.Id=Receipts.CheckOutEmpId WHERE Receipts.DeskNo = :a AND (Receipts.CheckInTime BETWEEN :f AND :t) LIMIT $a,$b");	
			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
			$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
			$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $row){
				$item = array (
						"Id" => $row ['Id'], 
						"CheckInEmp" => $row ['CheckInEmp'], 
						"CheckOutEmp" => $row ['CheckOutEmp'], 
						"DeskNo" => $row ['DeskNo'],
						"SubTotal" => $row ['SubTotal'],
						"ExtraPaid" => $row ['ExtraPaid'],
						"Total" => $row ['Total'],
						"CheckOutTime" => $row ['CheckOutTime'],
				);
				$data [] = $item;
			}
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data 
			); 
			Database::disconnect ();
			break;
			
			
			
		case "getReceipByProduct":
			$pdo = Database::connect ();
			$count = 0;
			$data = array ();
			$case = "Chọn các hóa đơn theo bàn trong thời gian";
			/*Count how many record avaible to show*/
			$stmt = $pdo->prepare("SELECT COUNT(OrderItems.ReceiptId) AS count FROM OrderItems INNER JOIN Receipts ON OrderItems.ReceiptId = Receipts.Id  WHERE (OrderItems.ProductId = :a) AND (Receipts.CheckInTime BETWEEN :f AND :t) AND OrderItems.IsServed = '1' GROUP BY OrderItems.ReceiptId");
			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
			$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
			$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $row){
					$count++;
			} 
			/*Select receipt group by one employee keyword*/
			$stmt = $pdo->prepare("SELECT OrderItems.ReceiptId, Receipts.DeskNo, Receipts.CheckOutTime, OrderItems.ProductId, Products.Name, SUM(OrderItems.Quantity) as Quantity, OrderItems.Price, SUM(OrderItems.Amount) as Amount FROM OrderItems INNER JOIN Receipts ON OrderItems.ReceiptId = Receipts.Id INNER JOIN Products ON Products.Id = OrderItems.ProductId WHERE (OrderItems.ProductId = :a) AND (Receipts.CheckInTime BETWEEN :f AND :t) AND OrderItems.IsServed = '1' GROUP BY OrderItems.ReceiptId ORDER BY Quantity DESC LIMIT $a,$b");	
			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
			$stmt->bindParam(':f', $FromDate, PDO::PARAM_STR);
			$stmt->bindParam(':t', $ToDate, PDO::PARAM_STR);
			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $row){
				$item = array (
						"Id" => $row ['ReceiptId'], 
						"DeskNo" => $row ['DeskNo'], 
						"CheckOutTime" => $row ['CheckOutTime'], 
						"ProductId" => $row ['ProductId'],
						"Name" => $row ['Name'],
						"Quantity" => $row ['Quantity'],
						"Price" => $row ['Price'],
						"Amount" => $row ['Amount'],
				);
				$data [] = $item;
			}
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data 
			); 
			Database::disconnect ();
			break;
			
			
			
		case "getReceipByDate":
			$pdo = Database::connect ();
			$count = 0;
			$data = array (); 
			$case = "Chọn các hóa trong time ngày";
			/*Count how many record avaible to show*/
			$stmt = $pdo->prepare("SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId  WHERE DATE(Receipts.CheckInTime) = DATE(:a)");
 			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
 			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $rq){
					$count = $rq['count'];
			} 
			/*Select receipt group by one employee keyword*/
			$stmt = $pdo->prepare("SELECT Receipts.Id, Receipts.SubTotal, Receipts.ExtraPaid, Receipts.Total, Receipts.CheckOutTime, Receipts.CheckInTime, Receipts.DeskNo, f.Username as CheckInEmp, f2.Username as CheckOutEmp FROM Receipts INNER JOIN Employees f ON f.Id=Receipts.EmployeeId INNER JOIN Employees f2 ON f2.Id=Receipts.CheckOutEmpId WHERE DATE(Receipts.CheckInTime) = DATE(:a) LIMIT $a,$b");	
 			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
  			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $row){
				$item = array (
						"Id" => $row ['Id'], 
						"CheckInEmp" => $row ['CheckInEmp'], 
						"CheckOutEmp" => $row ['CheckOutEmp'], 
						"DeskNo" => $row ['DeskNo'],
						"SubTotal" => $row ['SubTotal'],
						"ExtraPaid" => $row ['ExtraPaid'],
						"Total" => $row ['Total'],
						"CheckInTime" => $row ['CheckInTime'],
						"CheckOutTime" => $row ['CheckOutTime'],
				);
				$data [] = $item;
			}
			
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data 
			); 
			Database::disconnect ();
			break;
			
	
		case "getReceipByWeek":
			$pdo = Database::connect ();
			$count = 0;
			$data = array (); 
			$case = "Chọn các hóa đơn trong time tuần";
			/*Count how many record avaible to show*/
			$stmt = $pdo->prepare("SELECT COUNT(Receipts.Id) AS count FROM Receipts INNER JOIN Employees ON Employees.Id=Receipts.CheckOutEmpId WHERE WEEK(Receipts.CheckInTime, 1) = :a");
 			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
 			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $rq){
					$count = $rq['count'];
			} 
			/*Select receipt group by one employee keyword*/
			$stmt = $pdo->prepare("SELECT Receipts.Id, Receipts.SubTotal, Receipts.ExtraPaid, Receipts.Total, Receipts.CheckInTime, Receipts.CheckOutTime, Receipts.DeskNo, f.Username as CheckInEmp, f2.Username as CheckOutEmp FROM Receipts INNER JOIN Employees f ON f.Id=Receipts.EmployeeId INNER JOIN Employees f2 ON f2.Id=Receipts.CheckOutEmpId WHERE WEEK(Receipts.CheckInTime, 1) = :a LIMIT $a,$b");	
 			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
 			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $row){
				$item = array (
						"Id" => $row ['Id'], 
						"CheckInEmp" => $row ['CheckInEmp'], 
						"CheckOutEmp" => $row ['CheckOutEmp'], 
						"DeskNo" => $row ['DeskNo'],
						"SubTotal" => $row ['SubTotal'],
						"ExtraPaid" => $row ['ExtraPaid'],
						"Total" => $row ['Total'],
						"CheckInTime" => $row ['CheckInTime'],
						"CheckOutTime" => $row ['CheckOutTime'],
				);
				$data [] = $item;
			}
			
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data 
			); 
			Database::disconnect ();
			break;
			
				
	
		case "getReceipByMonth":
			$pdo = Database::connect ();
			$count = 0;
			$data = array (); 
			$case = "Chọn các hóa đơn trong time tuần";
			/*Count how many record avaible to show*/
			$stmt = $pdo->prepare("SELECT COUNT(Receipts.Id) AS count FROM Receipts WHERE MONTH(Receipts.CheckInTime) = :a");
 			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
 			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $rq){
					$count = $rq['count'];
			} 
			/*Select receipt group by one employee keyword*/
			$stmt = $pdo->prepare("SELECT Receipts.Id, Receipts.SubTotal, Receipts.ExtraPaid, Receipts.Total, Receipts.CheckInTime, Receipts.CheckOutTime, Receipts.DeskNo, f.Username as CheckInEmp, f2.Username as CheckOutEmp FROM Receipts INNER JOIN Employees f ON f.Id=Receipts.EmployeeId INNER JOIN Employees f2 ON f2.Id=Receipts.CheckOutEmpId WHERE MONTH(Receipts.CheckInTime) = :a LIMIT $a,$b");	
 			$stmt->bindParam(':a', $KeyWord, PDO::PARAM_STR);
 			$stmt->execute();
			$request = $stmt->fetchAll();
			foreach($request as $row){
				$item = array (
						"Id" => $row ['Id'], 
						"CheckInEmp" => $row ['CheckInEmp'], 
						"CheckOutEmp" => $row ['CheckOutEmp'], 
						"DeskNo" => $row ['DeskNo'],
						"SubTotal" => $row ['SubTotal'],
						"ExtraPaid" => $row ['ExtraPaid'],
						"Total" => $row ['Total'],
						"CheckInTime" => $row ['CheckInTime'],
						"CheckOutTime" => $row ['CheckOutTime'],
				);
				$data [] = $item;
			}
			
			$data = array (
					"Status" => true,
					"case" => $case,
					"count" => $count,
					"data" => $data 
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