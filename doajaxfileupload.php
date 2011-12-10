<?php
	include_once('classes/Shell.class');

	$error = "";
	$msg = "";
	$fileParam = 'fileToUpload';
	
	function noOutputErrorHandler($errno, $errstr, $errfile, $errline) {
		// block error output
	}
	
	set_error_handler("noOutputErrorHandler");
	
	if(!empty($_FILES[$fileParam]['error'])) {
		switch($_FILES[$fileParam]['error']) {
			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;
			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	} elseif(empty($_FILES[$fileParam]['tmp_name']) || $_FILES[$fileParam]['tmp_name'] == 'none') {
		$error = 'No file was uploaded..';
	} else {
		$tempFile = $_FILES[$fileParam]['tmp_name'];
		$targetDir = Shell::cwd();
		$targetFile = $targetDir . '/' . $_FILES[$fileParam]['name']; 
		if (!move_uploaded_file($tempFile, $targetFile)) {
			$error .= "Cannot save file to " . $targetDir;
		} else {
			$msg .= "Uploaded " . $_FILES[$fileParam]['name'] . "(" . round(@filesize($targetFile) / 1024, 2) . "KB) to " . $targetDir;
			//for security reason, we force to remove all uploaded file
			@unlink($_FILES[$fileParam]);
		}
	}
	
	echo "{";
	echo	"error: '" . $error . "',\n";
	echo	"msg: '" . $msg . "'\n";
	echo "}";

	restore_error_handler();
?>