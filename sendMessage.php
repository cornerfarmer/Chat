<?php	
    require_once(__DIR__.'/include/sqAES.php');
    $theKey = "test";
    session_start();
    $id = sqAES::decrypt($theKey, $_POST["id"]);
	$message = sqAES::decrypt($theKey, $_POST["message"]);
	$group = (int)sqAES::decrypt($theKey, $_POST["group"]);
	
	if (!$id)
    {
        echo "not valid" . $id . "," . $_POST["id"];
        die();
    }

	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

	if ($_POST["file"] !== NULL)
	{
        $data = sqAES::decrypt($theKey, $_POST["file"]);
		list($type, $data) = explode(';', $data);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);

        $orgFilename = sqAES::decrypt($theKey, $_POST["filename"]);
        $extension = substr($orgFilename, strrpos($orgFilename, ".") + 1);
		$filename = "./media/" . (string)sha1($data) . ".$extension";
        file_put_contents($filename, $data);
        
        if (in_array($extension, array('jpg', 'jpeg', 'gif', 'png'))) {
			$type = 'picture';
        } else if (in_array($extension, array('3gp', 'mp4', 'mov', 'avi'))) {
			$type = 'video';
        } else if (in_array($extension, array('3gp', 'caf', 'wav', 'mp3', 'wma', 'ogg', 'aif', 'aac', 'm4a'))) {
			$type = 'audio';
        } else {
            trigger_error ("Given resource is not valid");
            die();
        }
		
		$stmt = $mysqli->prepare("INSERT INTO resources (type, path, thumbnail_path) VALUES (?, ?, ?)");	
		$stmt->bind_param("sss", $type, $filename, $type === 'picture' ? $filename : "media/404.jpg");
		$stmt->execute();
		
		$resourceID = $mysqli->insert_id;
		
		$stmt = $mysqli->prepare("INSERT INTO messages (text, sender_id, chat_id, isGroup, resource) VALUES (?, 0, ?, ?, ?)");	
		$stmt->bind_param("siii", $message, $id, $group, $resourceID);
	}
	else
	{
		$stmt = $mysqli->prepare("INSERT INTO messages (text, sender_id, chat_id, isGroup) VALUES (?, 0, ?, ?)");	
		$stmt->bind_param("sii", $message, $id, $group);
	}
	
	$stmt->execute();
    $messageID = $mysqli->insert_id;
	$_SESSION["messagesToSend"] = true;
    $_SESSION["messages"][$messageID]["new"] = true;
?>