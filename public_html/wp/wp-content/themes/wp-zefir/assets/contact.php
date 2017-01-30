<?php
require_once('../../../../wp-load.php');
$admin_email_blog = get_bloginfo('admin_email');
define("WEBMASTER_EMAIL", $admin_email_blog);
error_reporting (E_ALL); 
 
if( isset($_POST['name'], $_POST['email'], $_POST['msg']) ) {
  // get contact data
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $message = htmlspecialchars($_POST['msg']);
  $admin_email_new = htmlspecialchars($_POST['adminEmail']);
} else {
  $result = array('status'=>'false', 'err'=>__('Error', 'birdwp-theme'));
  echo json_encode($result);
  die();
}
  
// check contact data
// name
if(empty($name)) {
  $result = array('status'=>'false', 'err'=>__('Please enter your name', 'birdwp-theme'));
  echo json_encode($result);
  die();
}

// email
if(empty($email)) {
  $result = array('status'=>'false', 'err'=>__('Please enter your e-mail', 'birdwp-theme'));
  echo json_encode($result);
  die();
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $result = array('status'=>'false', 'err'=>__('E-mail is incorrect. Please enter correct e-mail', 'birdwp-theme'));
  echo json_encode($result);
  die();
}

// message
if(empty($message) || empty($message{15})) {
  $result = array('status'=>'false', 'err'=>__('Please enter message more than 15 characters', 'birdwp-theme'));
  echo json_encode($result);
  die();
}

if ($admin_email_new == 'none') {
  $admin_email = WEBMASTER_EMAIL;
} else {
  $admin_email = $admin_email_new;
}

// mail
$subject = __('Mail from Zefir theme', 'birdwp-theme');
$message = 'Name: '.$name.'
Email: '.$email.'
Subject: '.$subject.'
Message: ' .$message;

$mail = mail($admin_email, $subject, $message,
   "From: ".$name." \r\n"
  ."Reply-To: ".$email."\r\n"
  ."X-Mailer: PHP/".phpversion());

if ($mail) {
  $result = array('status'=>'true', 'err'=>'');
  echo json_encode($result);
} else {
  $result = array('status'=>'false', 'err'=>__('Failed to send email', 'birdwp-theme'));
  echo json_encode($result);
}
