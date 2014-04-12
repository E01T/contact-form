<?php session_start();

if ( isset($_GET['fullname']) && trim($_GET['fullname']) !== '' ){
	$_SESSION['fullname'] = true;

	echo $_GET['fullname'];
}

// if ( isset($_GET['email']) && trim($_GET['email']) !== '' ){
// 	$email = TRUE;
// 	echo $_GET['email'];
// }

// if ( isset($_GET['subject']) && trim($_GET['subject']) !== '' ){
// 	$subject = TRUE;
// 	echo $_GET['subject'];
// }

// if ( isset($_GET['message']) && trim($_GET['message']) !== '' ){
// 	$message = TRUE;
// 	echo $_GET['message'];
// }

if ( (isset($_SESSION['fullname']) &&  $_SESSION['fullname'] == true) && isset($_REQUEST['code'])  /* && $email && $subject && $messge */ ) { // if all of them are true
require_once '/securimage/securimage.php';
$sec = new Securimage();
// echo 'something';
$request = trim(strtolower($_REQUEST['code']));
// echo $request . " -> " . $sec->check($request, true);
// // $options = array('no_session' => true );
	if ( $sec->check($request, true) ) {
		echo 'true';
		if(isset($_SESSION['fullname']))
  		unset($_SESSION['fullname']);
	}else 
		echo 'false';

		// Unset the sessions
		


		/*
		|--------------------------------------------------------------------------
		| Mailer module
		|--------------------------------------------------------------------------
		|
		| These module are used when sending email from contact form
		|
		*/

		//Get your email address
		// $contact_email = ot_get_option('contact_form_your_email');

		// // Message to the user
		// $output_message = '';
		
		// //Enter your email address, email from contact form will send to this addresss. Please enter inside quotes ('myemail@email.com')
		// define('DEST_EMAIL', $contact_email);
		
		// //Change email subject to something more meaningful
		// define('SUBJECT_EMAIL', __( 'Email from contact form', THEMEDOMAIN ));
		
		// //Thankyou message when message sent
		// define('THANKYOU_MESSAGE', __( 'Thank you! We will get back to you as soon as possible', THEMEDOMAIN ));
		
		// //Error message when message can't send
		// define('ERROR_MESSAGE', __( 'Oops! something went wrong, please try to submit later.', THEMEDOMAIN ));

		// $output_message = ERROR_MESSAGE;
		// echo $output_message;

		/*
		|
		| Begin sending mail
		|
		*/
		
		// $from_name  = trim($_GET['fullname']);
		// $from_email = trim($_GET['email']);
		
		// $headers = "";
	 //   	$headers.= 'From: '.$from_name.'<'.$from_email.'>'.PHP_EOL;
	 //   	$headers.= 'Reply-To: '.$from_name.'<'.$from_email.'>'.PHP_EOL;
	 //   	$headers.= 'Return-Path: '.$from_name.'<'.$from_email.'>'.PHP_EOL;        // these two to set reply address
	 //   	$headers.= "Message-ID: <".time()."webmaster@".$_SERVER['SERVER_NAME'].">".PHP_EOL;
	 //   	$headers.= "X-Mailer: PHP v".phpversion().PHP_EOL;                  // These two to help avoid spam-filters
		
		// $message = 'Name: '.$from_name.PHP_EOL;
		// $message.= 'Email: '.$from_email.PHP_EOL.PHP_EOL;
		// $message.= 'Message: '.PHP_EOL. trim($_GET['message']);

		// if(!empty($from_name) && !empty($from_email) && !empty($message))
		// { 
		// 	if (isset($_GET['subject']))
		// 		wp_mail(DEST_EMAIL, $_GET['subject'], $message, $headers);
		// 	else
		// 		wp_mail(DEST_EMAIL, SUBJECT_EMAIL, $message, $headers);

		// 	// $emailSent = true;
		// 	$output_message = THANKYOU_MESSAGE;
		// 	echo $output_message;
		// 	// echo THANKYOU_MESSAGE;
		// }
		// else
		// {
		// 	// $emailSent = true;
		// 	$output_message = ERROR_MESSAGE;
		// 	echo $output_message;
		// 	// echo ERROR_MESSAGE;
			
		// 	// exit;
		// }

	// }

}

