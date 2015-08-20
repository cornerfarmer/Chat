<?php
    $xml = new SimpleXMLElement('<xml/>');

	$message = $_REQUEST["message"];

	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");
    session_start();
    
    // CONTACTS
       
	$stmt = $mysqli->prepare("SELECT * FROM contacts Left join resources on contacts.picture = resources.resource_id WHERE UNIX_TIMESTAMP(lastModified) > ?");	
    $stmt->bind_param("i", $_REQUEST["lastCheckTime"]);
	$stmt->execute(); 
	$res = $stmt->get_result(); 

	foreach ($res as $row)
	{
		$contact = $xml->addChild('Contact');
		$contact->addChild('Name', $row["name"]);
		$contact->addChild('Number', $row["number"]);
		$contact->addChild('ID', $row["id"]);
        $contact->addChild('LastSeen', ($row["lastSeen"] == 0 ? "Online" : date("j. M H:i", $row["lastSeen"])));
        $contact->addChild('Status', $row["status"]);
		if ($row["picture"] !== NULL)
		{
			$resource = $contact->addChild('Resource');
			$resource->addChild('ID', $row["resource_id"]);
			$resource->addChild('Path', $row["path"]);
			$resource->addChild('ThumbnailPath', $row["thumbnail_path"]);
		}
	}

    // GROUPS

    $stmt = $mysqli->prepare("SELECT * FROM groups WHERE UNIX_TIMESTAMP(lastModified) > ?");	
    $stmt->bind_param("i", $_REQUEST["lastCheckTime"]);
	$stmt->execute(); 
	$res = $stmt->get_result(); 
		
	foreach ($res as $row)
	{
		$group = $xml->addChild('Group');
		$group->addChild('Name', $row["name"]);
		$group->addChild('ID', $row["id"]);
	}


    // MESSAGES
    	
	$stmt = $mysqli->prepare("SELECT * FROM messages Left join resources on messages.resource = resources.resource_id WHERE UNIX_TIMESTAMP(lastModified) > ?");	
	$stmt->bind_param("i", $_REQUEST["lastCheckTime"]);
	$stmt->execute(); 
	$res = $stmt->get_result(); 	 
	
	foreach ($res as $row)
	{	
        if (!isset($_SESSION["messages"][$row["id"]]) || $_SESSION["messages"][$row["id"]]["new"])
        {
            $message = $xml->addChild('Message');      
		    $message->addChild('Text', $row["text"]);
		    $message->addChild('ID', $row["intern_id"]);
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
            $news["messages"][$row["id"]]["new"] = false;
        }
        else if ($_SESSION["messages"][$row["id"]]["status"])
        {
            $message = $xml->addChild('MessageStatus');   
            $message->addChild('ID', $row["intern_id"]);   
            $message->addChild('Status', $row["status"]);
            $news["messages"][$row["id"]]["status"] = false;
        }
        else
        {
             $message = $xml->addChild('Missing'); 
        }
	}

    $xml->addChild('LastCheckTime', time());
	
	Header('Content-type: text/xml');
	print($xml->asXML());
?>
