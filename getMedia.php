<?php	
    require_once(__DIR__.'/include/sqAES.php');
    $theKey = "test";
     
	$id = $_GET["id"];

    if (strpos($id, "/"))
        $id = substr($id, strrpos($id, "/") + 1);
    $extension = substr($id, strrpos($id, ".") + 1);


    header("Cache-Control: max-age=2592000"); 

    $data = file_get_contents("media/$id");
	$data = base64_encode($data);
    if (in_array($extension, array('jpg', 'jpeg', 'gif', 'png'))) {
		$type = 'image';
    } else if (in_array($extension, array('3gp', 'mp4', 'mov', 'avi'))) {
		$type = 'video';
    } else if (in_array($extension, array('3gp', 'caf', 'wav', 'mp3', 'wma', 'ogg', 'aif', 'aac', 'm4a'))) {
		$type = 'audio';
    }
    echo sqAES::crypt($theKey, "data:$type/$extension;base64,$data");	
?>