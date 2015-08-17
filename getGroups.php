<?php
	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

	$res = $mysqli->query("SELECT * FROM groups");	
	
	$xml = new SimpleXMLElement('<xml/>');
	
	foreach ($res as $row)
	{
		$group = $xml->addChild('Group');
		$group->addChild('Name', $row["name"]);
		$group->addChild('ID', $row["id"]);
	}
	
	Header('Content-type: text/xml');
	print($xml->asXML());
?>