<?php	
    require_once(__DIR__.'/include/sqAES.php');
	session_start();
    $theKey = "test";
    $id = sqAES::decrypt($theKey, $_POST["id"]);
    $sender_id = sqAES::decrypt($theKey, $_POST["sender_id"]);
    $group_id = sqAES::decrypt($theKey, $_POST["group_id"]);

    if (!$id)
        die();

	array_push($_SESSION["messagesToSetAsRead"], 
                array("id" => $id, 
                      "sender_id" => $sender_id, 
                      "group_id" => $group_id)
                );
?>