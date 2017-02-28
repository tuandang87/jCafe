<?php
require 'config.php';

if ($IsLoggedIn) {
	switch ($request->Command) {
		case "initDesksStatus" :
			$fromDesk = 0; $toDesk = 0;
			if ($LoggedEmployee['AccessLevel'] == "3"){
				switch ($LoggedEmployee['RegionNo']) {
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
			}
			else {
				$fromDesk = 0;
				$toDesk = $settings['NumOfDesks'];
			}
			$IndexInArr = -1;
			$jsonInit = '';
			$jsonDesk = '';
			for($i=$fromDesk; $i<=$toDesk; $i++){
				$IndexInArr++;
				if($jsonInit != ''){
					$jsonDesk .= ',';
					$jsonInit .= ',';
				}
				$jsonInit .='"' . $i . '": 0';
				$jsonDesk .= '{"IndexInArr":' .$IndexInArr.',"No":' .$i.',"ReceiptId":0,"Total":0,"IsBusy":false,"IsOverTime":0}';
			}
			$jsonInit ='{' . $jsonInit . '}';
			$EmployeeId = $LoggedEmployee['Id'];
			$sql = "UPDATE Employees SET DesksOfEmp='$jsonInit' WHERE Id='$EmployeeId'";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();
			$jsonDesk = '['.$jsonDesk.']';
			$objDesk = json_decode($jsonDesk);
			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql,
					"Statusdesk" => $jsonInit,
					"fromDesk" => $fromDesk . $toDesk,
					"LoggedEmployee" => $LoggedEmployee['AccessLevel'],
					"Desks" => $objDesk,
					"Request" => $request
			);

			Database::disconnect ();
			break;
		case "increaseStatusOfDesk" :
			$sql = "SELECT Session, Status from DesksStatus WHERE Id=1";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();
			$jsonServer='';
			$severSession = '';
			while ( ($row = $stmt->fetch ()) ) {
				$jsonServer=$row['Status'];
				$severSession = $row['Session'];
				break;
			}
			$severSession = intval($severSession);
			$severSessionOld = $severSession;
			$severSession+=2;
			$objServer = json_decode($jsonServer);
			$DeskNo = $request->DeskNo;
			$severStatusOld=$objServer->{$DeskNo};
			$objServer->{$DeskNo}++;
			if($objServer->{$DeskNo} > 999){
				$objServer->{$DeskNo} = 1;
			}
			$severStatus=$objServer->{$DeskNo};
			if($severSession > 999){
 				$severSession = 1;
			}
			$jsonServer = json_encode($objServer);
			$sql = "UPDATE DesksStatus SET Status='$jsonServer', Session='$severSession' WHERE Id=1";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();
			$data = array (
					"Statusx" => $status,
					"Messagex" => 'Update status of desk no '.$DeskNo,
					"Errorx" => $error,
//					"Server" => $jsonServer,
					"DeskNo" => $DeskNo . ': ' . $severStatusOld .'->'.$severStatus,
//					"SQLx" => $sql,
 					"Requestx" => $request
			);

			Database::disconnect ();
			break;


		case "getDesks" :
			$pdo = Database::connect ();
			$desks = array ();

			$error = array ();

			$fromDesk = 0;
			$toDesk = 0;
			//Give region for each employee
			if ($LoggedEmployee['AccessLevel'] == "3"){
				switch ($LoggedEmployee['RegionNo']) {
					case "1":
						$fromDesk = 0;
						$toDesk = 50;
						break;
					case "2":
						$fromDesk = 51;
						$toDesk = 100;
						break;
					case "3":
						$fromDesk = 101;
						$toDesk = 150;
						break;
					case "4":
						$fromDesk = 151;
						$toDesk = 200;
						break;
					default:
						break;
				}
			}
			else {//For others
					$fromDesk = 0;
					$toDesk = $settings['NumOfDesks'];
			}
			$sql = "SELECT Session, Status from DesksStatus WHERE Id=1";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();
			$jsonServer='';
			$severSession = '';
			while ( ($row = $stmt->fetch ()) ) {
				$jsonServer=$row['Status'];
				$severSession = $row['Session'];
				break;
			}
			$stringX = '';
			$objServer = json_decode($jsonServer);
			//Bão
			$EmployeeId = $LoggedEmployee['Id'];
			$sql = "SELECT DesksOfEmp from Employees WHERE Id='$EmployeeId'";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();
			$jsonClient='';
			while ( ($row = $stmt->fetch ()) ) {
				$jsonClient=$row['DesksOfEmp'];
				break;
			}
			$objClient = json_decode($jsonClient);
			$jsonClientx = $jsonClient;
			if($request->ClientSession != $severSession){
				// for each desk
				$IndexInArr = -1;
				for($i = $fromDesk; $i <= $toDesk; $i ++) {
					$IndexInArr++;
					$desk = array (
							"IndexInArr" => $IndexInArr,
							"No" => $i,
							"ReceiptId" => 0,
							"Total" => 0,
							"IsBusy" => false,
							"IsOverTime" => 0,
							"IsUpdate" => 0,
					);
					if( ($objServer->{$i}) != ($objClient->{$i}) ){
						$stringX .= ', ' . $i;
						$countUpdateQuery++;			
						$sql = "SELECT * FROM Receipts WHERE Receipts.DeskNo='$i' AND Receipts.Status=" . ReceiptStatus::Ordered;
						$stmt = $pdo->prepare ( $sql );
						$stmt->execute ();
						$numRows = 0;

						$status = false;
						$message = "Get Desks failure!";

						if ($stmt->errorCode () == 0) {
//							if($objServer->{$i} - $objClient->{$i} ==0){
								$objClient->{$i} = $objServer->{$i};
//							}
//							$objClient->{$i}++;
							$message = "Get Desks  successfully!";
							$status = true;
							// Find receipt data for each desk
							while ( ($row = $stmt->fetch ()) != false ) {
								$numRows++;
								$desk = array (
										"IndexInArr" => $IndexInArr,
										"No" => $i,
										"ReceiptId" => $row ['Id'],
										"Total" => $row ['Total'],
										"SubTotal" => $row ['SubTotal'],
										"IsBusy" => true,
										"UpdatedTime" => $row ['UpdatedTime'],
										"CheckInTime" => $row ['CheckInTime'],
										"IsCompletedServing" => $row ["IsCompletedServing"],
										"IsOverTime" => 0,
										"Note"=>$row['Note'],
										"IsUpdate" => 1,
								);
								break;
							}
							if($numRows == 0){
								$desk = array (
									"IndexInArr" => $IndexInArr,
									"No" => $i,
									"ReceiptId" => 0,
									"Total" => 0,
									"IsBusy" => false,
									"IsOverTime" => 0,
									"IsUpdate" => 1,
								);
							}
						} else {
							$error = $stmt->errorInfo ();
						}
					}

					$desks [] = $desk;
				} // end for each desk
				$jsonClient = json_encode($objClient);
				$sql = "UPDATE Employees SET DesksOfEmp='$jsonClient' WHERE Id='$EmployeeId'";
				$stmt = $pdo->prepare ( $sql );
				$stmt->execute ();
			}
			$data = array (
					"Status" => $status,
					//"Message" => $message,
					"Message" => 'DESK UPDATE',
					"Desks" => $desks,
					"ServerSession" => $severSession,
					"ClientSession" => $request->ClientSession,
					"CountUpdateQuery" => $countUpdateQuery,
					"jsonClient" => $jsonClientx,
					"jsonServer" => $jsonServer,
					"stringX" => $stringX,
					"Error" => $error,
					"SQL" => $sql
			);
			Database::disconnect ();

			break;

		case "getProducts" :
			$pdo = Database::connect ();
			$sql = "SELECT * FROM Products ORDER BY IsFavorite DESC, Id ASC";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Get Products failure!";

			$products = array ();
			$error = array ();
			if ($stmt->errorCode () == 0) {
				$message = "Get Products  successfully!";
				$status = true;
				$index = - 1;
				while ( ($row = $stmt->fetch ()) != false ) {
					$index ++;
					$product = array (
							"IndexInArr" => $index,
							"Id" => $row ['Id'],
							"Name" => $row ["Name"],
							"Price" => $row ["Price"],
							"IsFavorite" => $row ["IsFavorite"],
							"Image" => $row ["Image"]
					);
					$products [] = $product;
				}
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Products" => $products,
					"Error" => $error,
					"SQL" => $sql
			);
			Database::disconnect ();
			break;

		case "getReceiptInfo" :
			$pdo = Database::connect ();

			// Get Receipt Info
			$sql = "SELECT Receipts.*, Employees.Username as Username
				FROM Receipts
				INNER JOIN Employees ON Receipts.EmployeeId = Employees.Id
				WHERE Receipts.Id=$request->ReceiptId";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Get Receipt failure!";
			$error = array ();
			$receipt = "";
			$total = 0;
			if ($stmt->errorCode () == 0) {
				$message = "Get Receipt  successfully!";
				$status = true;
				while ( ($row = $stmt->fetch ()) != false ) {
					$receipt = array (
							"Id" => $row ["Id"],
							"DeskNo" => $row ["DeskNo"],
							"EmployeeId" => $row ["EmployeeId"],
							"Date" => $row ["Date"],
							"SubTotal" => $row ["SubTotal"],
							"Tax" => $row ["Tax"],
							"Discount" => $row ["Discount"],
							"ExtraPaidPerItem" => $settings["ExtraPaidPerItem"],
							"NumOfItems" => $row ["NumOfItems"],
							"ExtraPaid" => $row ["ExtraPaid"],
							"Total" => $row ["Total"],
							"Paid" => $row ["Paid"],
							"DueChange" => $row ["DueChange"],
							"Status" => $row ["Status"],
							"UpdatedTime" => $row ["UpdatedTime"],
							"CheckInTime" => $row ["CheckInTime"],
							"CheckOutTime" => $row ["CheckOutTime"],
							"IsCompletedServing" => $row ["IsCompletedServing"],
							"Note" => $row ["Note"],
							"Username" => $row ["Username"],
					);
					break;
				}

				// Get Order Items
				$items = array ();
				$sql = "SELECT OrderItems.*,  Products.Name AS Name
				FROM OrderItems
				INNER JOIN Products ON	OrderItems.ProductId=Products.Id
				WHERE ReceiptId=$request->ReceiptId ORDER BY OrderItems.Id ASC";
				$stmt = $pdo->prepare ( $sql );
				$stmt->execute ();

				$status = false;
				$message = "Get OrderItems failure!";
				$total = 0;
				if ($stmt->errorCode () == 0) {
					$message = "Get OrderItems  successfully!";
					$status = true;
					while ( ($row = $stmt->fetch ()) != false ) {
						$OrderItem = array (
								"Id" => $row ["Id"],
								"ReceiptId" => $row ["ReceiptId"],
								"ProductId" => $row ["ProductId"],
								"ProductName" => $row ["Name"],
								"Price" => $row ["Price"],
								"Quantity" => $row ["Quantity"],
								"Amount" => $row ["Amount"],
								"IsServed" => $row ["IsServed"],
								"ServedTime" => $row ["ServedTime"],
								"IsUpdated" => $row ["IsUpdated"]
						);
						$total += $row ["Amount"];
						$items [] = $OrderItem;
					}
				} else {
					$error = $stmt->errorInfo ();
				}
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Receipt" => $receipt,
					"OrderItems" => $items,
					"SubTotal" => $total,
					"Error" => $error,
					"SQL" => $sql
			);
			Database::disconnect ();
			break;

		case "postNewOrderItems" :
			// Create a new receipt
			$pdo = Database::connect ();
			$sql = "INSERT INTO Receipts(EmployeeId, DeskNo, CheckInTime, SubTotal, Total, Status)
				VALUES ($request->EmployeeId, $request->DeskNo,
				NOW(), $request->SubTotal, $request->Total, $request->ReceiptStatus)";

			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$ReceiptId = 0;
			$status = false;
			$message = "New Receipt failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "New Receipt  successfully!";
				$status = true;
				// Find the new receipt ID

				$sql = "SELECT MAX(id) as Id, DeskNo FROM Receipts WHERE DeskNo=$request->DeskNo";
				$stmt = $pdo->prepare ( $sql );
				$stmt->execute ();

				if ($stmt->errorCode () == 0) {

					while ( ($row = $stmt->fetch ()) != false ) {
						$ReceiptId = $row ["Id"];
						$status = true;
						$message = "Get New Receipt successful!";
						break;
					}
				} else {
					$error = $stmt->errorInfo ();
				}

				if ($ReceiptId != 0) {
					// Insert order items
					foreach ( $request->OrderItems as $item ) {
						$sql = "INSERT INTO OrderItems(ReceiptId, Date, ProductId, Price, Quantity, Amount)
								VALUES ($ReceiptId, NOW(), $item->ProductId, $item->Price,
								$item->Quantity, $item->Price*$item->Quantity)";
						$stmt = $pdo->prepare ( $sql );
						$stmt->execute ();

						$status = false;
						$message = "Insert New Order Items failure!";
						if ($stmt->errorCode () == 0) {
							$status = true;
							$message = "Insert New Order Items successful!";
						} else {
							// In case of we cannot insert items
							$sql = "DELETE FROM Receipts WHERE Id = $ReceiptId;";
							$stmt = $pdo->prepare ( $sql );
							$stmt->execute ();
							break;
						}
					} // end foreach
				} // end if
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"ReceiptId" => $ReceiptId,
					"Request" => $request,
					"DeskNo" => $request->DeskNo,
					"Error" => $stmt->errorInfo (),
					"SQL" => $sql
			);

			Database::disconnect ();
			break;

		case "postUpdatedOrderItems" :
			$pdo = Database::connect ();
			$sql = "UPDATE Receipts SET SubTotal = $request->SubTotal,
				NumOfItems = $request->NumOfItems, ExtraPaidPerItem = $request->ExtraPaidPerItem, ExtraPaid = $request->ExtraPaid,
				Total = $request->Total, Note = '$request->Note'
				WHERE Id = $request->ReceiptId;";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Update Receipt failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "Update Receipt  successfully!";
				$status = true;

				// Insert order items
				foreach ( $request->OrderItems as $item ) {
					if ($item->Quantity > 0) {
						if ($item->Id == 0) {
							$sql = "INSERT INTO OrderItems(ReceiptId, ProductId, Price, Quantity, Amount, Date, IsUpdated)
							VALUES ($item->ReceiptId, $item->ProductId, $item->Price,
							$item->Quantity, $item->Price*$item->Quantity, NOW(), 1)";
						} else {
							/*
							$sql = "UPDATE OrderItems SET
							Quantity = $item->Quantity,
							Amount = $item->Price*$item->Quantity,
							IsUpdated = $item->IsUpdated
							WHERE Id = $item->Id";
							*/
						}
					}
					//Quantity == 0
					else {
						if ($item->Id > 0)
							$sql = "DELETE FROM OrderItems WHERE Id = $item->Id";
					}

					$stmt = $pdo->prepare ( $sql );
					$stmt->execute ();

					$status = false;
					$message = "Update OrderItems failure!";
					if ($stmt->errorCode () == 0) {
						$message = "Update OrderItems  successfully!";
						$status = true;
					} else {
						$error = $stmt->errorInfo ();
					}
				}
			} else {
				$error = $stmt->errorInfo (); // error for receipt
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql,
					"SQLi" => "UPDATE Receipts SET SubTotal = '".$request->SubTotal."',
				NumOfItems = '".$request->NumOfItems."', ExtraPaidPerItem = '".$request->ExtraPaidPerItem."', ExtraPaid = '".$request->ExtraPaid."',
				Total = '".$request->Total."', Note = '".$request->Note."'
				WHERE Id = '".$request->ReceiptId.";"
			);
			Database::disconnect ();
			break;

		case "serveOrderItem" :
			$pdo = Database::connect ();
			// Update sub total
			$sql = "UPDATE OrderItems SET IsServed= $request->IsServed, ServedTime=NOW() WHERE Id = $request->OrderItemId";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Serve item failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$sql = "UPDATE Receipts SET IsCompletedServing= $request->IsCompletedServing, UpdatedTime = NOW() WHERE Id = $request->ReceiptId";
				$stmt = $pdo->prepare ( $sql );
				$stmt->execute ();

				$message = "Update Receipts.IsCompletedServing failure!";
				$status = false;
				if ($stmt->errorCode () == 0) {
					$message = "Update Receipts.IsCompletedServing successfully!";
					$status = true;
				}else {
					$error = $stmt->errorInfo ();
				}

			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql,
					"Request" => $request
			);

			Database::disconnect ();
			break;

		case "serveOrderItems" :
			$pdo = Database::connect ();

			// Update Receipt
			$sql = "UPDATE Receipts SET IsCompletedServing=1, UpdatedTime=NOW() WHERE Id=$request->ReceiptId;";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Serve Receipt failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "Serve Receipt  successfully!";
				$status = true;

				foreach ( $request->OrderItems as $item ) {
					$sql = "UPDATE OrderItems SET IsServed=1, IsUpdated=0, ServedTime=NOW() WHERE Id=$item->Id;";
					$stmt = $pdo->prepare ( $sql );
					$stmt->execute ();

					$status = false;
					$message = "Serve OrderItems failure!";
					if ($stmt->errorCode () == 0) {
						$message = "Serve OrderItems  successfully!";
						$status = true;
					} else {
						$error = $stmt->errorInfo (); // error for items
					}
				}
			} else {
				$error = $stmt->errorInfo (); // error for receipt
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql,
					"Request" => $request
			);
			Database::disconnect ();
			break;
		case "addOrderItemWasServer" :
			$pdo = Database::connect ();

			// Update Receipt
			$sql = "UPDATE Receipts SET IsCompletedServing=0, UpdatedTime=NOW() WHERE Id=$request->ReceiptId;";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Serve Receipt failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "Serve Receipt  successfully!";
				$status = true;
			} else {
				$error = $stmt->errorInfo (); // error for receipt
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql,
					"Request" => $request
			);
			Database::disconnect ();
			break;
		case "pay" :
			$pdo = Database::connect ();
			// Update sub total
			$sql = "UPDATE Receipts SET Status = " . ReceiptStatus::Completed . ", CheckOutEmpId = $request->CheckOutEmpId, CheckOutTime = NOW() WHERE Id = $request->ReceiptId";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Pay Receipt failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "Pay Receipt  successfully!";
				$status = true;
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql,
					"Request" => $request
			);

			Database::disconnect ();
			break;

		case "deleteOrderItem" :
			$pdo = Database::connect ();
			$sql = "DELETE FROM OrderItems WHERE Id = $request->OrderItemId";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Delete item " . $request->OrderItemId .  " failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "Delete item " . $request->OrderItemId .  " successfully!";
				$status = true;

				// Delete receipt if there is only one order item
				//if ($request->NumOfCurrentOrderItems == 1) {
					//$sql = "DELETE FROM Receipts WHERE Id = $request->ReceiptId";
					//$stmt = $pdo->prepare ( $sql );
					//$stmt->execute ();
				//} else {
					// Update sub total
				$sql = "UPDATE Receipts SET SubTotal = $request->SubTotal,
				Total = $request->SubTotal WHERE Id = $request->ReceiptId;";
				$stmt = $pdo->prepare ( $sql );
				$stmt->execute ();
				//}
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql,
					"Request" => $request
			);

			Database::disconnect ();
			break;

			case "changedesk" :
				$pdo = Database::connect ();
				// Update sub total
				$sql = "UPDATE Receipts SET Receipts.DeskNo=$request->Desk WHERE Receipts.Id = $request->ReId;";
				$stmt = $pdo->prepare ( $sql );
				$stmt->execute ();

				$status = false;
				$message = " change desk failure!";
				$error = array ();
				if ($stmt->errorCode () == 0) {
					$message = "change desk successfully!";
					$status = true;
				} else {
					$error = $stmt->errorInfo ();
				}

				$data = array (
						"Status" => $status,
						"Message" => $message,
						"Error" => $error,
						"SQL" => $sql,
						"Request" => $request
				);

				Database::disconnect ();
				break;
                        case "saveReceiptInfo" :
				$pdo = Database::connect ();
			$sql = "UPDATE Receipts SET Receipts.SubTotal = $request->SubTotal,
				Receipts.NumOfItems = $request->NumOfItems, Receipts.ExtraPaidPerItem = $request->ExtraPaidPerItem, Receipts.ExtraPaid = $request->ExtraPaid,
				Receipts.Total = $request->Total, Receipts.Note = '$request->Note'
				WHERE Receipts.Id = $request->ReceiptId;";

			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Update Receipt failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "Update Receipt  successfully!";
				$status = true;
			} else {
				$error = $stmt->errorInfo (); // error for receipt
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql
			);
			Database::disconnect ();
			break;
                        case "changeViewReceipt" :
				$pdo = Database::connect ();
			$sql = "UPDATE OrderItems SET OrderItems.Quantity = $request->Quantity,OrderItems.Amount=$request->Amount
				WHERE OrderItems.Id = $request->Id;";

			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();


			$status = false;
			$message = "Update Receipt failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "Update Receipt  successfully!";
				$status = true;
			} else {
				$error = $stmt->errorInfo (); // error for receipt
			}
			$sql = "UPDATE Receipts SET Receipts.Total = $request->Total WHERE Receipts.Id=$request->ReId;";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();


			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql
			);
			Database::disconnect ();
			break;
			case "ConnectDesks" :
			$pdo = Database::connect ();
			$sql = "UPDATE OrderItems SET OrderItems.ReceiptId = $request->ReIdNew WHERE OrderItems.ReceiptId=$request->ReIdOld;";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();
			$status = false;
			$message = "failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "successfully!";
				$status = true;

				$sql ="SELECT * FROM Receipts WHERE Receipts.Id=$request->ReIdOld";
				$stmt = $pdo->prepare ( $sql );
				$stmt->execute ();
				$SubTotal=0;
				$Total=0;
				$ExtraItem=0;
				$Extra=0;
                                $test=1;
				if($request->FromComp==0||$request->ToComp==0)
				{
					$test=0;
				}
				if ($stmt->errorCode () == 0)
				{
				  		while ( ($row = $stmt->fetch ()) != false )
				  		 {
				  		 	$SubTotal=$row['SubTotal'];
				  		 	$Total=$row['Total'];
				  		 	$ExtraItem=$row['ExtraPaidPerItem'];
				  		 	$Extra=$row['ExtraPaid'];
					     }
					     $sql="UPDATE Receipts SET Receipts.ExtraPaid=Receipts.ExtraPaid+$ExtraItem,Receipts.SubTotal=Receipts.SubTotal +$SubTotal,Receipts.Total=Receipts.Total+$Total,Receipts.IsCompletedServing=$test,Receipts.Note=CONCAT(Receipts.Note,', hóa đơn cộng thêm $Total từ bàn $request->FromDesk với g.chú:$request->Note') WHERE Id=$request->ReIdNew;";
					     $stmt = $pdo->prepare ( $sql );
				             $stmt->execute ();
			                     $sql="UPDATE Receipts SET Receipts.ExtraPaid=0,Receipts.ExtraPaidPerItem=0,Receipts.SubTotal=0,Receipts.Total=0,Receipts.Note=CONCAT(Receipts.Note,', $Total vnđ của hóa đơn này được tính sang bàn $request->Desk + ') WHERE Id=$request->ReIdOld;";
                                             $stmt = $pdo->prepare ( $sql );
				             $stmt->execute ();

				}
				else{
				 $Status=false;
				}
			}//}
			 else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql,
					"Request" => $request
			);

			Database::disconnect ();
			break;

			case "deleteReceipt" :
			$pdo = Database::connect ();
			$sql = "DELETE  FROM Receipts WHERE Receipts.Id=$request->ReceiptId;";

			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Delete Receipt failure!";
			$error = array ();

			if ($stmt->errorCode () == 0) {
				$message = "Delete Receipt  successfully!";
				$status = true;
			} else {
				$error = $stmt->errorInfo (); // error for receipt
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $error,
					"SQL" => $sql
			);
			Database::disconnect ();
			break;

		default :
			$data = array (
					"Status" => false,
					"Error" => "Wrong request"
			);
			break;
	}
} else {
	$data = array (
			"Status" => false,
			"Message" => "Wrong username or password"
	);
}

/* Try code here */

echo json_encode ( $data );
?>