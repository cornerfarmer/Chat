<?php	
    require_once(__DIR__.'/include/sqAES.php');
    $theKey = "test";

	$group = sqAES::decrypt($theKey, $_POST["group"]);
    $member = sqAES::decrypt($theKey, $_POST["member"]);
	if (!$group)
        die();


	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

    $stmt = $mysqli->prepare("INSERT INTO groups_contacts_join (group_id, contact_id, new) VALUES (?, ?, 1)");	
    $stmt->bind_param("ss", $group, $member);
	$stmt->execute();

	session_start();
	$_SESSION["membersToAdd"] = true;
?>