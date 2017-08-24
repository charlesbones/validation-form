<?php
  if($_POST["submit"]) {
    if(isset($_FILES) && (bool) $_FILES){
      $allowedExtensions = array("pdf","jpg","gif","png","tiff","zip");
      foreach ($_FILES as $name => $file) {
        $file_name = $file['name'];
        $temp_name = $file['temp_name'];

        $path_parts = pathinfo($file_name);
        $ext = $path_parts['extension'];
        if(!in_array($ext,$allowedExtensions)){
          die("extension not allowed");
        }
        $server_file="/files/$path_parts[basename]";
        move_uploaded_file($temp_name,$server_file);
        array_push($files,$server_file);
      }
    }
    $to = "c.rodriguez@bcmi-labs.cc"
    $from = $_POST["sender1"];
    $subject = "CTC validation form $date[month] $date[mday] $date[year]";
    $message = "hola";
    $headers = "From: $from";

    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"

    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

  	// multipart boundary
  	$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
  	$message .= "--{$mime_boundary}\n";

  	// preparing attachments
  	for($x=0;$x<count($files);$x++){
  		$file = fopen($files[$x]['tmp_name'],"rb");
  		$data = fread($file,filesize($files[$x]['tmp_name']));
  		fclose($file);
  		$data = chunk_split(base64_encode($data));
  		$name = $files[$x]['name'];
  		$message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$name\"\n" .
  		"Content-Disposition: attachment;\n" . " filename=\"$name\"\n" .
  		"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
  		$message .= "--{$mime_boundary}\n";
  	}
  	// send

  	$ok = mail($to, $subject, $message, $headers);
  	if ($ok) {
  		echo "<p>mail sent to $to!</p>";
  	} else {
  		echo "<p>mail could not be sent!</p>";
  	} 

    /*$recipient="ctc.101@arduino.cc";
    $date=getdate(date("U"));
    $subject="CTC validation form $date[month] $date[mday] $date[year]";
    $sender=$_POST["name1"];
    $senderEmail=$_POST["sender1"];
    $schoolName=$_POST["school"];
    $country=$_POST["country"];
    $state=$_POST["state"];
    $message=$_POST["message"];

    $mailBody="Name: $sender\nEmail: $senderEmail\n\n$message";

    mail($recipient, $subject, $mailBody, "From: $sender <$senderEmail>");

    $thankYou="<p>Thank you! Your message has been sent.</p>";*/
  }
 ?>
