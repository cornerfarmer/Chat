<?php
	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

	$res = $mysqli->query("SELECT * FROM contacts Left join resources on contacts.picture = resources.resource_id");	
	
	$xml = new SimpleXMLElement('<xml/>');

	foreach ($res as $row)
	{
		$contact = $xml->addChild('Contact');
		$contact->addChild('Name', $row["name"]);
		$contact->addChild('Number', $row["number"]);
		$contact->addChild('ID', $row["id"]);
        $contact->addChild('LastSeen', ($row["lastSeen"] == 0 ? "Online" : date("j. M H:i", $row["lastSeen"]) . " Uhr"));
		if ($row["picture"] !== NULL)
		{
			$resource = $contact->addChild('Resource');
			$resource->addChild('ID', $row["resource_id"]);
			$resource->addChild('Path', $row["path"]);
			$resource->addChild('ThumbnailPath', $row["thumbnail_path"]);
		}
	}
	
	Header('Content-type: text/xml');
	print($xml->asXML());
?>