<?php
require 'config.php';

if ($IsLoggedIn) {
	switch ($request->Command) {
		case "getProducts" :
			$pdo = Database::connect ();
			$sql = "SELECT Products.*, Categories.Name as CategoryName
					FROM Products
					INNER JOIN Categories ON Products.CategoryId = Categories.Id
					ORDER BY IsFavorite DESC, Id ASC";
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
							"Image" => $row ["Image"],
							"CategoryId" => $row ["CategoryId"],
							"CategoryName" => $row ["CategoryName"],

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
					"SQL" => $sql,
			);
			Database::disconnect ();
			break;

		case "editProduct" :

			$pdo = Database::connect ();
			$sql = "UPDATE Products SET
   				Name = '$request->Name', Price = $request->Price,
   				CategoryId = $request->CategoryId,
   				IsFavorite = $request->IsFavorite, Image = '$request->Image'
				WHERE Id = $request->Id;";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Update Product failure!";
			$error = array ();
			if ($stmt->errorCode () == 0) {
				$message = "Update Product  successfully!";
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

			case "setIsFavorite":

				$pdo = Database::connect ();
				$sql = "UPDATE Products SET
	   				IsFavorite = $request->IsFavorite
					WHERE Id = $request->Id;";
				$stmt = $pdo->prepare ( $sql );
				$stmt->execute ();

				$status = false;
				$message = "set IsFavorite failure!";
				$error = array ();
				if ($stmt->errorCode () == 0) {
					$message = "set IsFavorite  successfully!";
					$status = true;
				} else {
					$error = $stmt->errorInfo ();
				}

				$data = array (
						"Status" => $status,
						"Message" => $message,
						"SQL" => $sql,
						"Error" => $error,
						"Request" => $request,
				);
				Database::disconnect ();
				break;

		case "newProduct" :

			$pdo = Database::connect ();
			$sql = "INSERT INTO Products(Name, Price, CategoryId, IsFavorite, Image)
    					VALUES('$request->Name', $request->Price, $request->CategoryId,
    					$request->IsFavorite, '$request->Image');";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "New Products failure!";
			$error = array ();
			if ($stmt->errorCode () == 0) {
				$message = "New Products  successfully!";
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

		case "deleteProduct" :

			$pdo = Database::connect ();
			$sql = "DELETE FROM Products WHERE Id=$request->Id;";
			$stmt = $pdo->prepare ( $sql );
			$stmt->execute ();

			$status = false;
			$message = "Delete Products failure!";
			$error = array ();
			if ($stmt->errorCode () == 0) {
				$message = "Delete Products  successfully!";
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
