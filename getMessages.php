<?php
	$message = $_REQUEST["message"];

	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

	$stmt = $mysqli->prepare("SELECT * FROM messages Left join resources on messages.resource = resources.resource_id WHERE time > ? AND status!=0");	
	$stmt->bind_param("i", $_REQUEST["newestMessageTime"]);
	$stmt->execute(); 
	$res = $stmt->get_result(); 
		
	$xml = new SimpleXMLElement('<xml/>');
	
	foreach ($res as $row)
	{
		$message = $xml->addChild('Message');
		$message->addChild('Text', $row["text"]);
		$message->addChild('ID', $row["id"]);
		$message->addChild('Sender', $row["sender_id"]);
		$message->addChild('ChatID', $row["chat_id"]);
		$message->addChild('Status', $row["status"]);
		$message->addChild('Time', date("j. M H:i", $row["time"]) . " Uhr");
        $message->addChild('Timestamp', $row["time"]);
		if ($row["isGroup"] === 1)			
			$message->addChild('Type', "group");
		else
			$message->addChild('Type', "single");
		if ($row["resource"] !== NULL)
		{
			$resource = $message->addChild('Resource');
			$resource->addChild('Type', $row["type"]);
			$resource->addChild('Path', $row["path"]);
			$resource->addChild('ThumbnailPath', $row["thumbnail_path"]);
		}
	}
	
	Header('Content-type: text/xml');
	print($xml->asXML());
?>