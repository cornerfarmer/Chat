
<!DOCTYPE html>

<html lang="en">

<head>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script type="text/javascript" src="include/jquery.jcryption.3.1.0.js"></script>

    <script>
    $('document').ready(function(){

		var password = $.jCryption.encrypt("XXXX", "turbo");

		$.jCryption.authenticate(password, "answer.php?getPublicKey=true", "answer.php?handshake=true", function(AESKey) {
			alert('Let\'s Rock!');
		}, function() {
			// Authentication failed
		});
		
       $('#ajaxtest').click(function(){
		    var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					$('body').append("<img src=\"data:image/png;base64," + ($.jCryption.decrypt(xmlhttp.responseText, password)) + "\">");
				}
			}
			xmlhttp.open("POST","answer.php",true);
			
			var formData = new FormData();
			var encryptedString = $.jCryption.encrypt(document.getElementById("textarea_field").value, password);
			formData.append("text", encryptedString);
			var reader = new FileReader();
			reader.readAsDataURL(document.getElementById("inputFile").files[0]);
			reader.onload = (function(theFile) {
				var test = reader.result;
				formData.append("file", $.jCryption.encrypt(test, password));
				xmlhttp.send(formData);
			})
			
		});
	});   
	
	</script>
	<title>NoSSL demo</title>
</head>
<body>
	<input type="file" id="inputFile" name="file" />
	Textarea: <textarea id="textarea_field">Please enter something...</textarea><br />			
    <button id="ajaxtest">Test jQuery-Ajax</button>
</body>
</html>
