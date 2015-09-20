<?php	
    require_once(__DIR__.'/include/sqAES.php');
    $theKey = "test";

	$number = sqAES::decrypt($theKey, $_POST["number"]);
	
	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

    $stmt = $mysqli->prepare("INSERT INTO contacts (id, name, picture, lastSeen, status, lastModified) VALUES (?, '', 0, 0, '', 0) ");
    $stmt->bind_param("s", $number);
	$stmt->execute();

	session_start();
	$_SESSION["contactsToAdd"] = true;
?>