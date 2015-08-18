<?php
set_time_limit(600); //1 minute
session_start();
$_SESSION["messagesToSend"] = true;
session_write_close();

$time = $_SESSION["running"];

$mysqli = new mysqli("db586264614.db.1and1.com", "dbo586264614", "#Budapest1101", "db586264614");

function running($time)
{
    //Compare initial timestamp in session
    //and current timestamp in session. This
    //timestamp is updated each time index.php
    //is called (page is refreshed). This will
    //kill the old socket.php processes.
    session_start();
    $running = $_SESSION["running"];
    if ($running != $time) {
        //index.php refreshed by user
        die();
    }
    session_write_close();
    return true; //continue running
}


require_once('include/ChatAPI/whatsprot.class.php');
require_once 'include/ChatAPI/events/MyEvents.php';

$username = "4915730395125";
$nickname = "Dom";
$password = "r5+1cnOyI6DlHnQIl/ZV8GcZKeU="; // The one we got registering the number
$debug = true;

// Create a instance of WhastPort.
$w = new WhatsProt($username, $nickname, $debug);
$events = new MyEvents($w);
$events->setEventsToListenFor($events->activeEvents);
//$w->sendPresenceSubscription("4915253889661");
$lastPing = time();
$w->connect(); // Connect to WhatsApp network
$w->loginWithPassword($password); // logging in with the password we got!


while (running($time)) {
    while($w->pollMessage(false));
	
	if (time() - $lastPing > 300)
	{
		$w->sendPing();
		$lastPing = time();
	}
	
	if ($_SESSION["messagesToSend"] == true)
	{
		echo "Sending...";
		session_start();
		$_SESSION["messagesToSend"] = false;			
		$res = $mysqli->query("SELECT * FROM messages Left join resources on messages.resource = resources.resource_id WHERE status=0");
		
		foreach ($res as $row)
		{
			$currentMessageID = $row["intern_id"];
			if ($row['resource'] === NULL)
			{
				$id = $w->sendMessage($row['chat_id'] , $row['text']);				
			}
			else
			{	
				$id = $w->sendMessageImage($row['chat_id'], $row['path'], false, 0, "", $row['text']);
			}
			if (!$mysqli->query("UPDATE messages SET status=1, id='$id', time=".time()." WHERE intern_id=" . $row["intern_id"]))
			{
				echo "Table update failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
		}
		session_write_close();
	}	 
}
echo "10min Timeout";