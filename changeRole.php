<?php	
    require_once(__DIR__.'/include/sqAES.php');
    $theKey = "test";

	$group = sqAES::decrypt($theKey, $_POST["group"]);
    $member = sqAES::decrypt($theKey, $_POST["member"]);
	$admin = sqAES::decrypt($theKey, $_POST["admin"]);
    if (!$group)
        die();

	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

    $stmt = $mysqli->prepare("UPDATE groups_contacts_join SET admin=? WHERE group_id=? AND contact_id=?");	
    $admin_nmb = $admin == "1" ? 3 : 2;
    $stmt->bind_param("iss", $admin_nmb, $group, $member);
	$stmt->execute();

	session_start();
	$_SESSION["rolesToChange"] = true;
?>