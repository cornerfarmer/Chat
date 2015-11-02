<?php
    require_once(__DIR__.'/include/sqAES.php');
    $xml = new SimpleXMLElement('<xml/>');
    $theKey = "test";
	$message = $_REQUEST["message"];
    $lastCheckTime = $_REQUEST["lastCheckTime"];
	$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");
    session_start();
    
    // CONTACTS
       
	$stmt = $mysqli->prepare("SELECT * FROM contacts Left join resources on contacts.picture = resources.resource_id WHERE UNIX_TIMESTAMP(lastModified) >= ? ORDER BY contacts.lastUsed ASC");	
    $stmt->bind_param("i", $lastCheckTime);
	$stmt->execute();   
    $xml->addChild('LastCheckTime', time());
	$res = $stmt->get_result(); 
    
	foreach ($res as $row)
	{
        if (!isset($_SESSION["contacts"][$row["id"]]) || $_SESSION["contacts"][$row["id"]]["new"] || $lastCheckTime == -1)
        {
		    $contact = $xml->addChild('Contact');
		    $contact->addChild('Name', $row["name"]);
		    $contact->addChild('Number', $row["number"]);
		    $contact->addChild('ID', $row["id"]);
            $contact->addChild('LastSeen', ($row["lastSeen"] == 0 ? "Online" : ($row["lastSeen"] == -1 ? "Denied" : date("j. M H:i", $row["lastSeen"]))));
            $contact->addChild('Status', $row["status"]);
		    if ($row["picture"] !== NULL)
		    {
			    $resource = $contact->addChild('Resource');
			    $resource->addChild('ID', $row["resource_id"]);
			    $resource->addChild('Path', $row["path"]);
			    $resource->addChild('ThumbnailPath', $row["thumbnail_path"]);
                $resource->addChild('ThumbnailWidth', $row["thumbnail_width"]);
                $resource->addChild('ThumbnailHeight', $row["thumbnail_height"]);
		    }
            $_SESSION["contacts"][$row["id"]]["new"] = false;
        }
        else
        { 
            if ($_SESSION["contacts"][$row["id"]]["lastSeen"])
            {
                $contact = $xml->addChild('ContactLastSeen');   
                $contact->addChild('ID', $row["id"]);   
                $contact->addChild('LastSeen', ($row["lastSeen"] == 0 ? "Online" : ($row["lastSeen"] == -1 ? "Denied" : date("j. M H:i", $row["lastSeen"]))));
                $_SESSION["contacts"][$row["id"]]["lastSeen"] = false;
            }
            if ($_SESSION["contacts"][$row["id"]]["status"])
            {
                $contact = $xml->addChild('ContactStatus');   
                $contact->addChild('ID', $row["id"]);   
                $contact->addChild('Status', $row["status"]);
                $_SESSION["contacts"][$row["id"]]["status"] = false;
            }
            if ($_SESSION["contacts"][$row["id"]]["picture"])
            {
                $contact = $xml->addChild('ContactPicture');   
                $contact->addChild('ID', $row["id"]);   
                if ($row["picture"] !== NULL)
		        {
			        $resource = $contact->addChild('Resource');
			        $resource->addChild('ID', $row["resource_id"]);
			        $resource->addChild('Path', $row["path"]);
			        $resource->addChild('ThumbnailPath', $row["thumbnail_path"]);
                    $resource->addChild('ThumbnailWidth', $row["thumbnail_width"]);
                    $resource->addChild('ThumbnailHeight', $row["thumbnail_height"]);
		        }
                $_SESSION["contacts"][$row["id"]]["picture"] = false;
            }
            if ($_SESSION["contacts"][$row["id"]]["composing"])
            {
                $contact = $xml->addChild('ContactComposing');   
                $contact->addChild('ID', $row["id"]);   
                $_SESSION["contacts"][$row["id"]]["composing"] = false;
            }
            if ($_SESSION["contacts"][$row["id"]]["paused"])
            {
                $contact = $xml->addChild('ContactPaused');   
                $contact->addChild('ID', $row["id"]);   
                $_SESSION["contacts"][$row["id"]]["paused"] = false;
            }
        }
	}

    // GROUPS

    $stmt = $mysqli->prepare("SELECT  groups.*, resources.*, groups_contacts_join.contact_id, groups_contacts_join.admin FROM groups Left join groups_contacts_join on groups.id = groups_contacts_join.group_id Left join resources on groups.picture = resources.resource_id WHERE UNIX_TIMESTAMP(groups.lastModified) >= ? AND groups.id!='0' ORDER BY groups.lastUsed ASC, groups.id ");	
    $stmt->bind_param("i", $lastCheckTime);
	$stmt->execute(); 
	$res = $stmt->get_result();
    $mergedRes = array();
	foreach ($res as $row)
	{
        $mergedRes[$row["id"]][] = $row;
    }
    foreach ($mergedRes as $row)
    {
        if (!isset($_SESSION["groups"][$row[0]["id"]]) || $_SESSION["groups"][$row[0]["id"]]["new"] || $lastCheckTime == -1)
        {
		    $group = $xml->addChild('Group');
		    $group->addChild('Name', $row[0]["name"]);
		    $group->addChild('ID', $row[0]["id"]);
            if ($row[0]["picture"] !== NULL)
		    {
			    $resource = $group->addChild('Resource');
			    $resource->addChild('ID', $row[0]["resource_id"]);
			    $resource->addChild('Path', $row[0]["path"]);
			    $resource->addChild('ThumbnailPath', $row[0]["thumbnail_path"]);
                $resource->addChild('ThumbnailWidth', $row[0]["thumbnail_width"]);
                $resource->addChild('ThumbnailHeight', $row[0]["thumbnail_height"]);
		    }
            foreach ($row as $singleRow) 
            {
                $member =  $group->addChild('Member');
                $member->addChild('ID', $singleRow["contact_id"]);                
                $member->addChild('Admin', $singleRow["admin"]);         
            }
            $_SESSION["groups"][$row[0]["id"]]["new"] = false;
        }
        else
        {
            if ($_SESSION["groups"][$row[0]["id"]]["name"])
            {
                $group = $xml->addChild('GroupName');
		        $group->addChild('Name', $row[0]["name"]);
		        $group->addChild('ID', $row[0]["id"]);
                $_SESSION["groups"][$row[0]["id"]]["name"] = false;
            }
            if ($_SESSION["groups"][$row[0]["id"]]["picture"])
            {
                $group = $xml->addChild('GroupPicture');   
                $group->addChild('ID', $row[0]["id"]);   
                if ($row[0]["picture"] !== NULL)
		        {
			        $resource = $group->addChild('Resource');
			        $resource->addChild('ID', $row[0]["resource_id"]);
			        $resource->addChild('Path', $row[0]["path"]);
			        $resource->addChild('ThumbnailPath', $row[0]["thumbnail_path"]);
                    $resource->addChild('ThumbnailWidth', $row[0]["thumbnail_width"]);
                    $resource->addChild('ThumbnailHeight', $row[0]["thumbnail_height"]);
		        }
                $_SESSION["groups"][$row[0]["id"]]["picture"] = false;
            }
            if ($_SESSION["groups"][$row[0]["id"]]["members"])
            {
                $group = $xml->addChild('GroupMembers');
                $group->addChild('ID', $row[0]["id"]); 
		        foreach ($row as $singleRow) 
                {
                    $member =  $group->addChild('Member');
                    $member->addChild('ID', $singleRow["contact_id"]);
                    $member->addChild('Admin', $singleRow["admin"]);
                }
                $_SESSION["groups"][$row[0]["id"]]["members"] = false;
            }           
        }
	}


    // MESSAGES
    	
	$stmt = $mysqli->prepare("SELECT * FROM (SELECT * FROM messages Left join resources on messages.resource = resources.resource_id WHERE UNIX_TIMESTAMP(lastModified) >= ? ORDER BY time DESC LIMIT 50) AS result ORDER BY time ASC");	
	$stmt->bind_param("i", $lastCheckTime);
	$stmt->execute(); 
	$res = $stmt->get_result(); 
	foreach ($res as $row)
	{	
        if ((!isset($_SESSION["messages"][$row["intern_id"]]) && !isset($_SESSION["messages"][$row["id"]])) || $_SESSION["messages"][$row["intern_id"]]["new"] || $lastCheckTime == -1)
        {
            $message = $xml->addChild('Message');      
		    $message->addChild('Text', $row["text"]);
		    $message->addChild('ID', $row["intern_id"]);
            $message->addChild('WID', $row["id"]);
		    $message->addChild('Sender', $row["sender_id"]);
		    $message->addChild('ChatID', $row["chat_id"]);
		    $message->addChild('Status', $row["status"]);
		    $message->addChild('Time', ($row["time"] === 0 ? "senden..." : date("j. M H:i", $row["time"]) . " Uhr"));
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
                $resource->addChild('ThumbnailWidth', $row["thumbnail_width"]);
			    $resource->addChild('ThumbnailHeight', $row["thumbnail_height"]);
		    }
            $_SESSION["messages"][$row["intern_id"]]["new"] = false;
        }
        else
        {
            if ($_SESSION["messages"][$row["id"]]["status"])
            {
                $message = $xml->addChild('MessageStatus');   
                $message->addChild('ID', $row["intern_id"]);   
                $message->addChild('Status', $row["status"]);
                $_SESSION["messages"][$row["id"]]["status"] = false;
            }
            if ($_SESSION["messages"][$row["id"]]["time"])
            {
                $message = $xml->addChild('MessageTime');   
                $message->addChild('ID', $row["intern_id"]);   
                $message->addChild('Time', date("j. M H:i", $row["time"]) . " Uhr");
                $_SESSION["messages"][$row["id"]]["time"] = false;
            }
            if ($_SESSION["messages"][$row["intern_id"]]["thumbnail_path"])
            {
                $message = $xml->addChild('MessageThumbnail');   
                $message->addChild('ID', $row["intern_id"]);   
                $message->addChild('ThumbnailPath', $row["thumbnail_path"]);
                $message->addChild('ThumbnailWidth', $row["thumbnail_width"]);
			    $message->addChild('ThumbnailHeight', $row["thumbnail_height"]);
                $_SESSION["messages"][$row["intern_id"]]["thumbnail_path"] = false;
            }
        }
	}
  
	echo sqAES::crypt($theKey, $xml->asXML());
?>
