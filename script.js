
var countdown;
var contacts = [];
var groups = [];
var init = 0;
var newestMessageTime = -1;
var answerID;
var answerGroup;

$(document).ready(function() {
	emoji.allow_native = false;
	emoji.use_css_imgs = true;
    window.setInterval(timer, 1000);
	$("#refresh_button").attr("disabled", "disabled");
	getContacts();
	$('#inputField').keydown(function(event) {
        if (event.shiftKey!=1 && event.keyCode == 13 && $("#inputFieldWrapper").css("visibility") === "visible" && $("#refresh_button").attr("disabled") === undefined) {
            sendMessage();
            return false;
         }
    });	
	
	$(".emoji-tab img").click(function() {
		var cursorPos = $('#inputField').prop('selectionStart'),
		v = $('#inputField').val(),
		textBefore = v.substring(0, cursorPos ),
		textAfter = v.substring(cursorPos, v.length);
		var idx = $(this).attr("class").substring($(this).attr("class").indexOf("-") + 1, $(this).attr("class").indexOf(" ", $(this).attr("class").indexOf("-")));
		$('#inputField').val(textBefore + emoji.data[idx][0][0] + textAfter );
	});
	
	$(".emoji-tab").mCustomScrollbar({
		theme: "minimal-dark"
	});
});


function answerButtonOnClick(group, id)
{
	var cssID;
	if (group)
	    cssID = "#g" + idFromJid(id);
	else
	    cssID = "#s" + idFromJid(id);
		
	if ($(cssID).length === 0)
		openChat(group, id);
	
	$(".chat_active").removeClass("chat_active");
	$(cssID).addClass("chat_active");
	
	$('html, body').clearQueue();
	$('html, body').animate({
        scrollTop: ($(cssID).offset().top - 30)
    }, 300);

	setTimeout(function() {
	 $('#inputField').focus();
	}, 0);
	
	answerGroup = group;
	answerID = id;
	showInputField();
}

function showInputField()
{
	$("#inputFieldWrapper").css("visibility", "visible");
}
	
function hideInputField()
{
	$("#inputFieldWrapper").css("visibility", "hidden");
	$(".chat_active").removeClass("chat_active");
}	

function refresh()
{
	$("#refresh_button").attr("disabled", "disabled");
	$("#refresh_button").text("Refreshing...");
	
	getMessages();
}

function restartTimer()
{
	$('#refresh_button').removeAttr("disabled");
	countdown = 10;	
}

function timer()
{
	if (countdown > 0)
	{
		countdown--;
		if (countdown === 0)
		{
			refresh();
		}
		else
		{
			$("#refresh_button").text("Autorefresh in " + countdown + " seconds");
		}
	}
}

function getContacts() 
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById('contacts').innerHTML = "";
			x = xmlhttp.responseXML.documentElement.getElementsByTagName("Contact");
			for (i = 0; i < x.length; i++)
			{
				var id = x[i].getElementsByTagName("ID")[0].textContent;
				contacts[id] = [];
				var contact = contacts[x[i].getElementsByTagName("ID")[0].textContent];
				contact["name"] = x[i].getElementsByTagName("Name")[0].textContent;
				contact["lastSeen"] = x[i].getElementsByTagName("LastSeen")[0].textContent;
				if (x[i].getElementsByTagName("Resource").length > 0)				
				{
					contact["thumbnailPicture"] = x[i].getElementsByTagName("Resource")[0].getElementsByTagName("ThumbnailPath")[0].textContent;	
					contact["picture"] = x[i].getElementsByTagName("Resource")[0].getElementsByTagName("Path")[0].textContent;	
				}
				else
				{
					contact["picture"] = "";
					contact["thumbnailPicture"] = "";
				}
				if (id != "0")
				    document.getElementById('contacts').innerHTML += "<div class=\"contact mdl-card mdl-shadow--2dp\" >" + getCodeForPicture(contact["picture"], contact["thumbnailPicture"], "contacts", contact["name"]) + "<div class=\"contact_info\" onclick=\"answerButtonOnClick(false,'" + id + "')\" ><div class=\"name\">" + contact["name"] + "</div><div class=\"status\">" + contact["lastSeen"] + "</div></div></div>";
			}
			if (init++ === 0)
				getGroups();
		}
	}
	xmlhttp.open("POST", "getContacts.php", true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send();	
}

function getGroups()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			x = xmlhttp.responseXML.documentElement.getElementsByTagName("Group");
			for (i = 0; i < x.length; i++)
			{
			    var id = x[i].getElementsByTagName("ID")[0].textContent;
			    groups[id] = [];
			    var group = groups[id];
				group["name"] = x[i].getElementsByTagName("Name")[0].textContent;				
			}
			if (init++ === 1)
				getMessages();
		}
	}
	xmlhttp.open("POST", "getGroups.php", true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send();
}

function getMessages()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{			
			x = xmlhttp.responseXML.documentElement.getElementsByTagName("Message");
			for (i = 0; i < x.length; i++)
			{
				if (x[i].getElementsByTagName("Type")[0].textContent === "group")
				{
				    if ($("#g" + idFromJid(x[i].getElementsByTagName("ChatID")[0].textContent)).length === 0)
					{
						openChat(true, x[i].getElementsByTagName("ChatID")[0].textContent);
					}
				}
				else
				{
				    if ($("#s" + idFromJid(x[i].getElementsByTagName("ChatID")[0].textContent)).length === 0)
					{
						openChat(false, x[i].getElementsByTagName("ChatID")[0].textContent);
					}
				}
			}
			for (i = 0; i < x.length; i++)
			{ 
				var type, thumbnailPath, path;
				if (x[i].getElementsByTagName("Resource").length > 0)				
				{
					type = x[i].getElementsByTagName("Resource")[0].getElementsByTagName("Type")[0].textContent;
					thumbnailPath = x[i].getElementsByTagName("Resource")[0].getElementsByTagName("ThumbnailPath")[0].textContent;
					path = x[i].getElementsByTagName("Resource")[0].getElementsByTagName("Path")[0].textContent;
				}
				else
				{
					type = "";
					thumbnailPath = "";
					path = "";
				}
				addMessage(x[i].getElementsByTagName("Type")[0].textContent === "group", x[i].getElementsByTagName("ChatID")[0].textContent, x[i].getElementsByTagName("ID")[0].textContent, x[i].getElementsByTagName("Text")[0].textContent, x[i].getElementsByTagName("Sender")[0].textContent, x[i].getElementsByTagName("Time")[0].textContent, x[i].getElementsByTagName("Status")[0].textContent, type, path, thumbnailPath);
				newestMessageTime = x[i].getElementsByTagName("Timestamp")[0].textContent;
			}
			
			restartTimer();
		}
	}
	xmlhttp.open("POST", "getMessages.php", true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("newestMessageTime=" + newestMessageTime);
}

function addMessage(group, chatID, messageID, text, senderID, time, status, resourceType, resourcePath, resourceThumbnailPath)
{
	var newMessage = "<div id=\"m" + messageID + "\" class=\"mdl-card mdl-shadow--2dp message ";
	if (senderID == 0)
	{
		newMessage += "message_own\"";
		newMessage += " ><div class=\"message_header mdl-card__title \">";
	}
	else
	{
		newMessage += "message_other\"";
		newMessage += " ><div class=\"message_header mdl-card__title mdl-color--primary-dark\">";
	}
	
	newMessage += contacts[senderID]["name"] + "<div class = \"message-status\"><span class=\"message-status-read message-status-off\">I</span><span class=\"message-status-received message-status-off\">I</span><span  class=\"message-status-send message-status-off\">I</span></div>";
	newMessage += "</div><div class=\"message_body mdl-color-text--grey-700 mdl-card__supporting-text\">";
	if (resourceType === "picture")
	{
		newMessage += "<div class=\"message_resource\">" + getCodeForPicture(resourcePath, resourceThumbnailPath, "message" + chatID, "Von " + contacts[senderID]["name"]) + "</div>";
	}

	newMessage += "<div>" + emoji.replace_unified(text) + "</div>";
	newMessage += "<div class=\"message-time\">" + time + "</div>";
	newMessage += "</div></div>";
	
	var chat;
	if (group) 
	    chat = "#g" + idFromJid(chatID) + " .messages";
	else
	    chat = "#s" + idFromJid(chatID) + " .messages"
	$(chat).append(newMessage);

	setStatusOfMessage(messageID, status);

	$(chat).clearQueue();
	var offset = $(chat).children().last().offset().left - $(chat).children().first().offset().left;	
	$(chat).animate({ 
        scrollLeft: offset
    }, 1000);
}

function setStatusOfMessage(messageID, status)
{
    if (status >= 1)    
        $("#m" + messageID + " .message-status-send").addClass("message-status-on").removeClass("message-status-off");
    if (status >= 2)
        $("#m" + messageID + " .message-status-received").addClass("message-status-on").removeClass("message-status-off");
    if (status >= 3)
        $("#m" + messageID + " .message-status-read").addClass("message-status-on").removeClass("message-status-off");
}

function openChat(group, id)
{
    var newChat = "<div class=\"mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col chat\" id=\"" + (group ? "g" : "s") + idFromJid(id) + "\"><div class=\"mdl-color--primary-dark chat_description\">";
	if (group === false)
	{		
		newChat += "<h4>" + contacts[id]["name"] + "</h4>";
		newChat += "<div class=\"chat_picture\">" + getCodeForPicture(contacts[id]["picture"], contacts[id]["thumbnailPicture"] , "chats", contacts[id]["name"]) + "</div>";
		newChat += "<p>Status:<br>" + contacts[id]["status"] + "</p>";
		newChat += "<p>Nummer:<br>" + contacts[id]["number"] + "</p>";
		newChat += "<p>" + contacts[id]["lastSeen"] + "</p>";		
	}
	else
	{
		newChat += "<h4>" + groups[id]["name"] + "</h4>";
		newChat += "<p>Bild</p>";
	}
	newChat += "</div><button class=\"answer_button mdl-button mdl-js-ripple-effect mdl-js-button mdl-button--fab mdl-color--accent\" ";
	newChat += "onclick=\"answerButtonOnClick(" + group + ",'" + id + "')\"";
	newChat +=" ><i class=\"material-icons mdl-color-text--white\" role=\"presentation\">reply</i><span class=\"visuallyhidden\">reply</span></button><div class=\"mdl-card__supporting-text messages\"></div></div>";	
	$("#chats").prepend(newChat);
}

function sendMessage()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("inputField").value = "";
			getMessages();
			hideInputField();
		}
		$('#refresh_button').removeAttr("disabled");
	}
	xmlhttp.open("POST","sendMessage.php",true);
//	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	var message = document.getElementById("inputField").value;
	message = message.replace(/\n/g, "<br />");
	
	var formData = new FormData();
	formData.append("id", answerID);
	formData.append("group", answerGroup);
	formData.append("message", message);
	formData.append("file", document.getElementById("inputFile").files[0]);
	
	xmlhttp.send(formData);
	$("#refresh_button").attr("disabled", "disabled");
}

function getCodeForPicture(path, pathThumbnail, group, title)
{
	return "<a href=\"" + path + "\" data-lightbox=\"" + group + "\" data-title=\"" + title + "\"><div class=\"mdl-card mdl-shadow--2dp card-image mdl-cell\" style=\"background: url(" + pathThumbnail + ")\"><div class=\"mdl-card__title mdl-card--expand\"></div></div></a>";
}

function showEmoji(category)
{
	$(".emoji-tab").css("display", "none");
	$("#emoji-" + category).css("display", "block");
}

function idFromJid(jid)
{
    return jid.substring(0, jid.indexOf("@"));
}