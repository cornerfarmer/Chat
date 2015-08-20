
var countdown;
var contacts = [];
var groups = [];
var lastCheckTime = -1;
var answerID;
var answerGroup;

$(document).ready(function() {
	emoji.allow_native = false;
	emoji.use_css_imgs = true;
	window.setInterval(timer, 1000);
	countdown = 1;
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
	
	getNews();
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

function getContentOfXMLTag(parent, tagName)
{
    return parent.getElementsByTagName(tagName)[0].textContent
}

function getNews()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		    documentXML = xmlhttp.responseXML.documentElement;
		    lastCheckTime = getContentOfXMLTag(documentXML, "LastCheckTime");
		    messages = documentXML.getElementsByTagName("Message");
		    // Contacts
		    contactsXML = documentXML.getElementsByTagName("Contact");
		    for (i = 0; i < contactsXML.length; i++) {
		        var id = getContentOfXMLTag(contactsXML[i], "ID");
		        contacts[id] = [];
		        contacts[id]["id"] = id;
		        contacts[id]["name"] = getContentOfXMLTag(contactsXML[i], "Name");
		        contacts[id]["lastSeen"] = getContentOfXMLTag(contactsXML[i], "LastSeen");
		        contacts[id]["status"] = getContentOfXMLTag(contactsXML[i], "Status");
		        resource = contactsXML[i].getElementsByTagName("Resource");
		        if (resource.length > 0) {
		            contacts[id]["thumbnailPicture"] = getContentOfXMLTag(resource[0], "ThumbnailPath");
		            contacts[id]["picture"] = getContentOfXMLTag(resource[0], "Path");
		        }
		        else {
		            contacts[id]["picture"] = "";
		            contacts[id]["thumbnailPicture"] = "";
		        }
		        if (id != "0")
		            addContact(contacts[id]);
		    }
		    // Groups
		    groupsXML = documentXML.getElementsByTagName("Group");
		    for (i = 0; i < groupsXML.length; i++) {
		        var id = getContentOfXMLTag(groupsXML[i], "ID");
		        groups[id] = [];
		        groups[id]["name"] = getContentOfXMLTag(groupsXML[i], "Name");
		    }
            // Open chats
		    for (i = 0; i < messages.length; i++)
		    {
		        id = getContentOfXMLTag(messages[i], "ChatID");
		        if (getContentOfXMLTag(messages[i], "Type") === "group")
				{
		            if ($("#g" + idFromJid(id)).length === 0)
					{
		                openChat(true, id);
					}
				}
				else
				{
		            if ($("#s" + idFromJid(id)).length === 0)
					{
		                openChat(false, id);
					}
				}
		    }
            // Open messages
		    for (i = 0; i < messages.length; i++)
			{ 
		        message = [];
		        resource = messages[i].getElementsByTagName("Resource");
		        if (resource.length > 0)
				{
		            message["resourceType"] = getContentOfXMLTag(resource[0], "Type");
		            message["resourceThumbnailPath"] = getContentOfXMLTag(resource[0], "ThumbnailPath");
		            message["resourcePath"] = getContentOfXMLTag(resource[0], "Path");
				}
				else
				{
		            message["resourceType"] = "";
		            message["resourceThumbnailPath"] = "";
		            message["resourcePath"] = "";
		        }
		        message["group"] = getContentOfXMLTag(messages[i], "Type") === "group";
		        message["chatID"] = getContentOfXMLTag(messages[i], "ChatID");
		        message["messageID"] = getContentOfXMLTag(messages[i], "ID");
		        message["text"] = getContentOfXMLTag(messages[i], "Text");
		        message["senderID"] = getContentOfXMLTag(messages[i], "Sender");
		        message["time"] = getContentOfXMLTag(messages[i], "Time");
		        message["status"] = getContentOfXMLTag(messages[i], "Status");
		        message["status"] = getContentOfXMLTag(messages[i], "Status");
		        addMessage(message);		        
		    }
            // Change Message Status
		    messageStatusChanges = documentXML.getElementsByTagName("MessageStatus");
		    for (i = 0; i < messageStatusChanges.length; i++)
		    {
		        setStatusOfMessage(getContentOfXMLTag(messageStatusChanges[i], "ID"), getContentOfXMLTag(messageStatusChanges[i], "Status"));
		    }
		    
			restartTimer();
		}
	}
	xmlhttp.open("POST", "getNews.php", true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("lastCheckTime=" + lastCheckTime);
}

function addContact(contact)
{
    document.getElementById('contacts').innerHTML += "<div class=\"contact mdl-card mdl-shadow--2dp\" >" + getCodeForPicture(contact["picture"], contact["thumbnailPicture"], "contacts", contact["name"]) + "<div class=\"contact_info\" onclick=\"answerButtonOnClick(false,'" + contact["id"] + "')\" ><div class=\"name\">" + contact["name"] + "</div><div class=\"status\">" + contact["lastSeen"] + "</div></div></div>";
}

function addMessage(message)
{
    var newMessage = "<div id=\"m" + message["messageID"] + "\" class=\"mdl-card mdl-shadow--2dp message ";
    if (message["senderID"] == 0)
	{
		newMessage += "message_own\"";
		newMessage += " ><div class=\"message_header mdl-card__title \">";
	}
	else
	{
		newMessage += "message_other\"";
		newMessage += " ><div class=\"message_header mdl-card__title mdl-color--primary-dark\">";
	}
	
	newMessage += contacts[message["senderID"]]["name"] + "<div class = \"message-status\"><span class=\"message-status-read message-status-off\">I</span><span class=\"message-status-received message-status-off\">I</span><span  class=\"message-status-send message-status-off\">I</span></div>";
	newMessage += "</div><div class=\"message_body mdl-color-text--grey-700 mdl-card__supporting-text\">";
	if (message["resourceType"] === "picture")
	{
	    newMessage += "<div class=\"message_resource\">" + getCodeForPicture(message["resourcePath"], message["resourceThumbnailPath"], "message" + message["chatID"], "Von " + contacts[message["senderID"]]["name"]) + "</div>";
	}

	newMessage += "<div>" + emoji.replace_unified(message["text"]) + "</div>";
	newMessage += "<div class=\"message-time\">" + message["time"] + "</div>";
	newMessage += "</div></div>";
	
	var chat;
	if (message["group"])
	    chat = "#g" + idFromJid(message["chatID"]) + " .messages";
	else
	    chat = "#s" + idFromJid(message["chatID"]) + " .messages"
	$(chat).append(newMessage);

	setStatusOfMessage(message["messageID"], message["status"]);

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
		newChat += "<p>Nummer:<br>+" + idFromJid(id) + "</p>";
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
			getNews();
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