<?php
 

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

$w->connect(); // Connect to WhatsApp network
$w->loginWithPassword($password); // logging in with the password we got!

$target = '4915253889661'; // The number of the person you are sending the message
$message = 'Hi!';

while(true)
{
	while($w->pollMessage(false));
}
 
die();
$w->sendMessage($target , $message);

die();
echo "0"; 
$myContacts = array('4915253889661');
$w->sendSync($myContacts); 


?>