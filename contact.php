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
    $recipient="ctc.101@arduino.cc";
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

    $thankYou="<p>Thank you! Your message has been sent.</p>";
  }
 ?>
