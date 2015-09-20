<?php
require 'AllEvents.php';
require 'include/vCard.php';


class MyEvents extends AllEvents
{
	public $mysqli;

    /**
     * This is a list of all current events. Uncomment the ones you wish to listen to.
     * Every event that is uncommented - should then have a function below.
     * @var array
     */
    public $activeEvents = array(
//        'onClose',
//        'onCodeRegister',
//        'onCodeRegisterFailed',
//        'onCodeRequest',
//        'onCodeRequestFailed',
//        'onCodeRequestFailedTooRecent',
        'onConnect',
//        'onConnectError',
//        'onCredentialsBad',
//        'onCredentialsGood',
        'onDisconnect',
//        'onDissectPhone',
//        'onDissectPhoneFailed',
        'onGetAudio',
//        'onGetBroadcastLists',
//        'onGetError',
//        'onGetExtendAccount',
        'onGetGroupMessage',
//        'onGetGroupParticipants',
        'onGetGroups',
        'onGetGroupV2Info',
        'onGetGroupsSubject',
        'onGetImage',
        'onGetGroupImage',
        'onGetLocation',
        'onGetMessage', 
//        'onGetNormalizedJid',
//        'onGetPrivacyBlockedList',
        'onGetProfilePicture',
//        'onGetReceipt',
        'onGetRequestLastSeen',
//        'onGetServerProperties',
//        'onGetServicePricing',
        'onGetStatus',
        'onGetSyncResult',
        'onGetVideo',
        'onGetvCard',
//        'onGroupCreate',
        'onGroupisCreated',
        'onGroupsChatCreate',
//        'onGroupsChatEnd',
        'onGroupsParticipantsAdd',
        'onGroupsParticipantsPromote',
        'onGroupsParticipantsDemote',
        'onGroupsParticipantsRemove',
//        'onLogin',
//        'onLoginFailed',
//        'onAccountExpired',
        'onMediaMessageSent',
//        'onMediaUploadFailed',
        'onMessageComposing',
        'onMessagePaused',
        'onMessageReceivedClient',
        'onMessageReceivedServer',
//        'onPaidAccount',
//        'onPing',
        'onPresenceAvailable',
        'onPresenceUnavailable',
        'onProfilePictureChanged',
        'onProfilePictureDeleted',
        'onSendMessage',
//        'onSendMessageReceived',
//        'onSendPong',
//        'onSendPresence',
//        'onSendStatusUpdate',
//        'onStreamError',
//        'onUploadFile',
//        'onUploadFileFailed',
    );
	
    public function setSessionNews($type, $id, $value)
    {
        echo "$type - $id - $value <br>";
        session_start();
        $_SESSION[$type][$id][$value] = true;
        session_write_close();
    }
    public function unsetSessionNews($type, $id, $value)
    {
        session_start();
        $_SESSION[$type][$id][$value] = false;
        session_write_close();
    }
		
	public function __construct(WhatsProt $whatsProt)
	{
		parent::__construct($whatsProt);		
	}

    public function onConnect($mynumber, $socket)
    {
        echo "<p>WooHoo!, Phone number $mynumber connected successfully!</p>";
    }

    public function onDisconnect($mynumber, $socket)
    {
        echo "<p>Booo!, Phone number $mynumber is disconnected!</p>";
    }
	
	public function onGetSyncResult($result)
	{
		foreach ($result->existing as $number) {
			echo "$number exists<br />";
		}
		foreach ($result->nonExisting as $number) {
			echo "$number does not exist<br />";
		}
	
	}
	
	public function onGetRequestLastSeen($mynumber, $from, $id, $seconds)
	{
	    $this->onPresenceUnavailable($mynumber, $from, $seconds);
	}
	
	public function onGetMessage($mynumber, $from, $id, $type, $time, $name, $body, $resource = NULL)
	{
        if (!$this->mysqli->query("UPDATE contacts SET name='$name' WHERE id='$from'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}	

		if (!($stmt = $this->mysqli->prepare("INSERT INTO messages (text, id, sender_id, chat_id, isGroup, status, resource, time) VALUES (?, ?, ?, ?, ?, 3, ?, ?)")))
		{
			  echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}

		$isGroup = false;
		if (!$stmt->bind_param("ssssiii", $body, $id, $from, $from, $isGroup, $resource, $time))
		{
			  echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) 
		{
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->close();
		$this->whatsProt->sendMessageRead($from, $id, ""); 
        $this->setSessionNews("messages", $id, "new");
	}
	
	public function onGetImage($mynumber, $from, $id, $type, $time, $name, $size, $url, $file, $mimeType, $fileHash, $width, $height, $preview, $caption)
	{
		$path = "media/" . $file;
		$thumbnailPath = "media/prev_" . $file;
		file_put_contents($path, fopen($url, 'r'));
		file_put_contents($thumbnailPath, $preview);	
		
		$stmt = $this->mysqli->prepare("INSERT INTO resources (type, path, thumbnail_path) VALUES ('picture', ?, ?)");	
		$stmt->bind_param("ss", $path, $thumbnailPath);
		$stmt->execute();
		
		$resourceID = $this->mysqli->insert_id;

		$this->onGetMessage($mynumber, $from, $id, $type, $time, $name, $caption, $resourceID);
	}

    public function onGetAudio($mynumber, $from, $id, $type, $time, $name, $size, $url, $file, $mimeType, $fileHash, $duration, $acodec, $fromJID_ifGroup = null) 
    {
        $path = "media/" . $file;
		file_put_contents($path, fopen($url, 'r'));
		
		$stmt = $this->mysqli->prepare("INSERT INTO resources (type, path, thumbnail_path) VALUES ('audio', ?, '')");	
		$stmt->bind_param("s", $path);
		$stmt->execute();
		
		$resourceID = $this->mysqli->insert_id;

        if ($fromJID_ifGroup === null) {
		    $this->onGetMessage($mynumber, $from, $id, $type, $time, $name, '', $resourceID);
        } else {
            $this->onGetGroupMessage($mynumber, $from, $fromJID_ifGroup, $id, $type, $time, $name, '', $resourceID);
        }
    }

    public function onGetLocation($mynumber, $from, $id, $type, $time, $name, $author, $longitude, $latitude, $url, $preview, $fromJID_ifGroup = null) 
    {
        $text = "Location:<br><a href=\"$url\">$author</a>";
        if ($fromJID_ifGroup === null) {
		    $this->onGetMessage($mynumber, $from, $id, $type, $time, $name, $text);
        } else {
            $this->onGetGroupMessage($mynumber, $from, $fromJID_ifGroup, $id, $type, $time, $name, $text);
        }
    }

    public function onGetvCard($mynumber, $from, $id, $type, $time, $name, $vcardname, $vcard, $fromJID_ifGroup = null) 
    {
        $text = "vCard:<br>";
        $vCard = new vCard(false, $vcard);
        if ($vCard->fn)
            $text.= "Name: ".$vCard->fn[0]."<br>";
        if ($vCard->bday)
            $text.= "Birthday: ".$vCard->bday[0]."<br>";
        if ($vCard->adr)
            $text.= "Address: ".$vCard->adr[0]."<br>";
        if ($vCard->email)
            $text.= "EMAIL: ".$vCard->email[0]."<br>";
         if ($vCard->tel)
            $text.= "Tel.: ".$vCard->tel[0]."<br>";
        if ($fromJID_ifGroup === null) {
		    $this->onGetMessage($mynumber, $from, $id, $type, $time, $name, $text);
        } else {
            $this->onGetGroupMessage($mynumber, $from, $fromJID_ifGroup, $id, $type, $time, $name, $text);
        }
    }

    public function onGetGroupImage($mynumber, $from_group_jid, $from_user_jid, $id, $type, $time, $name, $size, $url, $file, $mimeType, $fileHash, $width, $height, $preview, $caption) 
    {
        $path = "media/" . $file;
		$thumbnailPath = "media/prev_" . $file;
		file_put_contents($path, fopen($url, 'r'));
		file_put_contents($thumbnailPath, $preview);	
		
		$stmt = $this->mysqli->prepare("INSERT INTO resources (type, path, thumbnail_path) VALUES ('picture', ?, ?)");	
		$stmt->bind_param("ss", $path, $thumbnailPath);
		$stmt->execute();
		
		$resourceID = $this->mysqli->insert_id;

		$this->onGetGroupMessage($mynumber, $from_group_jid, $from_user_jid, $id, $type, $time, $name, $caption, $resourceID);
    }

    public function onProfilePictureChanged($mynumber, $from, $id, $time)
    {
        $this->whatsProt->sendGetProfilePicture($from, true); 
        $this->whatsProt->sendGetProfilePicture($from, false);  
    }

    public function onProfilePictureDeleted($mynumber, $from, $id, $time) 
    {
        $group = (substr($from, strpos($from, "@")) === "@g.us");
        if (!$group)
        {
            if (!$this->mysqli->query("UPDATE contacts SET picture=0 WHERE id='$from'"))
		    {
			    echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		    }	
        }
        else
        {
            if (!$this->mysqli->query("UPDATE groups SET picture=0 WHERE id='$from'"))
		    {
			    echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		    }	
        }
        if (!$group)
            $this->setSessionNews("contacts", $from, "picture");
        else
            $this->setSessionNews("groups", $from, "picture");
    }
	
	public function onMessageReceivedClient($mynumber, $from, $id, $type, $time, $participant) 
	{
		$status = ($type === "" ? 3 : 4);
		if (!$this->mysqli->query("UPDATE messages SET status=".$status."  WHERE id='" . $id . "' AND chat_id='" . $from . "'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
        $this->setSessionNews("messages", $id, "status");
	}
    public function onMessageReceivedServer($mynumber, $from, $id, $type, $time)
	{		
		if (!$this->mysqli->query("UPDATE messages SET status=2, id='$id', time=$time WHERE intern_id=" . $GLOBALS['currentMessageID'] . ""))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
        $this->setSessionNews("messages", $id, "status");        
        $this->setSessionNews("messages", $id, "time");     
	}

    public function onSendMessage($mynumber, $target, $messageId, $node) 
    {

    }
	
	public function onGroupsChatCreate($mynumber, $gid) 
	{
		echo "<div>onGroupsChatCreate ".$mynumber." ".$gid."</div>";
	}
	
	public function onGroupsParticipantsAdd($mynumber, $groupId, $jid)
	{
		$this->addMembers($groupId, array($jid));

        if (!$this->mysqli->query("UPDATE groups SET lastModified=CURRENT_TIMESTAMP WHERE id='$groupId'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}	

        $this->setSessionNews("groups", $groupId, "members");
	}
	
    public function onGroupsParticipantsRemove($mynumber, $groupId, $jid) 
    {
        if (substr($jid, 0, strpos($jid, "@")) === $GLOBALS['username']) 
            $jid = "0";

        if (!$this->mysqli->query("DELETE FROM groups_contacts_join WHERE group_id='" . $groupId . "' AND contact_id='" . $jid . "' "))
		{
			echo "Table delete failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}

        if (!$this->mysqli->query("UPDATE groups SET lastModified=CURRENT_TIMESTAMP WHERE id='$groupId'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}	
        
        $this->setSessionNews("groups", $groupId, "members");
    }

	public function onGroupisCreated($mynumber, $creator, $gid, $subject, $admins, $creation, $members = array()) 
	{
         if (!is_array($admins)) {
            $admins = array($admins);
        }

        $isGroup = false;
        $gid .= "@g.us";

        if (!$this->mysqli->query("DELETE FROM groups_contacts_join WHERE group_id='" . $gid . "' "))
		{
			echo "Table delete failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}

        if (!$this->mysqli->query("DELETE FROM groups WHERE id='" . $gid . "' "))
		{
			echo "Table delete failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}

		if (!($stmt = $this->mysqli->prepare("INSERT INTO groups (id, name) VALUES (?, ?)")))
		{
			echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
		
		
		if (!$stmt->bind_param("ss", $gid, $subject))
		{
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) 
		{
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->close();       
        $this->setSessionNews("groups", $gid, "new");

        $this->whatsProt->sendGetProfilePicture($gid, true); 
        $this->whatsProt->sendGetProfilePicture($gid, false);  

        $this->addMembers($gid, $members, $admins);
	}

    private function addMembers($gid, $members, $admins = array()) {
        $this->addContacts($members);

        $sql = "INSERT INTO groups_contacts_join (group_id, contact_id, admin) VALUES ";
        for ($i = 0; $i < sizeof($members); $i++)
        {
            if (substr($members[$i], 0, strpos($members[$i], "@")) !== $GLOBALS['username']) 
                $sql .= "('" . $gid . "','" . $members[$i] . "',";
            else
                $sql .= "('" . $gid . "','0',";
            $sql .= ( in_array($members[$i], $admins) ? "1" : "0" ) . "),";
        }
        $sql = rtrim($sql, ",");

        if (!$this->mysqli->query($sql))
		{
			echo "Table insert failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
    }

    public function addContacts($newContacts)
    {
        $res = $this->mysqli->query("SELECT * FROM contacts");

        $existingContacts = array();
		
		foreach ($res as $row)
		{
            $existingContacts[] = $row["id"];
        }

        $unknownContacts = array();

        for ($i = 0; $i < sizeof($newContacts); $i++)
        {
            if (substr($newContacts[$i], 0, strpos($newContacts[$i], "@")) !== $GLOBALS['username'] && !in_array($newContacts[$i], $existingContacts)) 
                $unknownContacts[] = $newContacts[$i];
        }
         var_dump($newContacts);
         var_dump($existingContacts);
        var_dump($unknownContacts);

        if (count($unknownContacts) > 0) {
            $sql = "INSERT INTO contacts (id, name) VALUES ";
            for ($i = 0; $i < sizeof($unknownContacts); $i++)
            {            
                  $sql .= "('" . $unknownContacts[$i] . "','" . substr($unknownContacts[$i], 0, strpos($unknownContacts[$i], "@")) . "'),";
            }
            $sql = rtrim($sql, ",");

            if (!$this->mysqli->query($sql))
		    {
			    echo "Table insert failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		    }
            $this->whatsProt->sendSync($unknownContacts);
            for ($i = 0; $i < sizeof($unknownContacts); $i++)
            {
                $this->whatsProt->sendGetProfilePicture($unknownContacts[$i], true); 
                $this->whatsProt->sendGetProfilePicture($unknownContacts[$i], false);             
                //$this->whatsProt->sendPresenceSubscription($newContacts[$i]);  
                $this->setSessionNews("contacts", $unknownContacts[$i], "new");
            } 
            $this->whatsProt->sendGetStatuses($unknownContacts);
            
        }
    }

	public function onGetProfilePicture($mynumber, $from, $type, $data) 
    {
        $group = (substr($from, strpos($from, "@")) === "@g.us");
        if (!$group && substr($from, 0, strpos($from, "@")) === $GLOBALS['username'])
            $from = 0;

        if (!$group)
            $res = $this->mysqli->query("SELECT picture FROM contacts WHERE id='$from'");
        else
            $res = $this->mysqli->query("SELECT picture FROM groups WHERE id='$from'");

        if ($res->num_rows > 0)
        {
            $resourceID = $res->fetch_assoc()['picture'];
            if ($resourceID == 0)
                $resourceID = NULL;
        }
        else 
            $resourceID = NULL;

        if ($type == "preview") 
        {
            $path = "media/prev_" . $from . ".jpg";
            $pathType = "thumbnail_path";
        }
        else
        {
            $path = "media/" . $from . ".jpg";     
            $pathType = "path";       
        }
        file_put_contents($path, $data);	    	

        if ($resourceID === NULL)
        {       
            if (!$this->mysqli->query("INSERT INTO resources (type, $pathType) VALUES ('picture', '$path')"))
		    {
			    echo "Table insert failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		    }
            $resourceID = $this->mysqli->insert_id;
            if (!$group)
            {
                if (!$this->mysqli->query("UPDATE contacts SET picture=$resourceID WHERE id='$from'"))
		        {
			        echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		        }	
            }
            else
            {
                if (!$this->mysqli->query("UPDATE groups SET picture=$resourceID WHERE id='$from'"))
		        {
			        echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		        }	
            }
        }
        else
        {
            if (!$this->mysqli->query("UPDATE resources SET $pathType='$path' WHERE resource_id=$resourceID"))
		    {
			    echo "Table insert failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		    }
            if (!$group)
            {
                if (!$this->mysqli->query("UPDATE contacts SET lastModified=CURRENT_TIMESTAMP WHERE id='$from'"))
		        {
			        echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		        }	
            }
            else
            {
                if (!$this->mysqli->query("UPDATE groups SET lastModified=CURRENT_TIMESTAMP WHERE id='$from'"))
		        {
			        echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		        }	
            }
        }
        if (!$group)
            $this->setSessionNews("contacts", $from, "picture");
        else
            $this->setSessionNews("groups", $from, "picture");
    }

	public function onGetGroupMessage($mynumber, $from_group_jid, $from_user_jid, $id, $type, $time, $name, $body, $resource = NULL) 
	{
		if (!($stmt = $this->mysqli->prepare("INSERT INTO messages (text, id, sender_id, chat_id, isGroup, status, resource, time) VALUES (?, ?, ?, ?, ?, 3, ?, ?)")))
		{
			  echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}

		$isGroup = true;
		if (!$stmt->bind_param("ssssiii", $body, $id, $from_user_jid, $from_group_jid, $isGroup, $resource, $time))
		{
			  echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) 
		{
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->close();
		$this->whatsProt->sendGroupMessageRead($from_group_jid, $from_user_jid, $id, ""); 
        $this->setSessionNews("messages", $id, "new");
	}
	
	public function onPresenceAvailable($mynumber, $from)
	{
		if (!$this->mysqli->query("UPDATE contacts SET lastSeen=0 WHERE id='" . $from . "'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
        $this->setSessionNews("contacts", $from, "lastSeen");
	}
	
    public function onPresenceUnavailable($mynumber, $from, $last) 
	{
		if (!$this->mysqli->query("UPDATE contacts SET lastSeen=".$last." WHERE id='" . $from . "'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
        $this->setSessionNews("contacts", $from, "lastSeen");
	}
	
	public function onMediaMessageSent($mynumber, $to, $id, $filetype, $url, $filename, $filesize, $filehash, $caption, $icon) 
	{
        if ($icon !== '') {
		    $thumbnailPath = "media/prev_" . $filename . ".jpg";
		    file_put_contents($thumbnailPath, $icon);		
		    if (!$this->mysqli->query("UPDATE resources Left join messages on messages.resource = resources.resource_id SET thumbnail_path='".$thumbnailPath."' WHERE intern_id='" . $GLOBALS['currentMessageID'] . "'"))
		    {
			    echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		    }		
            $this->setSessionNews("messages", $GLOBALS['currentMessageID'] , "thumbnail_path");
        }
	}

    public function onGetStatus($mynumber, $from, $requested, $id, $time, $data) 
    {
        if (!$this->mysqli->query("UPDATE contacts SET status='$data' WHERE id='$from'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
        $this->setSessionNews("contacts", $from, "status");
    }

    public function onGetGroupsSubject($mynumber, $group_jid, $time, $author, $name, $subject) 
    {
        if (!$this->mysqli->query("UPDATE groups SET name='$subject' WHERE id='$group_jid'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
        $this->setSessionNews("groups", $group_jid, "name");
    }

    public function onGetVideo($mynumber, $from, $id, $type, $time, $name, $url, $file, $size, $mimeType, $fileHash, $duration, $vcodec, $acodec, $preview, $caption) 
    {
        $path = "media/" . $file;
		$thumbnailPath = "media/prev_" . $file;
		file_put_contents($path, fopen($url, 'r'));
		file_put_contents($thumbnailPath, $preview);	
		
		$stmt = $this->mysqli->prepare("INSERT INTO resources (type, path, thumbnail_path) VALUES ('video', ?, ?)");	
		$stmt->bind_param("ss", $path, $thumbnailPath);
		$stmt->execute();
		
		$resourceID = $this->mysqli->insert_id;

		$this->onGetMessage($mynumber, $from, $id, $type, $time, $name, $caption, $resourceID);
    }

    public function onGetGroups($mynumber, $groupList) 
    {

    }

    public function onGetGroupV2Info($mynumber, $group_id, $creator, $creation, $subject, $participants, $admins, $fromGetGroup )
    {
        $this->onGroupisCreated($mynumber, $creator, $group_id, $subject, $admins, $creation, $participants);
    }

    public function onMessageComposing($mynumber, $from, $id, $type, $time) 
    {
        $this->unsetSessionNews("contacts", $from, "paused");
        $this->setSessionNews("contacts", $from, "composing");
        if (!$this->mysqli->query("UPDATE contacts SET lastModified=CURRENT_TIMESTAMP WHERE id='$from'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}	
    }

    public function onMessagePaused($mynumber, $from, $id, $type, $time) 
    {
        $this->unsetSessionNews("contacts", $from, "composing");
        $this->setSessionNews("contacts", $from, "paused");
        if (!$this->mysqli->query("UPDATE contacts SET lastModified=CURRENT_TIMESTAMP WHERE id='$from'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
    }

    public function onGroupsParticipantsPromote($myNumber, $groupJID, $time, $issuerJID, $issuerName, $promotedJIDs = array()) 
    {
        foreach ($promotedJIDs as $contactID)
        {
            if (!$this->mysqli->query("UPDATE groups_contacts_join SET admin=1 WHERE group_id='$groupJID' AND contact_id='$contactID' "))
		    {
			    echo "Table update failed: (" . $mysqli->errno . ") " . $mysqli->error;
		    }
        }
        if (!$this->mysqli->query("UPDATE groups SET lastModified=CURRENT_TIMESTAMP WHERE id='$groupJID'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}	
        $this->setSessionNews("groups", $groupJID, "members");
    }

    public function onGroupsParticipantsDemote($myNumber, $groupJID, $time, $issuerJID, $issuerName, $demotedJIDs = array()) 
    {
        foreach ($demotedJIDs as $contactID)
        {
            if (!$this->mysqli->query("UPDATE groups_contacts_join SET admin=0 WHERE group_id='$groupJID' AND contact_id='$contactID' "))
		    {
			    echo "Table update failed: (" . $mysqli->errno . ") " . $mysqli->error;
		    }
        }
        if (!$this->mysqli->query("UPDATE groups SET lastModified=CURRENT_TIMESTAMP WHERE id='$groupJID'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}	
        $this->setSessionNews("groups", $groupJID, "members");
    }
}
