<?php
require 'AllEvents.php';



class MyEvents extends AllEvents
{
	protected $mysqli;

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
//        'onGetAudio',
//        'onGetBroadcastLists',
//        'onGetError',
//        'onGetExtendAccount',
        'onGetGroupMessage',
//        'onGetGroupParticipants',
//        'onGetGroups',
//        'onGetGroupsInfo',
//        'onGetGroupsSubject',
        'onGetImage',
        'onGetGroupImage',
//        'onGetLocation',
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
//        'onGetVideo',
//        'onGetvCard',
//        'onGroupCreate',
        'onGroupisCreated',
        'onGroupsChatCreate',
//        'onGroupsChatEnd',
        'onGroupsParticipantsAdd',
//        'onGroupsParticipantsPromote',
//        'onGroupsParticipantsRemove',
//        'onLogin',
//        'onLoginFailed',
//        'onAccountExpired',
        'onMediaMessageSent',
//        'onMediaUploadFailed',
//        'onMessageComposing',
//        'onMessagePaused',
        'onMessageReceivedClient',
        'onMessageReceivedServer',
//        'onPaidAccount',
//        'onPing',
        'onPresenceAvailable',
        'onPresenceUnavailable',
//        'onProfilePictureChanged',
//        'onProfilePictureDeleted',
//        'onSendMessage',
//        'onSendMessageReceived',
//        'onSendPong',
//        'onSendPresence',
//        'onSendStatusUpdate',
//        'onStreamError',
//        'onUploadFile',
//        'onUploadFileFailed',
    );
	
		
	public function __construct(WhatsProt $whatsProt)
	{
		parent::__construct($whatsProt);
		$this->mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");
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
	
	public function onGetRequestLastSeen( $mynumber, $from, $id, $seconds )
	{
	  echo "Last seen of $id: ".gmdate("H:i:s", $seconds);
	}
	
	public function onGetMessage($mynumber, $from, $id, $type, $time, $name, $body, $resource = NULL)
	{
		if (!($stmt = $this->mysqli->prepare("INSERT INTO messages (text, id, sender_id, chat_id, isGroup, status, resource, time) VALUES (?, ?, ?, ?, ?, 2, ?, ?)")))
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
	
	public function onMessageReceivedClient($mynumber, $from, $id, $type, $time, $participant) 
	{
		$status = ($type === "" ? 2 : 3);
		if (!$this->mysqli->query("UPDATE messages SET status=".$status."  WHERE id='" . $id . "' AND chat_id='" . $from . "'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
	}
    public function onMessageReceivedServer($mynumber, $from, $id, $type, $time)
	{		
		if (!$this->mysqli->query("UPDATE messages SET status=1 WHERE id='" . $id . "' AND chat_id='" . $from . "'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
	}
	
	public function onGroupsChatCreate($mynumber, $gid) 
	{
		echo "<div>onGroupsChatCreate ".$mynumber." ".$gid."</div>";
	}
	
	public function onGroupsParticipantsAdd($mynumber, $groupId, $jid)
	{
		echo "<div>onGroupsParticipantsAdd ".$mynumber." ".$gid." ".$jid."</div>";
	}
	
	public function onGroupisCreated($mynumber, $creator, $gid, $subject, $admin, $creation, $members = array()) 
	{
		if (!($stmt = $this->mysqli->prepare("INSERT INTO groups (id, name) VALUES (?, ?)")))
		{
			  echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
		
		$isGroup = false;
        $gid .= "@g.us";
		if (!$stmt->bind_param("ss", $gid, $subject))
		{
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) 
		{
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->close();
        $this->addContacts($members);
	}

    private function addContacts($newContacts)
    {
        $sql = "INSERT INTO contacts (id, name) VALUES ";
        for ($i = 0; $i < sizeof($newContacts); $i++)
        {
             if (substr($newContacts[$i], 0, strpos($newContacts[$i], "@")) !== $GLOBALS['username']) 
                $sql .= "('" . $newContacts[$i] . "','" . substr($newContacts[$i], 0, strpos($newContacts[$i], "@")) . "'),";
        }
        $sql = rtrim($sql, ",");

        if (!$this->mysqli->query($sql))
		{
			echo "Table insert failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
        for ($i = 0; $i < sizeof($newContacts); $i++)
        {
            $this->whatsProt->sendGetProfilePicture($newContacts[$i], true); 
            $this->whatsProt->sendGetProfilePicture($newContacts[$i], false);  
            $this->whatsProt->sendPresenceSubscription($newContacts[$i]);  
        }
        $this->whatsProt->sendSync($newContacts);
        $this->whatsProt->sendGetStatuses($newContacts);
    }

	public function onGetProfilePicture($mynumber, $from, $type, $data) 
    {
        if (substr($from, 0, strpos($from, "@")) === $GLOBALS['username'])
            $from = 0;

        $res = $this->mysqli->query("SELECT picture FROM contacts WHERE id='$from'");

        if ($res->num_rows > 0)
            $resourceID = $res->fetch_assoc()['picture'];
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
            if (!$this->mysqli->query("UPDATE contacts SET picture=$resourceID WHERE id='$from'"))
		    {
			    echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		    }	
        }
        else
        {
            if (!$this->mysqli->query("UPDATE resources SET $pathType='$path' WHERE resource_id=$resourceID"))
		    {
			    echo "Table insert failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		    }
        }
    }

	public function onGetGroupMessage($mynumber, $from_group_jid, $from_user_jid, $id, $type, $time, $name, $body, $resource = NULL) 
	{
		if (!($stmt = $this->mysqli->prepare("INSERT INTO messages (text, id, sender_id, chat_id, isGroup, status, resource, time) VALUES (?, ?, ?, ?, ?, 2, ?, ?)")))
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
	}
	
	public function onPresenceAvailable($mynumber, $from)
	{
		if (!$this->mysqli->query("UPDATE contacts SET lastSeen=0 WHERE id='" . $from . "'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
	}
	
    public function onPresenceUnavailable($mynumber, $from, $last) 
	{
		if (!$this->mysqli->query("UPDATE contacts SET lastSeen=".$last." WHERE id='" . $from . "'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
	}
	
	public function onMediaMessageSent($mynumber, $to, $id, $filetype, $url, $filename, $filesize, $filehash, $caption, $icon) 
	{
		$thumbnailPath = "media/prev_" . $filename;
		file_put_contents($thumbnailPath, $icon);		
		if (!$this->mysqli->query("UPDATE resources Left join messages on messages.resource = resources.resource_id SET thumbnail_path='".$thumbnailPath."' WHERE intern_id='" . $GLOBALS['currentMessageID'] . "'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}		
	}

    public function onGetStatus($mynumber, $from, $requested, $id, $time, $data) 
    {
        if (!$this->mysqli->query("UPDATE contacts SET status='$data' WHERE id='$from'"))
		{
			echo "Table update failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
		}
    }
	
}
