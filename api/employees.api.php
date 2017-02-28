<?php

require 'config.php';

if ($IsLoggedIn) {
	switch ($request->Command) {
		case "getEmployees" :

			$pdo = Database::connect ();
			$sql = "SELECT * FROM Employees;";
			if($request->AccessLevel == 3){
				$sql = "SELECT * FROM Employees WHERE Username='$request->Username';";
			}
			$data = array ();
			$index = - 1;
			foreach ( $pdo->query ( $sql ) as $row ) {
				$index ++;
				$employee = array (
						"Id" => $row ['Id'],
						"IndexInArr" => $index,
						"Username" => $row ["Username"],
						"Password" => $row ['Password'],
						"AccessLevel" => $row ['AccessLevel'],
						"RegionNo" => $row ['RegionNo'],
						"FirstName" => $row ["FirstName"],
						"LastName" => $row ["LastName"],
						"Image" => $row ["Image"],
						"Email" => $row ["Email"],
						"Phone" => $row ["Phone"],
						"Birthday" => $row ["Birthday"],
						"Address" => $row ["Address"],
				);
				$data [] = $employee;
			}
			$data = array (
					"Status" => true,
					"Message" => "Get all Employees successfully",
					"Employees" => $data
			);
			Database::disconnect ();
			break;

		case "editEmployee" :

			$birthday = $request->Birthday;
// 			if ($birthday < 0){
// 				$data = array (
// 						"Status" => false,
// 						"Message" => "Birthday wrong format",
// 						"Request" => $request
// 				);
// 				break;
// 			}
			$pdo = Database::connect ();

			$password = $request->EPassword;
			if (strlen($request->EPassword) == 6)
				$password = md5 ($request->EPassword );
			else if (strlen($request->EPassword) < 6)
				$password = md5 ('123456');
			$sql = "UPDATE Employees SET Username = '$request->EUsername', Password = '$password',
				AccessLevel = $request->AccessLevel, RegionNo = $request->RegionNo, 
				FirstName = '$request->FirstName',
				LastName = '$request->LastName', Image = '$request->Image', Email = '$request->Email',
				Phone = '$request->Phone', Birthday = '$request->Birthday', Address = '$request->Address'
				WHERE Id = $request->EmployeeId;";
			$result = $pdo->exec ( $sql );

			$message = "Edit Employee " . $request->Username . " failure!";
			$status = false;
			if ($result == 1) {
				$message = "Edit Employee " . $request->Username . " successfully!";
				$status = true;
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $pdo->errorInfo (),
					"SQL" => $sql,
					"Request" => $request
			);

			Database::disconnect ();
			break;

		case "newEmployee":
			$pdo = Database::connect ();
			$birthday = strtotime($request->Birthday);
			if ($birthday < 0){
				$data = array (
						"Status" => false,
						"Message" => "Birthday wrong format",
						"Request" => $request
				);
				break;
			}

			$password = $request->EPassword;
			if (strlen($request->EPassword) == 6)
				$password = md5 ($request->EPassword );
			else if (strlen($request->EPassword) < 6)
				$password = md5 ('123456');

			$sql = "INSERT INTO Employees(Username, Password, AccessLevel, RegionNo,
						FirstName, LastName, Image, Email, Phone, Birthday, Address)
    					VALUES('$request->EUsername', '$password', $request->AccessLevel, $request->RegionNo,
    						'$request->FirstName', '$request->LastName', '$request->Image',
    						'$request->Email', '$request->Phone', '$request->Birthday', '$request->Address');";
			$result = $pdo->exec ( $sql );
			Database::disconnect ();
			$message = "Create new Employee  failure!";

			$status = false;
			if ($result == 1) {
				$message = "Create new Employee successfully!";
				$status = true;
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $pdo->errorInfo (),
					"SQL" => $sql,
					"Request" => $request
			);
			break;

		case "deleteEmployee" :

			$pdo = Database::connect ();
			$sql = "DELETE FROM Employees WHERE Id=$request->Id;";
			$result = $pdo->exec ( $sql );
			Database::disconnect ();
			$message = "Delete Employee " . $request->EUsername . " failure!";

			$status = false;
			if ($result == 1) {
				$message = "Delete Employee " . $request->EUsername . " successfully!";
				$status = true;
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Error" => $pdo->errorInfo(),
					"SQL" => $sql,
					"Request" => $request
			);
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
