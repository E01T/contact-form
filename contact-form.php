<?php // session_start(); // This is for secure image
/**
 * Template Name: contact-form
 * The main template file for display contact page.
 *
 * @package WordPress
*/
// $options = get_option('option_tree');
// require_once '/captcha_check.php';
// require_once '/securimage/securimage.php';
// $sec = new Securimage();
// $request = trim(strtolower($_REQUEST['code']));
// $sec = new Securimage();
// if ($sec->check($request) == true) {
//     echo "true";
// } else {
//     echo "false";
// }
// }else if ( $_POST['captcha_code']) )

function display_error($error_array = array() ,$error_name = '' ){
	if ( isset($error_array) )
		return array_key_exists($error_name, $error_array)? $error_array[$error_name] : "";
}

function display_message($message) {
	return $message;
}

$ancor_tag = '#contact_form';

if ( isset($_POST['submit']) ){

	$message = array('fullname' => __("NAME EMPTY ASSHOLE!!!", "base_template_translate"), 
					 'email'    => __("EMAIL EMPTY ASSHOLE!!!", "base_template_translate"), 
					 'subject'  => __("SUBJECT EMPTY ASSHOLE!!!", "base_template_translate"), 
					 'address'  => __("ADDRESS EMPTY ASSHOLE!!!", "base_template_translate"), 
					 'phone'    => __("PHONE EMPTY ASSHOLE!!!", "base_template_translate"), 
					 'mobile'   => __("MOBILE EMPTY ASSHOLE!!!", "base_template_translate"), 
					 'company'  => __("COMPANY EMPTY ASSHOLE!!!", "base_template_translate"), 
					 'country'  => __("COUNTRY EMPTY ASSHOLE!!!", "base_template_translate"), 
					 'message'  => __("MESSAGE EMPTY ASSHOLE!!!", "base_template_translate"),
					 'captcha_code' => __("CAPTCHA EMPTY ASSHOLE!!!", "base_template_translate"),
					 );

	$errors = array();

	// IF EMPTY
	foreach ($_POST as $key => $value) {
		// echo $key . ": " . $value . "<br>";

		if ( isset($_POST[$key]) && trim($_POST[$key]) === '' ){
			if ( ($key === 'fullname') ){
		 			$errors['error_' . $key] =  $message[$key];
		 			// echo $errors['error_' . $key]. "<br>";
		 			// echo gettype($key);
		 	}
		 	if ( ($key === 'email') ){
					$errors['error_'. $key] =  $message[$key];
					// echo $errors['error_' . $key]. "<br>";
			}
			if ( ($key === 'message') ) {
					$errors['error_' . $key] =  $message[$key];
					// echo $errors['error_' . $key]. "<br>";
			}
			if ( ($key === 'captcha_code') ) {
					$errors['error_' . $key] =  $message[$key];
					// echo $errors['error_' . $key]. "<br>";
			}
			if ( ot_get_option("required_". $key)[0] == 'yes' ) {
					$errors['error_' . $key] =  $message[$key];
			}
		}
	}

	require_once '/securimage/securimage.php';
	// CAPTHCA CODE
	if ( empty($errors['error_captcha_code']) ){
		
		$securimage = new Securimage();
		if ($securimage->check($_POST['captcha_code']) == false) {
		  	// the code was incorrect
		  	// you should handle the error so that the form processor doesn't continue

		  	// or you can use the following code if there is no validation or you do not know how
		  	$errors['error_captcha'] = __("The security code entered was incorrect. Please try again", "base_template_translate") ;
		  	// echo "The security code entered was incorrect.<br /><br />";
		  	// echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
		  	//exit;
		}
	}


	if ( empty($errors)) {

		// Needs further testing
		// http://blog.techwheels.net/send-email-from-localhost-wamp-server-using-sendmail/
		// AND USING PEAR MAIL
		// http://email.about.com/od/emailprogrammingtips/qt/PHP_Email_SMTP_Authentication.htm

		/*
		|--------------------------------------------------------------------------
		| Mailer module
		|--------------------------------------------------------------------------
		|
		| These module are used when sending email from contact form
		|
		*/
		
		//Get your email address
		$contact_email = ot_get_option('contact_form_your_email');

		// Message to the user
		$output_message = '';
		
		//Enter your email address, email from contact form will send to this addresss. Please enter inside quotes ('myemail@email.com')
		define('DEST_EMAIL', $contact_email);
		
		//Change email subject to something more meaningful
		define('SUBJECT_EMAIL', __( 'Email from contact form', THEMEDOMAIN ));
		
		//Thankyou message when message sent
		define('THANKYOU_MESSAGE', __( 'Thank you! We will get back to you as soon as possible', THEMEDOMAIN ));
		
		//Error message when message can't send
		define('ERROR_MESSAGE', __( 'Oops! something went wrong, please try to submit later.', THEMEDOMAIN ));
		
		
		/*
		|
		| Begin sending mail
		|
		*/
		
		$from_name  = trim($_POST['fullname']);
		$from_email = trim($_POST['email']);
		
		$headers = "";
	   	$headers.= 'From: '.$from_name.'<'.$from_email.'>'.PHP_EOL;
	   	$headers.= 'Reply-To: '.$from_name.'<'.$from_email.'>'.PHP_EOL;
	   	$headers.= 'Return-Path: '.$from_name.'<'.$from_email.'>'.PHP_EOL;        // these two to set reply address
	   	$headers.= "Message-ID: <".time()."webmaster@".$_SERVER['SERVER_NAME'].">".PHP_EOL;
	   	$headers.= "X-Mailer: PHP v".phpversion().PHP_EOL;                  // These two to help avoid spam-filters
		
		$message = 'Name: '.$from_name.PHP_EOL;
		$message.= 'Email: '.$from_email.PHP_EOL.PHP_EOL;
		$message.= 'Message: '.PHP_EOL. trim($_POST['message']);

		if(isset($_POST['address']))
		{
			$message.= 'Address: '.$_POST['address'].PHP_EOL;
		}
		
		if(isset($_POST['phone']))
		{
			$message.= 'Phone: '.$_POST['phone'].PHP_EOL;
		}
		
		if(isset($_POST['mobile']))
		{
			$message.= 'Mobile: '.$_POST['mobile'].PHP_EOL;
		}
		
		if(isset($_POST['company']))
		{
			$message.= 'Company: '.$_POST['company'].PHP_EOL;
		}
		
		if(isset($_POST['country']))
		{
			$message.= 'Country: '.$_POST['country'].PHP_EOL;
		}
		    
		
		if(!empty($from_name) && !empty($from_email) && !empty($message))
		{ 
			if (isset($_POST['subject']))
				wp_mail(DEST_EMAIL, $_POST['subject'], $message, $headers);
			else
				wp_mail(DEST_EMAIL, SUBJECT_EMAIL, $message, $headers);

			// $emailSent = true;
			$output_message = THANKYOU_MESSAGE;
			// echo THANKYOU_MESSAGE;
			foreach ($_POST as $key => $value) {
				$_POST[$key] = '';
			}
			
			// exit;
		}
		else
		{
			// $emailSent = true;
			$output_message = ERROR_MESSAGE;
			// echo ERROR_MESSAGE;
			
			// exit;
		}
		
		/*
		|
		| End sending mail
		|
		*/

	}

}

// global $emailSent;
// $emailSent = true;
// $contact_form_your_email = ot_get_option('contact_form_your_email');
// if(isset($emailSent) && $emailSent == true) { 
// 	// $output_message = "TestTestTest";
// 	$html  = '<div id="contact_form" class="thanks">';
// 	$html .= $output_message;
// 	$html .= '</div>';
// 	echo $html;

// } else { 
	// print_r($errors);
	// print_r($an_array);

	// consider put it in a js file

	// http://stackoverflow.com/questions/2457032/jquery-validation-change-default-error-message


	// echo'<script>
	// $(document).ready(function(){
	// 	$("#contactForm").bind("keyup keypress", function(e) {
	// 									  var code = e.keyCode || e.which; 
	// 									  if (code  == 13) {               
	// 									    e.preventDefault();
	// 									    return false;
	// 									  }
	// 									});
	// });
	// </script>';

	echo'<script>
	$(document).ready(function(){
		jQuery.validator.addMethod( "captcha_function", function(value, element) {
							        // var result = true;
							        if (value.length < 6 || value.length > 6) { 
							            $.validator.messages.captcha_function = "Required: Captcha code is 6 characters";
							            return false;
							        } else {
							            var myCaptcha = $.ajax({ type: "GET", url: "'  . get_template_directory_uri() . '/captcha_check.php?code="+value, async: false }).responseText;
							            if (myCaptcha === "false") {
							                $.validator.messages.captcha_function = "Captcha code is invalid, please try the new code.";
							                $("#captchaRefreshLink").click();  
							                return false;
										} else if (myCaptcha === "true") {
											// $("#contactForm").hide();
											$("#contactForm").fadeOut(3000,"swing");
											alert("Thank you for your message");
											return true;
										} else if (myCaptcha === "") {
											$.validator.messages.captcha_function = "Fill in the required fileds";
											return false;
										}
							        }     
							        // return result;  
							    },   
							    "captcha invalid"
		);   
	});
	</script>';	
	
	echo'<script>
	$(document).ready(function(){
		jQuery.validator.addMethod( "fullname_ajax", function(value, element) {

									// if( value.length == 0) {
									// 	element.value="";
									// 	$.ajax({ type: "GET", url: "'  . get_template_directory_uri() . '/captcha_check.php?fullname="+ ""});
									// }
									// else {
									    var fullname = $.ajax({ type: "GET", url: "'  . get_template_directory_uri() . '/captcha_check.php?fullname="+value, async: true }).responseText;
									    if (fullname == "false") {
									    	$.validator.messages.fullname_ajax = "Please enter your full name.";  
							                return false;
								        } else {
								        	// element.disabled=true;
								        	return true;
								        }
							        // }
						        
							    },   
							    "Name invalid"
		);   
	});
	</script>';	


	echo '<script> $(document).ready(function(){
						jQuery.validator.addMethod("textOnly", 
                        	function(value, element) {
                            	return !/[0-9]+/.test(value);
                            }, 
                            "Alpha Characters Only."
       					);
					});
		</script>';

	echo '<script> 
				$(document).ready(function(){
						$("#contactForm" ).validate({
  							rules: {
    							captcha_code: {
      								required: true,
      								captcha_function: true
      							},
      							fullname:{
      								textOnly: true,
      								fullname_ajax: true
      							}
  							},
  							submitHandler: function(form) {
			                    form.submit();
			                }

						});
				});
		</script>';

	// 	echo '<script>
	// 		$(document).ready(function(){
    
 //        //custom validation rule - text only
 //        $.validator.addMethod("textOnly", 
 //                              function(value, element) {
 //                                  return !/[0-9]+/.test(value);
 //                              }, 
 //                              "Alpha Characters Only."
 //       );
       
 //       //validation implementation will go here.
 //       $("#contactForm").validate({
 //           rules: {
 //               name: {
 //                   required: true,
 //                   textOnly: true
 //               }
 //           },
 //           messages: {
 //               name: {
 //                  required: "* Required"
 //               }
 //           }    
 //       });
 //   })
	// </script>';


 	// If captcha is enabled throw this script

 	if ( ot_get_option('contact_form_captcha_check') == 'on' ){
 		echo "<script>function get_captcha(){ document.getElementById('captcha').src += '?sid=' + Math.random(); this.blur(); return false; }</script>";
 		echo "<script>$(document).ready(function(){
 				$('#submit').hide();
 				});
 			  </script>";
 	}
 	// $securecode = $sec->getCode();
 	// echo "<br>" . $securecode;
 	print_r( $_SESSION['securimage_code_disp']  );
 	print_r( $_SESSION['securimage_code_value'] );
 	print_r($_SESSION['securimage']);
 	echo "<br>Session fullname: ";
 	print_r($_SESSION['fullname']);
 	echo "<br>";
 	
	$html  = '<div id="contact_form">';
	if (isset($output_message) && $output_message !== "") 
		echo $output_message . "<br>"; 
 
	$html .= '<form action="' . home_url('/') . $ancor_tag . '" id="contactForm" method="post">';
	$html .= __("* Denotes required field", "base_template_translate") . "<hr>";

	$html .= '<label for="name">';
	$html .= __("Name: *", "base_template_translate");
	$html .= '<span>' . display_error($errors, "error_fullname")  . '</span>';
	$html .= '<input type="text" id="name" class="required" name="fullname" value="' . htmlspecialchars(trim($_POST['fullname'])) . '" /></label><br>';
	
	$html .= '<label for="email" >';
	$html .= __("Email: *", "base_template_translate");
	$html .= '<span>' . display_error($errors, "error_email")  . '</span>';
	$html .= '<input type="email" id="email" class="required" name="email" value="' . htmlspecialchars(trim($_POST['email'])) . '" /></label><br>';

	if ( ot_get_option('contact_form_subject_check')[0] == 'yes' ) {
		$html .= '<label for="subject" >';
		$html .= __("Subject: ", "base_template_translate");
		if (ot_get_option("required_subject")[0] == 'yes') {
			$html .= '<span>' . display_error($errors, "error_subject")  . '</span>';
			$html .= '*<input type="text" id="subject" class="required" name="subject" value="' . htmlspecialchars(trim($_POST['subject'])) . '" />';
		}else
			$html .= '<input type="text" id="subject" name="subject" value="' . htmlspecialchars(trim($_POST['subject'])) . '" />';
		$html .= '</label><br>';
	}

	if ( ot_get_option('contact_form_address_check')[0] == 'yes' ) {
		$html .= '<label for="address" >';
		$html .= __("Address: ", "base_template_translate");
		if (ot_get_option("required_address")[0] == 'yes') {
			$html .= '<span>' . display_error($errors, "error_address")  . '</span>';
			$html .= '*<input type="text" id="address" class="required" name="address" value="' . htmlspecialchars(trim($_POST['address'])) . '" />';
		}else
			$html .= '<input type="text" id="address" name="address" value="' . htmlspecialchars(trim($_POST['address'])) . '" />';
		$html .= '</label><br>';
	}

	if ( ot_get_option('contact_form_phone_check')[0] == 'yes' ) {
		$html .= '<label for="phone" >';
		$html .= __("Phone: ", "base_template_translate");
		if (ot_get_option("required_phone")[0] == 'yes') {
			$html .= '<span>' . display_error($errors, "error_phone")  . '</span>';
			$html .= '*<input type="tel" id="phone" class="required" name="phone" value="' . htmlspecialchars(trim($_POST['phone'])) . '" />';
		}else
			$html .= '<input type="tel" id="phone" name="phone" value="' . htmlspecialchars(trim($_POST['phone'])) . '" />';
		$html .= '</label><br>';
	}
	
	if ( ot_get_option('contact_form_mobile_check')[0] == 'yes' ) {
		$html .= '<label for="mobile" >';
		$html .= __("Mobile: ", "base_template_translate");
		if (ot_get_option("required_mobile")[0] == 'yes') {
			$html .= '<span>' . display_error($errors, "error_mobile")  . '</span>';
			$html .= '*<input type="tel" id="mobile" class="required" name="mobile" value="' . htmlspecialchars(trim($_POST['mobile'])) . '" />';
		}else
			$html .= '<input type="tel" id="mobile" name="mobile" value="' . htmlspecialchars(trim($_POST['mobile'])) . '" />';
		$html .= '</label><br>';
	}

	if ( ot_get_option('contact_form_company_check')[0] == 'yes' ) {
		$html .= '<label for="company" >';
		$html .= __("Company Name :", "base_template_translate");
		if (ot_get_option("required_company")[0] == 'yes') {
			$html .= '<span>' . display_error($errors, "error_company")  . '</span>';
			$html .= '*<input type="text" id="company" class="required" name="company" value="' . htmlspecialchars(trim($_POST['company'])) . '" />';
		}else
			$html .= '<input type="text" id="company" name="company" value="' . htmlspecialchars(trim($_POST['company'])) . '" />';
		$html .= '</label><br>';
	}

	if ( ot_get_option('contact_form_country_check')[0] == 'yes' ) { 
		$html .= '<label for="country" >';
		$html .= __("Country: ", "base_template_translate");
		if (ot_get_option("required_company")[0] == 'yes') {
			$html .= '<span>' . display_error($errors, "error_country")  . '</span>';
			$html .= '*<input type="text" id="country" class="required" name="country" value="' . htmlspecialchars(trim($_POST['country'])) . '" />';
		}else
			$html .= '<input type="text" id="country" name="country" value="' . htmlspecialchars(trim($_POST['country'])) . '" />';
		$html .= '</label><br>';
	}

	$html .= '<label for="message" >';
	$html .= __("Message: *", "base_template_translate");
	$html .= '<span>' . display_error($errors, "error_message")  . '</span>';
	$html .= '<textarea id="message" class="required" name="message" />' . htmlspecialchars(trim($_POST['message'])) . '</textarea></label><br>';

	// if captcha is enabled...
	if ( ot_get_option('contact_form_captcha_check') == 'on' ){
		// Show the image
		$html .= '<img id="captcha" src="' . get_template_directory_uri() . '/securimage/securimage_show.php" alt="CAPTCHA Image" />';

		// Flash Audio
		$html .= '<object type="application/x-shockwave-flash" data="' . get_template_directory_uri() . '/securimage/securimage_play.swf?audio_file=' . get_template_directory_uri() . '/securimage/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" width="20" height="20">';
	  	$html .= '<param name="movie" value="' . get_template_directory_uri() . '/securimage/securimage_play.swf?audio_file=' . get_template_directory_uri() . '/securimage/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" />';
	  	$html .= '</object>';

		// Create a text input box
		$html .= '<input type="text" id="captcha_code" name="captcha_code" class="required" size="10" maxlength="6" />';
		// Display any errors
		// Write a second error message in case the field is empty???
		$html .= '<span>' . display_error($errors, "error_captcha_code")  . '</span>';
		$html .= '<span>' . display_error($errors, "error_captcha")  . '</span>';
		// 
		$html .= '<a href="#" id="captchaRefreshLink" onclick="return get_captcha()"><img src="' . get_template_directory_uri() . '/securimage/images/refresh.png" height="32" width="32" alt="Reload Image" onclick="this.blur()" align="bottom" border="0"></a>';
	}

	$html .= '<input type="submit" id="submit" name="submit" value="' . esc_attr__("Submit &raquo;", "base_template_translate") . '" >';
	$html .= '</form>';
   	$html .= '</div>';

   	echo $html;

   	echo '<script>
	// var el = document.getElementById("captcha_code");
	// el.addEventListener("blur", function(){
	// 	// alert(document.URL);
	// 	// var actionUrl = $("#contactForm").attr("action");
	// 	// alert(actionUrl);
	// 	// alert(this.value);
	// 	my_url = "' . get_stylesheet_directory_uri() . '/captcha_check.php?code=";
	// 	// alert(my_url);
	// 	var that = this;

	// if ( this.value.length == 0 ) { 
 //  		document.getElementById("captcha_code").innerHTML="";
 //  		document.getElementById("captcha_code").style.border="0px";
 //  		return;
	// }
	// if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
 //  		xmlhttp=new XMLHttpRequest();
 //  	}else {// code for IE6, IE5
 //  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
 //  	}
		
	// xmlhttp.onreadystatechange=function() {
	// 	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	// 	    document.getElementById("captcha_code").innerHTML=xmlhttp.responseText;
	// 	    document.getElementById("captcha_code").style.border="1px solid #A5ACB2";
	// 	    alert(xmlhttp.responseText);
	//     }
 //  	}

	// 	xmlhttp.open("GET", my_url + this.value, true);
	// 	xmlhttp.send();

	// }, false);
	</script>';	
	// http://www.crackajax.net/captchaform.php
