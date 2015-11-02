<?php	
    require_once(__DIR__.'/include/sqAES.php');
    $theKey = "test";

	$group = sqAES::decrypt($theKey, $_POST["group"]);
    $member = sqAES::decrypt($theKey, $_POST["member"]);
	if (!$group)
        die();


	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

    $stmt = $mysqli->prepare("UPDATE groups_contacts_join SET remove=1 WHERE group_id=? AND contact_id=? ");
    $stmt->bind_param("ss", $group, $member);
	$stmt->execute();

	session_start();
	$_SESSION["membersToRemove"] = true;
?>