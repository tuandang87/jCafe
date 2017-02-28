<?php
header("Access-Control-Allow-Origin: *");

// Get post request from client
$postData = file_get_contents ( "php://input" );
$request = json_decode ( $postData );

require_once 'database.php';
date_default_timezone_set ( "Asia/Bangkok" );

$pdo = Database::connect ();

$password = $request->Password;
if (strlen($request->Password) == 6)
	$password = md5 ($request->Password );

$sql = "SELECT * FROM Employees WHERE Username='$request->Username' and Password='$password'";
$IsLoggedIn = false;

$LoggedEmployee = array();

$stmt = $pdo->prepare ( $sql );
$stmt->execute ();

$status = false;
$message = "Login failure!";

$error = array ();
if ($stmt->errorCode () == 0) {
	while ( ($row = $stmt->fetch ()) != false ) {
		$message = "Login  successfully!";
		$status = true;
		$IsLoggedIn = true;
		$LoggedEmployee = array(
			"Id" =>	$row ["Id"],
			"AccessLevel" => $row ["AccessLevel"],
			"RegionNo" => $row ["RegionNo"],
			"Username" => $row ["Username"],
			"Password" => $row["Password"],
		);
		break;
	}
}
else {
	$error = $stmt->errorInfo ();
}

switch ($request->Command) {
	case "login" :
		$data = array (
				"Status" => $status,
				"Message" => $message,
				"LoggedEmployee" => $LoggedEmployee,
				"Error" => $error,
				"SQL" => $sql,
		);
		echo json_encode ($data );
		break;
	default:
		break;
}
Database::disconnect ();


?>
