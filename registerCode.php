<?php
require_once('include/ChatAPI/whatsprot.class.php');

$username = "4915730395125";
$nickname = "Dom";
$debug = true;

// Create a instance of WhastPort.
$w = new WhatsProt($username, $nickname, $debug);

$w->codeRegister('609064');

?>