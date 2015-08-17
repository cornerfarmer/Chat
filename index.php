<!DOCTYPE html>
<?php
session_start();
$_SESSION["running"] = time();
?>
<html>
<head>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<link media="all" href="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.indigo-red.min.css" type="text/css" rel="stylesheet">
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
	<script src="script.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="style.css">
	<link media="all" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium" type="text/css" rel="stylesheet">
	<link href=lightbox.css" rel="stylesheet"> 
	<link href="include/emoji.css" rel="stylesheet" type="text/css" />
	<script src="include/emoji.js" type="text/javascript"></script>
	<link href="include/emojisprite.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="include/jquery.mCustomScrollbar.css" />
	<script src="include/jquery.mCustomScrollbar.js"></script>
</head>
<body>	
	<div id="content">
		<div id="refresh_button_wrapper">
			<button id="refresh_button" onclick="refresh()" class="mdl-button mdl-js-ripple-effect mdl-js-button">
				  Loading...
			</button>
		</div>
		<div id="chats" class="mdl-grid">
		<div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col chat">		
			<div class="mdl-color--primary-dark chat_description">
				  <h4>Name</h4>
				  <p>Bild</p>
				  <p>Status:<br>blabla</p>
				  <p>Nummer:<br>5424554364756</p>
				  <p>zul. online</p>
			</div>
			<button class="answer_button mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--fab mdl-color--accent">
				  <i class="material-icons mdl-color-text--white" role="presentation">reply</i>
				  <span class="visuallyhidden">reply</span>
			</button>
			<div class="mdl-card__supporting-text messages">			
				<div class="mdl-card mdl-shadow--2dp message message_other" >
					<div class="message_header mdl-card__title mdl-color--primary-dark">Name</div>
					<div class="message_body mdl-color-text--grey-700 mdl-card__supporting-text">Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse. </div>
				</div>		
				<div class="mdl-card mdl-shadow--2dp message message_own" >
					<div class="message_header mdl-card__title">Name</div>
					<div class="message_body mdl-color-text--grey-700 mdl-card__supporting-text">Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse. </div>
				</div>	
				<div class="mdl-card mdl-shadow--2dp message message_other" >
					<div class="message_header mdl-card__title mdl-color--primary-dark">Name</div>
					<div class="message_body mdl-color-text--grey-700 mdl-card__supporting-text">Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse. </div>
				</div>		
				<div class="mdl-card mdl-shadow--2dp message message_own" >
					<div class="message_header mdl-card__title">Name</div>
					<div class="message_body mdl-color-text--grey-700 mdl-card__supporting-text">Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse. </div>
				</div>	
				<div class="mdl-card mdl-shadow--2dp message message_other" >
					<div class="message_header mdl-card__title mdl-color--primary-dark">Name</div>
					<div class="message_body mdl-color-text--grey-700 mdl-card__supporting-text">Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse. </div>
				</div>		
				<div class="mdl-card mdl-shadow--2dp message message_own" >
					<div class="message_header mdl-card__title">Name</div>
					<div class="message_body mdl-color-text--grey-700 mdl-card__supporting-text">Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse. </div>
				</div>	
				<div class="mdl-card mdl-shadow--2dp message message_other" >
					<div class="message_header mdl-card__title mdl-color--primary-dark">Name</div>
					<div class="message_body mdl-color-text--grey-700 mdl-card__supporting-text">Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse. </div>
				</div>		
				<div class="mdl-card mdl-shadow--2dp message message_own" >
					<div class="message_header mdl-card__title">Name</div>
					<div class="message_body mdl-color-text--grey-700 mdl-card__supporting-text">Dolore ex deserunt aute fugiat aute nulla ea sunt aliqua nisi cupidatat eu. Nostrud in laboris labore nisi amet do dolor eu fugiat consectetur elit cillum esse. </div>
				</div>	
			</div>
		</div>
		<div id="inputFieldSpacer"></div>
		<div id="inputFieldWrapper" class="mdl-shadow--3dp mdl-color--primary">
			
			<div id="emojis">
				<div id="emoji-categories">
					<button class="mdl-button mdl-js-button mdl-button--icon " onclick="showEmoji('people')">
					  <i class="material-icons">mood</i>
					</button>
					<button class="mdl-button mdl-js-button mdl-button--icon " onclick="showEmoji('nature')">
					  <i class="material-icons">landscape</i>
					</button>
					<button class="mdl-button mdl-js-button mdl-button--icon " onclick="showEmoji('objects')">
					  <i class="material-icons">restaurant_menu</i>
					</button>
					<button class="mdl-button mdl-js-button mdl-button--icon " onclick="showEmoji('places')">
					  <i class="material-icons">place</i>
					</button>
					<button class="mdl-button mdl-js-button mdl-button--icon " onclick="showEmoji('symbols')">
					  <i class="material-icons">warning</i>
					</button>
				</div>

					<div id="emoji-people" class="emoji-tab">
						<img class="emoji emoji-1f604"> <img class="emoji emoji-1f603"> <img class="emoji emoji-1f600"> <img class="emoji emoji-1f60a"> <img class="emoji emoji-263a"> <img class="emoji emoji-1f609"> <img class="emoji emoji-1f60d">
						<img class="emoji emoji-1f618"> <img class="emoji emoji-1f61a"> <img class="emoji emoji-1f617"> <img class="emoji emoji-1f619"> <img class="emoji emoji-1f61c"> <img class="emoji emoji-1f61d"> <img class="emoji emoji-1f61b">
						<img class="emoji emoji-1f633"> <img class="emoji emoji-1f601"> <img class="emoji emoji-1f614"> <img class="emoji emoji-1f60c"> <img class="emoji emoji-1f612"> <img class="emoji emoji-1f61e"> <img class="emoji emoji-1f623">
						<img class="emoji emoji-1f622"> <img class="emoji emoji-1f602"> <img class="emoji emoji-1f62d"> <img class="emoji emoji-1f62a"> <img class="emoji emoji-1f625"> <img class="emoji emoji-1f630"> <img class="emoji emoji-1f605">
						<img class="emoji emoji-1f613"> <img class="emoji emoji-1f629"> <img class="emoji emoji-1f62b"> <img class="emoji emoji-1f628"> <img class="emoji emoji-1f631"> <img class="emoji emoji-1f620"> <img class="emoji emoji-1f621">
						<img class="emoji emoji-1f624"> <img class="emoji emoji-1f616"> <img class="emoji emoji-1f606"> <img class="emoji emoji-1f60b"> <img class="emoji emoji-1f637"> <img class="emoji emoji-1f60e"> <img class="emoji emoji-1f634">
						<img class="emoji emoji-1f635"> <img class="emoji emoji-1f632"> <img class="emoji emoji-1f61f"> <img class="emoji emoji-1f626"> <img class="emoji emoji-1f627"> <img class="emoji emoji-1f608"> <img class="emoji emoji-1f47f">
						<img class="emoji emoji-1f62e"> <img class="emoji emoji-1f62c"> <img class="emoji emoji-1f610"> <img class="emoji emoji-1f615"> <img class="emoji emoji-1f62f"> <img class="emoji emoji-1f636"> <img class="emoji emoji-1f607">
						<img class="emoji emoji-1f60f"> <img class="emoji emoji-1f611"> <img class="emoji emoji-1f472"> <img class="emoji emoji-1f473"> <img class="emoji emoji-1f46e"> <img class="emoji emoji-1f477"> <img class="emoji emoji-1f482">
						<img class="emoji emoji-1f476"> <img class="emoji emoji-1f466"> <img class="emoji emoji-1f467"> <img class="emoji emoji-1f468"> <img class="emoji emoji-1f469"> <img class="emoji emoji-1f474"> <img class="emoji emoji-1f475">
						<img class="emoji emoji-1f471"> <img class="emoji emoji-1f47c"> <img class="emoji emoji-1f478"> <img class="emoji emoji-1f63a"> <img class="emoji emoji-1f638"> <img class="emoji emoji-1f63b"> <img class="emoji emoji-1f63d">
						<img class="emoji emoji-1f63c"> <img class="emoji emoji-1f640"> <img class="emoji emoji-1f63f"> <img class="emoji emoji-1f639"> <img class="emoji emoji-1f63e"> <img class="emoji emoji-1f479"> <img class="emoji emoji-1f47a">
						<img class="emoji emoji-1f648"> <img class="emoji emoji-1f649"> <img class="emoji emoji-1f64a"> <img class="emoji emoji-1f480"> <img class="emoji emoji-1f47d"> <img class="emoji emoji-1f4a9"> <img class="emoji emoji-1f525">
						<img class="emoji emoji-2728"> <img class="emoji emoji-1f31f"> <img class="emoji emoji-1f4ab"> <img class="emoji emoji-1f4a5"> <img class="emoji emoji-1f4a2"> <img class="emoji emoji-1f4a6"> <img class="emoji emoji-1f4a7">
						<img class="emoji emoji-1f4a4"> <img class="emoji emoji-1f4a8"> <img class="emoji emoji-1f442"> <img class="emoji emoji-1f440"> <img class="emoji emoji-1f443"> <img class="emoji emoji-1f445"> <img class="emoji emoji-1f444">
						<img class="emoji emoji-1f44d"> <img class="emoji emoji-1f44e"> <img class="emoji emoji-1f44c"> <img class="emoji emoji-1f44a"> <img class="emoji emoji-270a"> <img class="emoji emoji-270c"> <img class="emoji emoji-1f44b">
						<img class="emoji emoji-270b"> <img class="emoji emoji-1f450"> <img class="emoji emoji-1f446"> <img class="emoji emoji-1f447"> <img class="emoji emoji-1f449"> <img class="emoji emoji-1f448"> <img class="emoji emoji-1f64c">
						<img class="emoji emoji-1f64f"> <img class="emoji emoji-261d"> <img class="emoji emoji-1f44f"> <img class="emoji emoji-1f4aa"> <img class="emoji emoji-1f6b6"> <img class="emoji emoji-1f3c3"> <img class="emoji emoji-1f483">
						<img class="emoji emoji-1f46b"> <img class="emoji emoji-1f46a"> <img class="emoji emoji-1f46c"> <img class="emoji emoji-1f46d"> <img class="emoji emoji-1f48f"> <img class="emoji emoji-1f491"> <img class="emoji emoji-1f46f">
						<img class="emoji emoji-1f646"> <img class="emoji emoji-1f645"> <img class="emoji emoji-1f481"> <img class="emoji emoji-1f64b"> <img class="emoji emoji-1f486"> <img class="emoji emoji-1f487"> <img class="emoji emoji-1f485">
						<img class="emoji emoji-1f470"> <img class="emoji emoji-1f64e"> <img class="emoji emoji-1f64d"> <img class="emoji emoji-1f647"> <img class="emoji emoji-1f3a9"> <img class="emoji emoji-1f451"> <img class="emoji emoji-1f452">
						<img class="emoji emoji-1f45f"> <img class="emoji emoji-1f45e"> <img class="emoji emoji-1f461"> <img class="emoji emoji-1f460"> <img class="emoji emoji-1f462"> <img class="emoji emoji-1f455"> <img class="emoji emoji-1f454">
						<img class="emoji emoji-1f45a"> <img class="emoji emoji-1f457"> <img class="emoji emoji-1f3bd"> <img class="emoji emoji-1f456"> <img class="emoji emoji-1f458"> <img class="emoji emoji-1f459"> <img class="emoji emoji-1f4bc">
						<img class="emoji emoji-1f45c"> <img class="emoji emoji-1f45d"> <img class="emoji emoji-1f45b"> <img class="emoji emoji-1f453"> <img class="emoji emoji-1f380"> <img class="emoji emoji-1f302"> <img class="emoji emoji-1f484">
						<img class="emoji emoji-1f49b"> <img class="emoji emoji-1f499"> <img class="emoji emoji-1f49c"> <img class="emoji emoji-1f49a"> <img class="emoji emoji-2764"> <img class="emoji emoji-1f494"> <img class="emoji emoji-1f497">
						<img class="emoji emoji-1f493"> <img class="emoji emoji-1f495"> <img class="emoji emoji-1f496"> <img class="emoji emoji-1f49e"> <img class="emoji emoji-1f498"> <img class="emoji emoji-1f48c"> <img class="emoji emoji-1f48b">
						<img class="emoji emoji-1f48d"> <img class="emoji emoji-1f48e"> <img class="emoji emoji-1f464"> <img class="emoji emoji-1f465"> <img class="emoji emoji-1f4ac"> <img class="emoji emoji-1f463"> <img class="emoji emoji-1f4ad">
					</div>
					
					<div id="emoji-nature" class="emoji-tab ">
						<img class="emoji emoji-1f436"> <img class="emoji emoji-1f43a"> <img class="emoji emoji-1f431"> <img class="emoji emoji-1f42d"> <img class="emoji emoji-1f439"> <img class="emoji emoji-1f430"> <img class="emoji emoji-1f438">
						<img class="emoji emoji-1f42f"> <img class="emoji emoji-1f428"> <img class="emoji emoji-1f43b"> <img class="emoji emoji-1f437"> <img class="emoji emoji-1f43d"> <img class="emoji emoji-1f42e"> <img class="emoji emoji-1f417">
						<img class="emoji emoji-1f435"> <img class="emoji emoji-1f412"> <img class="emoji emoji-1f434"> <img class="emoji emoji-1f411"> <img class="emoji emoji-1f418"> <img class="emoji emoji-1f43c"> <img class="emoji emoji-1f427">
						<img class="emoji emoji-1f426"> <img class="emoji emoji-1f424"> <img class="emoji emoji-1f425"> <img class="emoji emoji-1f423"> <img class="emoji emoji-1f414"> <img class="emoji emoji-1f40d"> <img class="emoji emoji-1f422">
						<img class="emoji emoji-1f41b"> <img class="emoji emoji-1f41d"> <img class="emoji emoji-1f41c"> <img class="emoji emoji-1f41e"> <img class="emoji emoji-1f40c"> <img class="emoji emoji-1f419"> <img class="emoji emoji-1f41a">
						<img class="emoji emoji-1f420"> <img class="emoji emoji-1f41f"> <img class="emoji emoji-1f42c"> <img class="emoji emoji-1f433"> <img class="emoji emoji-1f40b"> <img class="emoji emoji-1f404"> <img class="emoji emoji-1f40f">
						<img class="emoji emoji-1f400"> <img class="emoji emoji-1f403"> <img class="emoji emoji-1f405"> <img class="emoji emoji-1f407"> <img class="emoji emoji-1f409"> <img class="emoji emoji-1f40e"> <img class="emoji emoji-1f410">
						<img class="emoji emoji-1f413"> <img class="emoji emoji-1f415"> <img class="emoji emoji-1f416"> <img class="emoji emoji-1f401"> <img class="emoji emoji-1f402"> <img class="emoji emoji-1f432"> <img class="emoji emoji-1f421">
						<img class="emoji emoji-1f40a"> <img class="emoji emoji-1f42b"> <img class="emoji emoji-1f42a"> <img class="emoji emoji-1f406"> <img class="emoji emoji-1f408"> <img class="emoji emoji-1f429"> <img class="emoji emoji-1f43e">
						<img class="emoji emoji-1f490"> <img class="emoji emoji-1f338"> <img class="emoji emoji-1f337"> <img class="emoji emoji-1f340"> <img class="emoji emoji-1f339"> <img class="emoji emoji-1f33b"> <img class="emoji emoji-1f33a">
						<img class="emoji emoji-1f341"> <img class="emoji emoji-1f343"> <img class="emoji emoji-1f342"> <img class="emoji emoji-1f33f"> <img class="emoji emoji-1f33e"> <img class="emoji emoji-1f344"> <img class="emoji emoji-1f335">
						<img class="emoji emoji-1f334"> <img class="emoji emoji-1f332"> <img class="emoji emoji-1f333"> <img class="emoji emoji-1f330"> <img class="emoji emoji-1f331"> <img class="emoji emoji-1f33c"> <img class="emoji emoji-1f310">
						<img class="emoji emoji-1f31e"> <img class="emoji emoji-1f31d"> <img class="emoji emoji-1f31a"> <img class="emoji emoji-1f311"> <img class="emoji emoji-1f312"> <img class="emoji emoji-1f313"> <img class="emoji emoji-1f314">
						<img class="emoji emoji-1f315"> <img class="emoji emoji-1f316"> <img class="emoji emoji-1f317"> <img class="emoji emoji-1f318"> <img class="emoji emoji-1f31c"> <img class="emoji emoji-1f31b"> <img class="emoji emoji-1f319">
						<img class="emoji emoji-1f30d"> <img class="emoji emoji-1f30e"> <img class="emoji emoji-1f30f"> <img class="emoji emoji-1f30b"> <img class="emoji emoji-1f30c"> <img class="emoji emoji-1f320"> <img class="emoji emoji-2b50">
						<img class="emoji emoji-2600"> <img class="emoji emoji-26c5"> <img class="emoji emoji-2601"> <img class="emoji emoji-26a1"> <img class="emoji emoji-2614"> <img class="emoji emoji-2744"> <img class="emoji emoji-26c4">
						<img class="emoji emoji-1f300"> <img class="emoji emoji-1f301"> <img class="emoji emoji-1f308"> <img class="emoji emoji-1f30a">
					</div>

					<div id="emoji-objects" class="emoji-tab ">
						<img class="emoji emoji-1f38d"> <img class="emoji emoji-1f49d"> <img class="emoji emoji-1f38e"> <img class="emoji emoji-1f392"> <img class="emoji emoji-1f393"> <img class="emoji emoji-1f38f"> <img class="emoji emoji-1f386">
						<img class="emoji emoji-1f387"> <img class="emoji emoji-1f390"> <img class="emoji emoji-1f391"> <img class="emoji emoji-1f383"> <img class="emoji emoji-1f47b"> <img class="emoji emoji-1f385"> <img class="emoji emoji-1f384">
						<img class="emoji emoji-1f381"> <img class="emoji emoji-1f38b"> <img class="emoji emoji-1f389"> <img class="emoji emoji-1f38a"> <img class="emoji emoji-1f388"> <img class="emoji emoji-1f38c"> <img class="emoji emoji-1f52e">
						<img class="emoji emoji-1f3a5"> <img class="emoji emoji-1f4f7"> <img class="emoji emoji-1f4f9"> <img class="emoji emoji-1f4fc"> <img class="emoji emoji-1f4bf"> <img class="emoji emoji-1f4c0"> <img class="emoji emoji-1f4bd">
						<img class="emoji emoji-1f4be"> <img class="emoji emoji-1f4bb"> <img class="emoji emoji-1f4f1"> <img class="emoji emoji-260e"> <img class="emoji emoji-1f4de"> <img class="emoji emoji-1f4df"> <img class="emoji emoji-1f4e0">
						<img class="emoji emoji-1f4e1"> <img class="emoji emoji-1f4fa"> <img class="emoji emoji-1f4fb"> <img class="emoji emoji-1f50a"> <img class="emoji emoji-1f509"> <img class="emoji emoji-1f508"> <img class="emoji emoji-1f507">
						<img class="emoji emoji-1f514"> <img class="emoji emoji-1f515"> <img class="emoji emoji-1f4e2"> <img class="emoji emoji-1f4e3"> <img class="emoji emoji-23f3"> <img class="emoji emoji-231b"> <img class="emoji emoji-23f0">
						<img class="emoji emoji-231a"> <img class="emoji emoji-1f513"> <img class="emoji emoji-1f512"> <img class="emoji emoji-1f50f"> <img class="emoji emoji-1f510"> <img class="emoji emoji-1f511"> <img class="emoji emoji-1f50e">
						<img class="emoji emoji-1f4a1"> <img class="emoji emoji-1f526"> <img class="emoji emoji-1f506"> <img class="emoji emoji-1f505"> <img class="emoji emoji-1f50c"> <img class="emoji emoji-1f50b"> <img class="emoji emoji-1f50d">
						<img class="emoji emoji-1f6c1"> <img class="emoji emoji-1f6c0"> <img class="emoji emoji-1f6bf"> <img class="emoji emoji-1f6bd"> <img class="emoji emoji-1f527"> <img class="emoji emoji-1f529"> <img class="emoji emoji-1f528">
						<img class="emoji emoji-1f6aa"> <img class="emoji emoji-1f6ac"> <img class="emoji emoji-1f4a3"> <img class="emoji emoji-1f52b"> <img class="emoji emoji-1f52a"> <img class="emoji emoji-1f48a"> <img class="emoji emoji-1f489">
						<img class="emoji emoji-1f4b0"> <img class="emoji emoji-1f4b4"> <img class="emoji emoji-1f4b5"> <img class="emoji emoji-1f4b7"> <img class="emoji emoji-1f4b6"> <img class="emoji emoji-1f4b3"> <img class="emoji emoji-1f4b8">
						<img class="emoji emoji-1f4f2"> <img class="emoji emoji-1f4e7"> <img class="emoji emoji-1f4e5"> <img class="emoji emoji-1f4e4"> <img class="emoji emoji-2709"> <img class="emoji emoji-1f4e9"> <img class="emoji emoji-1f4e8">
						<img class="emoji emoji-1f4ef"> <img class="emoji emoji-1f4eb"> <img class="emoji emoji-1f4ea"> <img class="emoji emoji-1f4ec"> <img class="emoji emoji-1f4ed"> <img class="emoji emoji-1f4ee"> <img class="emoji emoji-1f4e6">
						<img class="emoji emoji-1f4dd"> <img class="emoji emoji-1f4c4"> <img class="emoji emoji-1f4c3"> <img class="emoji emoji-1f4d1"> <img class="emoji emoji-1f4ca"> <img class="emoji emoji-1f4c8"> <img class="emoji emoji-1f4c9">
						<img class="emoji emoji-1f4dc"> <img class="emoji emoji-1f4cb"> <img class="emoji emoji-1f4c5"> <img class="emoji emoji-1f4c6"> <img class="emoji emoji-1f4c7"> <img class="emoji emoji-1f4c1"> <img class="emoji emoji-1f4c2">
						<img class="emoji emoji-2702"> <img class="emoji emoji-1f4cc"> <img class="emoji emoji-1f4ce"> <img class="emoji emoji-2712"> <img class="emoji emoji-270f"> <img class="emoji emoji-1f4cf"> <img class="emoji emoji-1f4d0">
						<img class="emoji emoji-1f4d5"> <img class="emoji emoji-1f4d7"> <img class="emoji emoji-1f4d8"> <img class="emoji emoji-1f4d9"> <img class="emoji emoji-1f4d3"> <img class="emoji emoji-1f4d4"> <img class="emoji emoji-1f4d2">
						<img class="emoji emoji-1f4da"> <img class="emoji emoji-1f4d6"> <img class="emoji emoji-1f516"> <img class="emoji emoji-1f4db"> <img class="emoji emoji-1f52c"> <img class="emoji emoji-1f52d"> <img class="emoji emoji-1f4f0">
						<img class="emoji emoji-1f3a8"> <img class="emoji emoji-1f3ac"> <img class="emoji emoji-1f3a4"> <img class="emoji emoji-1f3a7"> <img class="emoji emoji-1f3bc"> <img class="emoji emoji-1f3b5"> <img class="emoji emoji-1f3b6">
						<img class="emoji emoji-1f3b9"> <img class="emoji emoji-1f3bb"> <img class="emoji emoji-1f3ba"> <img class="emoji emoji-1f3b7"> <img class="emoji emoji-1f3b8"> <img class="emoji emoji-1f47e"> <img class="emoji emoji-1f3ae">
						<img class="emoji emoji-1f0cf"> <img class="emoji emoji-1f3b4"> <img class="emoji emoji-1f004"> <img class="emoji emoji-1f3b2"> <img class="emoji emoji-1f3af"> <img class="emoji emoji-1f3c8"> <img class="emoji emoji-1f3c0">
						<img class="emoji emoji-26bd"> <img class="emoji emoji-26be"> <img class="emoji emoji-1f3be"> <img class="emoji emoji-1f3b1"> <img class="emoji emoji-1f3c9"> <img class="emoji emoji-1f3b3"> <img class="emoji emoji-26f3">
						<img class="emoji emoji-1f6b5"> <img class="emoji emoji-1f6b4"> <img class="emoji emoji-1f3c1"> <img class="emoji emoji-1f3c7"> <img class="emoji emoji-1f3c6"> <img class="emoji emoji-1f3bf"> <img class="emoji emoji-1f3c2">
						<img class="emoji emoji-1f3ca"> <img class="emoji emoji-1f3c4"> <img class="emoji emoji-1f3a3"> <img class="emoji emoji-2615"> <img class="emoji emoji-1f375"> <img class="emoji emoji-1f376"> <img class="emoji emoji-1f37c">
						<img class="emoji emoji-1f37a"> <img class="emoji emoji-1f37b"> <img class="emoji emoji-1f378"> <img class="emoji emoji-1f379"> <img class="emoji emoji-1f377"> <img class="emoji emoji-1f374"> <img class="emoji emoji-1f355">
						<img class="emoji emoji-1f354"> <img class="emoji emoji-1f35f"> <img class="emoji emoji-1f357"> <img class="emoji emoji-1f356"> <img class="emoji emoji-1f35d"> <img class="emoji emoji-1f35b"> <img class="emoji emoji-1f364">
						<img class="emoji emoji-1f371"> <img class="emoji emoji-1f363"> <img class="emoji emoji-1f365"> <img class="emoji emoji-1f359"> <img class="emoji emoji-1f358"> <img class="emoji emoji-1f35a"> <img class="emoji emoji-1f35c">
						<img class="emoji emoji-1f372"> <img class="emoji emoji-1f362"> <img class="emoji emoji-1f361"> <img class="emoji emoji-1f373"> <img class="emoji emoji-1f35e"> <img class="emoji emoji-1f369"> <img class="emoji emoji-1f36e">
						<img class="emoji emoji-1f366"> <img class="emoji emoji-1f368"> <img class="emoji emoji-1f367"> <img class="emoji emoji-1f382"> <img class="emoji emoji-1f370"> <img class="emoji emoji-1f36a"> <img class="emoji emoji-1f36b">
						<img class="emoji emoji-1f36c"> <img class="emoji emoji-1f36d"> <img class="emoji emoji-1f36f"> <img class="emoji emoji-1f34e"> <img class="emoji emoji-1f34f"> <img class="emoji emoji-1f34a"> <img class="emoji emoji-1f34b">
						<img class="emoji emoji-1f352"> <img class="emoji emoji-1f347"> <img class="emoji emoji-1f349"> <img class="emoji emoji-1f353"> <img class="emoji emoji-1f351"> <img class="emoji emoji-1f348"> <img class="emoji emoji-1f34c">
						<img class="emoji emoji-1f350"> <img class="emoji emoji-1f34d"> <img class="emoji emoji-1f360"> <img class="emoji emoji-1f346"> <img class="emoji emoji-1f345"> <img class="emoji emoji-1f33d">
					</div>

					<div id="emoji-places" class="emoji-tab ">
						<img class="emoji emoji-1f3e0"> <img class="emoji emoji-1f3e1"> <img class="emoji emoji-1f3eb"> <img class="emoji emoji-1f3e2"> <img class="emoji emoji-1f3e3"> <img class="emoji emoji-1f3e5"> <img class="emoji emoji-1f3e6">
						<img class="emoji emoji-1f3ea"> <img class="emoji emoji-1f3e9"> <img class="emoji emoji-1f3e8"> <img class="emoji emoji-1f492"> <img class="emoji emoji-26ea"> <img class="emoji emoji-1f3ec"> <img class="emoji emoji-1f3e4">
						<img class="emoji emoji-1f307"> <img class="emoji emoji-1f306"> <img class="emoji emoji-1f3ef"> <img class="emoji emoji-1f3f0"> <img class="emoji emoji-26fa"> <img class="emoji emoji-1f3ed"> <img class="emoji emoji-1f5fc">
						<img class="emoji emoji-1f5fe"> <img class="emoji emoji-1f5fb"> <img class="emoji emoji-1f304"> <img class="emoji emoji-1f305"> <img class="emoji emoji-1f303"> <img class="emoji emoji-1f5fd"> <img class="emoji emoji-1f309">
						<img class="emoji emoji-1f3a0"> <img class="emoji emoji-1f3a1"> <img class="emoji emoji-26f2"> <img class="emoji emoji-1f3a2"> <img class="emoji emoji-1f6a2"> <img class="emoji emoji-26f5"> <img class="emoji emoji-1f6a4">
						<img class="emoji emoji-1f6a3"> <img class="emoji emoji-2693"> <img class="emoji emoji-1f680"> <img class="emoji emoji-2708"> <img class="emoji emoji-1f4ba"> <img class="emoji emoji-1f681"> <img class="emoji emoji-1f682">
						<img class="emoji emoji-1f68a"> <img class="emoji emoji-1f689"> <img class="emoji emoji-1f69e"> <img class="emoji emoji-1f686"> <img class="emoji emoji-1f684"> <img class="emoji emoji-1f685"> <img class="emoji emoji-1f688">
						<img class="emoji emoji-1f687"> <img class="emoji emoji-1f69d"> <img class="emoji emoji-1f68b"> <img class="emoji emoji-1f683"> <img class="emoji emoji-1f68e"> <img class="emoji emoji-1f68c"> <img class="emoji emoji-1f68d">
						<img class="emoji emoji-1f699"> <img class="emoji emoji-1f698"> <img class="emoji emoji-1f697"> <img class="emoji emoji-1f695"> <img class="emoji emoji-1f696"> <img class="emoji emoji-1f69b"> <img class="emoji emoji-1f69a">
						<img class="emoji emoji-1f6a8"> <img class="emoji emoji-1f693"> <img class="emoji emoji-1f694"> <img class="emoji emoji-1f692"> <img class="emoji emoji-1f691"> <img class="emoji emoji-1f690"> <img class="emoji emoji-1f6b2">
						<img class="emoji emoji-1f6a1"> <img class="emoji emoji-1f69f"> <img class="emoji emoji-1f6a0"> <img class="emoji emoji-1f69c"> <img class="emoji emoji-1f488"> <img class="emoji emoji-1f68f"> <img class="emoji emoji-1f3ab">
						<img class="emoji emoji-1f6a6"> <img class="emoji emoji-1f6a5"> <img class="emoji emoji-26a0"> <img class="emoji emoji-1f6a7"> <img class="emoji emoji-1f530"> <img class="emoji emoji-26fd"> <img class="emoji emoji-1f3ee">
						<img class="emoji emoji-1f3b0"> <img class="emoji emoji-2668"> <img class="emoji emoji-1f5ff"> <img class="emoji emoji-1f3aa"> <img class="emoji emoji-1f3ad"> <img class="emoji emoji-1f4cd"> <img class="emoji emoji-1f6a9">
						<img class="emoji emoji-1f1ef-1f1f5"> <img class="emoji emoji-1f1f0-1f1f7"> <img class="emoji emoji-1f1e9-1f1ea"> <img class="emoji emoji-1f1e8-1f1f3"> <img class="emoji emoji-1f1fa-1f1f8"> <img class="emoji emoji-1f1eb-1f1f7">
						<img class="emoji emoji-1f1ea-1f1f8"> <img class="emoji emoji-1f1ee-1f1f9"> <img class="emoji emoji-1f1f7-1f1fa"> <img class="emoji emoji-1f1ec-1f1e7">
					</div>

					<div id="emoji-symbols" class="emoji-tab ">
						<img class="emoji emoji-0031"> <img class="emoji emoji-0032-20e3"> <img class="emoji emoji-0033-20e3"> <img class="emoji emoji-0034-20e3"> <img class="emoji emoji-0035-20e3"> <img class="emoji emoji-0036-20e3"> <img class="emoji emoji-0037-20e3">
						<img class="emoji emoji-0038-20e3"> <img class="emoji emoji-0039-20e3"> <img class="emoji emoji-0030-20e3"> <img class="emoji emoji-1f51f"> <img class="emoji emoji-1f522"> <img class="emoji emoji-0023-20e3"> <img class="emoji emoji-1f523">
						<img class="emoji emoji-2b06"> <img class="emoji emoji-2b07"> <img class="emoji emoji-2b05"> <img class="emoji emoji-27a1"> <img class="emoji emoji-1f520"> <img class="emoji emoji-1f521"> <img class="emoji emoji-1f524">
						<img class="emoji emoji-2197"> <img class="emoji emoji-2196"> <img class="emoji emoji-2198"> <img class="emoji emoji-2199"> <img class="emoji emoji-2194"> <img class="emoji emoji-2195"> <img class="emoji emoji-1f504">
						<img class="emoji emoji-25c0"> <img class="emoji emoji-25b6"> <img class="emoji emoji-1f53c"> <img class="emoji emoji-1f53d"> <img class="emoji emoji-21a9"> <img class="emoji emoji-21aa"> <img class="emoji emoji-2139">
						<img class="emoji emoji-23ea"> <img class="emoji emoji-23e9"> <img class="emoji emoji-23eb"> <img class="emoji emoji-23ec"> <img class="emoji emoji-2935"> <img class="emoji emoji-2934"> <img class="emoji emoji-1f197">
						<img class="emoji emoji-1f500"> <img class="emoji emoji-1f501"> <img class="emoji emoji-1f502"> <img class="emoji emoji-1f195"> <img class="emoji emoji-1f199"> <img class="emoji emoji-1f192"> <img class="emoji emoji-1f193">
						<img class="emoji emoji-1f196"> <img class="emoji emoji-1f4f6"> <img class="emoji emoji-1f3a6"> <img class="emoji emoji-1f201"> <img class="emoji emoji-1f22f"> <img class="emoji emoji-1f233"> <img class="emoji emoji-1f235">
						<img class="emoji emoji-1f234"> <img class="emoji emoji-1f232"> <img class="emoji emoji-1f250"> <img class="emoji emoji-1f239"> <img class="emoji emoji-1f23a"> <img class="emoji emoji-1f236"> <img class="emoji emoji-1f21a">
						<img class="emoji emoji-1f6bb"> <img class="emoji emoji-1f6b9"> <img class="emoji emoji-1f6ba"> <img class="emoji emoji-1f6bc"> <img class="emoji emoji-1f6be"> <img class="emoji emoji-1f6b0"> <img class="emoji emoji-1f6ae">
						<img class="emoji emoji-1f17f"> <img class="emoji emoji-267f"> <img class="emoji emoji-1f6ad"> <img class="emoji emoji-1f237"> <img class="emoji emoji-1f238"> <img class="emoji emoji-1f202"> <img class="emoji emoji-24c2">
						<img class="emoji emoji-1f6c2"> <img class="emoji emoji-1f6c4"> <img class="emoji emoji-1f6c5"> <img class="emoji emoji-1f6c3"> <img class="emoji emoji-1f251"> <img class="emoji emoji-3299"> <img class="emoji emoji-3297">
						<img class="emoji emoji-1f191"> <img class="emoji emoji-1f198"> <img class="emoji emoji-1f194"> <img class="emoji emoji-1f6ab"> <img class="emoji emoji-1f51e"> <img class="emoji emoji-1f4f5"> <img class="emoji emoji-1f6af">
						<img class="emoji emoji-1f6b1"> <img class="emoji emoji-1f6b3"> <img class="emoji emoji-1f6b7"> <img class="emoji emoji-1f6b8"> <img class="emoji emoji-26d4"> <img class="emoji emoji-2733"> <img class="emoji emoji-2747">
						<img class="emoji emoji-274e"> <img class="emoji emoji-2705"> <img class="emoji emoji-2734"> <img class="emoji emoji-1f49f"> <img class="emoji emoji-1f19a"> <img class="emoji emoji-1f4f3"> <img class="emoji emoji-1f4f4">
						<img class="emoji emoji-1f170"> <img class="emoji emoji-1f171"> <img class="emoji emoji-1f18e"> <img class="emoji emoji-1f17e"> <img class="emoji emoji-1f4a0"> <img class="emoji emoji-27bf"> <img class="emoji emoji-267b">
						<img class="emoji emoji-2648"> <img class="emoji emoji-2649"> <img class="emoji emoji-264a"> <img class="emoji emoji-264b"> <img class="emoji emoji-264c"> <img class="emoji emoji-264d"> <img class="emoji emoji-264e">
						<img class="emoji emoji-264f"> <img class="emoji emoji-2650"> <img class="emoji emoji-2651"> <img class="emoji emoji-2652"> <img class="emoji emoji-2653"> <img class="emoji emoji-26ce"> <img class="emoji emoji-1f52f">
						<img class="emoji emoji-1f3e7"> <img class="emoji emoji-1f4b9"> <img class="emoji emoji-1f4b2"> <img class="emoji emoji-1f4b1"> <img class="emoji emoji-00a9"> <img class="emoji emoji-00ae"> <img class="emoji emoji-2122">
						<img class="emoji emoji-303d"> <img class="emoji emoji-3030"> <img class="emoji emoji-1f51d"> <img class="emoji emoji-1f51a"> <img class="emoji emoji-1f519"> <img class="emoji emoji-1f51b"> <img class="emoji emoji-1f51c">
						<img class="emoji emoji-274c">  <img class="emoji emoji-2b55"> <img class="emoji emoji-2757"> <img class="emoji emoji-2753"> <img class="emoji emoji-2755"> <img class="emoji emoji-2754"> <img class="emoji emoji-1f503">
						<img class="emoji emoji-1f55b"> <img class="emoji emoji-1f567"> <img class="emoji emoji-1f550"> <img class="emoji emoji-1f55c"> <img class="emoji emoji-1f551"> <img class="emoji emoji-1f55d"> <img class="emoji emoji-1f552">
						<img class="emoji emoji-1f55e"> <img class="emoji emoji-1f553"> <img class="emoji emoji-1f55f"> <img class="emoji emoji-1f554"> <img class="emoji emoji-1f560"> <img class="emoji emoji-1f555"> <img class="emoji emoji-1f556">
						<img class="emoji emoji-1f557"> <img class="emoji emoji-1f558"> <img class="emoji emoji-1f559"> <img class="emoji emoji-1f55a"> <img class="emoji emoji-1f561"> <img class="emoji emoji-1f562"> <img class="emoji emoji-1f563">
						<img class="emoji emoji-1f564"> <img class="emoji emoji-1f565"> <img class="emoji emoji-1f566"> <img class="emoji emoji-2716"> <img class="emoji emoji-2795"> <img class="emoji emoji-2796"> <img class="emoji emoji-2797">
						<img class="emoji emoji-2660"> <img class="emoji emoji-2665"> <img class="emoji emoji-2663"> <img class="emoji emoji-2666"> <img class="emoji emoji-1f4ae"> <img class="emoji emoji-1f4af"> <img class="emoji emoji-2714">
						<img class="emoji emoji-2611"> <img class="emoji emoji-1f518"> <img class="emoji emoji-1f517"> <img class="emoji emoji-27b0"> <img class="emoji emoji-1f531"> <img class="emoji emoji-1f532"> <img class="emoji emoji-1f533">
						<img class="emoji emoji-25fc"> <img class="emoji emoji-25fb"> <img class="emoji emoji-25fe"> <img class="emoji emoji-25fd"> <img class="emoji emoji-25aa"> <img class="emoji emoji-25ab"> <img class="emoji emoji-1f53a">
						<img class="emoji emoji-2b1c"> <img class="emoji emoji-2b1b"> <img class="emoji emoji-26ab"> <img class="emoji emoji-26aa"> <img class="emoji emoji-1f534"> <img class="emoji emoji-1f535"> <img class="emoji emoji-1f53b">
						<img class="emoji emoji-1f536"> <img class="emoji emoji-1f537"> <img class="emoji emoji-1f538"> <img class="emoji emoji-1f539">
						<img class="emoji emoji-2049"> <img class="emoji emoji-203c">
					</div>
				
			</div>
	
			<div id="inputFieldInput">
				<input type="file" id="inputFile" name="file" />
				<textarea id="inputField"></textarea>
			</div>
			<div id="inputFieldButtons">
				<button id="cancel_button" class=" mdl-button mdl-js-ripple-effect mdl-js-button" onclick="hideInputField()" >Abbrechen</button>
				<button id="send_button" class=" mdl-button mdl-js-ripple-effect mdl-js-button mdl-color--accent" onclick="sendMessage()" >Senden</button>
			</div>
		</div>
	</div>
	</div>
	<div id="contacts" class="mdl-shadow--3dp mdl-color--primary-dark">
	<div>
	<script src="lightbox.js"></script>
</body>
</html> 
