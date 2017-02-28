<?php
require_once 'login.api.php';
require_once 'type.php';

$settings = array ();

if ($IsLoggedIn) {

	$pdo = Database::connect ();
	$sql = "SELECT * FROM Settings";

	$rows = $pdo->query ( $sql );
	foreach ( $rows as $row ) {
		$settings = array (
				"NumOfDesks" => $row ["NumOfDesks"],
				"ExtraPaidPerItem" =>  $row ["ExtraPaidPerItem"],
				"Name" => $row ["Name"],
				"Owner" => $row ["Owner"],
				"Description" => $row ["Description"],
				"Address" => $row ["Address"],
				"Phone" => $row ["Phone"],
				"Email" => $row ["Email"],
				"Password" => $row ["Password"],
				"SendReport" => $row ["SendReport"]
		);
		break;
	}
} else {
	$pdo = Database::connect ();
	$sql = "SELECT * FROM Settings";

	$rows = $pdo->query ( $sql );
	foreach ( $rows as $row ) {
		$settings = array (
				"NumOfDesks" => $row ["NumOfDesks"],
				"ExtraPaidPerItem" =>  $row ["ExtraPaidPerItem"],
				"Name" => $row ["Name"],
				"Owner" => $row ["Owner"],
				"Description" => $row ["Description"],
				"AccountInfo" => $row ["AccountInfo"],
				"TaxCode" => $row ["TaxCode"],
				"Address" => $row ["Address"],
				"Phone" => $row ["Phone"],
				"Fax" => $row ["Fax"],
				"EmailAddress" => $row ["EmailAddress"]
		);
		break;
	}
}

Database::disconnect ();
?>
