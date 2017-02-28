<?php

// Get post request from client
$postData = file_get_contents ( "php://input" );
$request = json_decode ( $postData );

require 'config.php'; // Read config variables

if ($IsLoggedIn) {
	switch ($request->Command) {
		case "getSettings" :
			$data = array (
					"Status" => true,
					"Message" => "Get settings successful!",
					"Settings" => array (
							"NumOfDesks" => $row ["NumOfDesks"],
							"Name" => $row ["Name"],
							"Owner" => $row ["Owner"],
							"Description" => $row ["Description"],
							"Address" => $row ["Address"],
							"Phone" => $row ["Phone"],
							"Email" => $row ["Email"],
							"SendReport" => $row ["SendReport"],
							"ExtraPaidPerItem" =>  $row ["ExtraPaidPerItem"],
					),
					"Request" => $request
			);
			break;

		case "saveSettings" :

			$pdo = Database::connect ();
			$settings = $request->Settings;
			$sql = "UPDATE Settings SET
				NumOfDesks = $settings->NumOfDesks,
				ExtraPaidPerItem = $settings->ExtraPaidPerItem,
				Name = '$settings->Name',
				Address = '$settings->Address',
				Phone = '$settings->Phone',
				Email = '$settings->Email',
				SendReport = $settings->SendReport

			 	WHERE Id=1;";
			$result = $pdo->exec ( $sql );

			$status = false;
			$message = "Update settings failure!";

			$error = $pdo->errorInfo ();
			if ($error [0] == '00000') {
				$message = "Update settings successfully!";
				$status = true;
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

		case "sentReport" :
			require './mail/PHPMailerAutoload.php';
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->SMTPDebug = 2;
			$mail->Debugoutput = 'html';
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = 587;
			$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;
			$mail->Username = "report.jcafe@gmail.com";
			$mail->Password = "1234$#@!";
			$mail->setFrom('report.jcafe@gmail.com', 'Cafe Paris Night');
                        $mail->addAddress('dangthanhtuanit@gmail.com', 'Tuan Dang');
			$mail->addAddress('pdudctnguyen@gmail.com', 'Nguyen Nguyen');
			$mail->Subject = 'jCafe mail system test';

			//$content = file_get_contents('http://www.gocnhinalan.com/bai-cua-khach/vi-sao-nguoi-thai-gioi-lam-kinh-te-hoc-gi-tu-ho.html');
                       
			require_once 'report.mail.php';
                        $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");

			$mail->msgHTML($content);
			if (!$mail->send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				echo "Message sent!";
			}
			break;

		default :
			$data = array (
					"Error" => "Wrong request"
			);
			break;
	}
} else {
	$data = array (
			"Status" => "Error",
			"Message" => "Wrong username or password"
	);
}

/* Try code here */

echo json_encode ( $data );

?>
