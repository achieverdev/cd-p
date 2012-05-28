<?php

$tempFile = $_FILES['Filedata']['tmp_name'];
$timename = time() + (7 * 24 * 60 * 60);
$fileName = $timename.$_FILES['Filedata']['name'];
$fileSize = $_FILES['Filedata']['size'];

 $success = move_uploaded_file($tempFile, "./img/" . $fileName);
if( $success )
{
	echo 'tdesignstudio/php/img/'.$fileName;
}
else
{
	echo 'false';
}

?>