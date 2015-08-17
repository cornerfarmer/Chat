<?php	
	$message = $_POST["message"];
	$group = ($_POST["group"] === "true" ? 1 : 0);
	$id = $_POST["id"];
	
	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

	if ($_FILES["file"] !== NULL)
	{
		$filename = "./media/" . (string)sha1_file($_FILES['file']['tmp_name']) . ".png";
		move_uploaded_file($_FILES['file']['tmp_name'], $filename);
		
		$stmt = $mysqli->prepare("INSERT INTO resources (type, path, thumbnail_path) VALUES ('picture', ?, ?)");	
		$stmt->bind_param("ss", $filename, $filename);
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
	 session_start();
	$_SESSION["messagesToSend"] = true;
?>