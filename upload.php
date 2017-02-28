<?php
	
	$dir = $_POST['dir']; //Lấy đường dẫn chứa hình ảnh
	$target_dir = dir(getcwd ())->path . '/img/'.$dir;

	//Xóa file cũ
	$oldImage = $_POST['oldImage'];
	$indexCate = -1;
	if(stripos($oldImage, "categorie") !== false)
		$indexCate = 1;
	$daXoa = '0';
	if(file_exists('img/'.$oldImage)){
		if(strcmp($dir,"product/") != 0){
			unlink('img/'.$oldImage);
			$daXoa = '1';
		}
		if(strcmp($dir,"product/") == 0 && $indexCate < 0){
			
			unlink('img/'.$oldImage);
			$daXoa = '2';
		} 
	}

	//Tạo ra tên mới
	$nameImage = $_POST['nameImage'];
	$str = strtolower($nameImage);
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
	$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
	$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
	$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
	$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
	$str = preg_replace("/(đ)/", 'd', $str);
	$str = str_replace(' ', '-', $str); // Replaces all spaces with hyphens.
	$str = preg_replace('/[^A-Za-z0-9\-]/', '', $str); // Removes special chars.
	$str =  preg_replace('/-+/', '-', $str);
	$date = date_create(); 
	$nowStamp = date_timestamp_get($date);
	$str .= '_'.$nowStamp;
	//Lấy phần mở rộng của file.
	$imageFileName = basename($_FILES["fileToUpload"]["name"]);
	$imageFileType = pathinfo ( $imageFileName, PATHINFO_EXTENSION );

	$str .= '.'. $imageFileType;

	$target_file = $target_dir . $str;
	$status = true;
	$message = "";
	$imageFileType = pathinfo ( $target_file, PATHINFO_EXTENSION );

	// Check if image file is a actual image or fake image
	if (isset ( $_POST ["submit"] )) {
		$check = getimagesize ( $_FILES ["fileToUpload"] ["tmp_name"] );
		if ($check !== false) {
			$message .=  " - File is an image - " . $check ["mime"] . ".";
			$status = true;
		} else {
			$message .=  " - File is not an image.";
			$status = false;
		}
	}
//	// Check if file already exists
//	if (file_exists ( $target_file )) {
//		unlink($target_file);
//		//$message .=  " - File already exists.";
//		//$status = false;
//	}
	// Check file size
	if ($_FILES ["fileToUpload"] ["size"] > 500000) {
		$message .=  " - File is too large.";
		$status = false;
	}
	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		$message .=  " - Only JPG, JPEG, PNG & GIF files are allowed.";
		$status = false;
	}
	// Check if $status is set to false by an error
	if ($status == false) {
		$message .=  " - File was not uploaded.";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file ( $_FILES ["fileToUpload"] ["tmp_name"], $target_file )) {
			$message .=  "- The file " . basename ( $_FILES ["fileToUpload"] ["name"] ) . " has been uploaded.";
		} else {
			$message .=  "- There was an error uploading your file.";
		}
	}

	$data = array (
			"Status" => $status,
			"FileName" => $dir.$str, 
			"Message" => $message
	);

	echo json_encode ( $data );
?>