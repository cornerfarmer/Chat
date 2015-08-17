<?php
		
	require_once(__DIR__.'/include/sqAES.php');
	require_once(__DIR__.'/include/jcryption.php');
	
	

	$jc = new JCryption('rsa_1024_pub.pem', 'rsa_1024_priv.pem');
	$jc->go();
	if ($_POST["file"])
	{
		$data = $_POST["file"];
		list($type, $data) = explode(';', $data);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);

		file_put_contents('test.png', $data);
	}
	header('Content-type: text/plain');
	if ($_FILES["file"] !== NULL)
	{
		$filename = "./include/" . (string)sha1_file($_FILES['file']['tmp_name']) . ".png";
		move_uploaded_file($_FILES['file']['tmp_name'], $filename);
	}
	
	$data = file_get_contents('test.png');
	$data = base64_encode($data);
	echo $jc->decryptText($data);

?>