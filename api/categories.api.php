<?php
require 'config.php';

if ($IsLoggedIn) {
	switch ($request->Command) {
		case "getCategories" :
			$pdo = Database::connect ();
			$sql = "SELECT * FROM Categories ORDER BY Id ASC";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Get Categories failure!";
			$categories = array ();
			$error = array ();
			if ($stmt->errorCode () == 0) {
				$message = "Get Categories  successfully!";
				$status = true;
				$index = - 1;
				while ( ($row = $stmt->fetch ()) != false ) {
					$index ++;
					$category = array (
							"IndexInArr" => $index,
							"Id" => $row ['Id'],
							"Name" => $row ['Name'],
							"Image" => $row ["Image"]
					);
					$categories [] = $category;
				}
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"Categories" => $categories,
					"Error" => $error,
					"SQL" => $sql,
			);
			Database::disconnect ();
			break;
		case "getProductsOfCategory" :
			$pdo = Database::connect ();
			$sql = "SELECT * FROM Products WHERE CategoryId=$request->Id ORDER BY IsFavorite DESC, Id ASC";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Get Products Of Category failure!";
			$productsofcategory = array ();
			$error = array ();
			if ($stmt->errorCode () == 0) {
				$message = "Get Products of Category successfully!";
				$status = true;
				$index = - 1;
				while ( ($row = $stmt->fetch ()) != false ) {
					$index ++;
					$product = array (
							"IndexInArr" => $index,
							"Id" => $row ['Id'],
							"Name" => $row ["Name"],
							"Price" => $row ["Price"],
							"CategoryId" => $row ["CategoryId"],
							"IsFavorite" => $row ["IsFavorite"],
							"Image" => $row ["Image"]);
					$productsofcategory [] = $product;
				}
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"ProductsOfCategory" => $productsofcategory,
					"Error" => $error,
					"SQL" => $sql,
			);
			Database::disconnect ();
			break;

		case "editCategory" :

			$pdo = Database::connect ();
			$sql = "UPDATE Categories SET
   				Name = '$request->Name',
   				Image = '$request->Image'
				WHERE Id = $request->Id;";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Update Category failure!";
			$error = array ();
			if ($stmt->errorCode () == 0) {
				$message = "Update Category  successfully!";
				$status = true;
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"SQL" => $sql,
					"Error" => $error,
			);
			Database::disconnect ();
			break;

		case "newCategory" :

			$pdo = Database::connect ();
			$sql = "INSERT INTO Categories(Name, Image)
    					VALUES('$request->Name','$request->Image');";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "New Categories failure!";
			$error = array ();
			if ($stmt->errorCode () == 0) {
				$message = "New Categories  successfully!";
				$status = true;
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"SQL" => $sql,
					"Error" => $error,
			);
			Database::disconnect ();
			break;

		case "deleteCategory" :

			$pdo = Database::connect ();
			$sql = "DELETE FROM Categories WHERE Id=$request->Id;";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();
			$status = false;
			$message = "Delete Categories failure!";
			$error = array ();
			if ($stmt->errorCode () == 0) {
				$message = "Delete Categories  successfully!";
				$status = true;
			} else {
				$error = $stmt->errorInfo ();
			}

			$data = array (
					"Status" => $status,
					"Message" => $message,
					"SQL" => $sql,
					"Error" => $error,
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
