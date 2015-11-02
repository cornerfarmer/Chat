<?php	
    require_once(__DIR__.'/include/sqAES.php');
    $theKey = "test";
	$name = sqAES::decrypt($theKey, $_POST["name"]);
    $member = sqAES::decrypt($theKey, $_POST["member"]);
	if (!$name)
        die();

	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

	if ($_POST["file"] !== NULL)
	{
        $data = sqAES::decrypt($theKey, $_POST["file"]);
		list($type, $data) = explode(';', $data);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);

        $extension = substr($type, strrpos($type, "/") + 1);
		$filename = "./media/" . (string)sha1($data) . ".$extension";
        file_put_contents($filename, $data);
        
        if (in_array($extension, array('jpg', 'jpeg', 'gif', 'png'))) {
			$type = 'picture';
        } else {
            trigger_error ("Given resource is not valid");
        }
		
		$stmt = $mysqli->prepare("INSERT INTO resources (type, path, thumbnail_path) VALUES (?, ?, ?)");	
		$stmt->bind_param("sss", $type, $filename, $filename);
		$stmt->execute();
		
		$resourceID = $mysqli->insert_id;
	}
	else
	{
		$resourceID = 0;
	}

	$stmt = $mysqli->prepare("INSERT INTO groups (id, name, picture) VALUES ('0', ?, ?)");	
    $stmt->bind_param("si", $name, $resourceID);
	$stmt->execute();

    $groupID = $mysqli->insert_id;

    $stmt = $mysqli->prepare("INSERT INTO groups_contacts_join (group_id, contact_id) VALUES (?, ?)");	
    $stmt->bind_param("ss", $groupID, $member);
	$stmt->execute();

	session_start();
	$_SESSION["groupsToCreate"] = true;
?>