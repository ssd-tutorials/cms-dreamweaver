<?php

// process the email
if (array_key_exists('btn', $_POST)) {

// process the email
$to = 'info@myemailaddress.co.uk'; // own email address
$subject = 'Message from mydomain.co.uk website';

// list expected fields
$expected = array('frmname','frmemail','frmtel','frmmessage');

// set required fields
$required = array('frmname','frmemail','frmmessage');

// create empty array for any missing fields
$missing = array();



	// process the $_POST variables
	foreach ($_POST as $key => $value) {	

		// assign to temporary variable and strip whitespace if not an array
		$temp = is_array($value) ? $value : trim($value);

		// if empty and required, add to $missing array
		if (empty($temp) && in_array($key, $required)) {

			array_push($missing, $key);

		} // otherwise, assign to a variable of the same name as $key

		elseif (in_array($key, $expected)) {

			${$key} = $temp;

		}	

	}
	
// validate the email address
if (!empty($frmemail)) {

	// regex to ensure there are no illegal characters in email address
	$checkEmail = '/^[^@]+@[^\s\r\n\'";,@%]+$/';	

	// reject the email address if it doesn't match
	if (!preg_match($checkEmail, $frmemail)) {

		array_push($missing, 'frmemail');

	}

}

//get IP address
$ip = $_SERVER['REMOTE_ADDR'];

//make time
$time = time();
$date = date("r", $time);

// go ahead only if all required fields are ok
if (empty($missing)) {

$frmmessage = nl2br($frmmessage);

// build the message
$message = "<div style=\"font-size:12px;font-family:arial,verdana,sans-serif;color:#666;line-height:26px;padding:10px;\">";
$message .= "<div style=\"width:100%;\"><strong style=\"width: 180px;height:auto;\">Name:</strong> $frmname</div>";
$message .= "<div style=\"width:100%;\"><strong style=\"width: 180px;height:auto;\">Email:</strong> $frmemail</div>";
$message .= "<div style=\"width:100%;\"><strong style=\"width: 180px;height:auto;\">Telephone:</strong> $frmtel</div>";
$message .= "<div style=\"width:100%;\"><strong style=\"width: 180px;height:auto;\">Message:</strong><br />$frmmessage</div>";
$message .= "<div style=\"width:100%;\"><strong style=\"width: 180px;height:auto;\">IP:</strong> <span style=\"color:#990000;\">$ip</span></div>";
$message .= "<div style=\"width:100%;\"><strong style=\"width: 180px;height:auto;\">Date:</strong> $date</div></div>";

// create additional headers

$additionalHeaders = "From: $frmemail<$frmemail>";

if (!empty($frmemail)) {

	$additionalHeaders .= "\r\nReply-To: $frmemail";

}

$additionalHeaders .= "\r\nContent-type: text/html; charset=us-ascii";

// send email
$mailSent = mail($to, $subject, $message, $additionalHeaders);

if ($mailSent) {

	// $missing is no longer needed if the email is sent, so unset it
	unset($missing);

}

}

}

?>

<form id="frmcontact" name="frmcontact" method="post" action="">
  <table border="0" cellspacing="0" cellpadding="0" id="tbl_contact">
    <?php 
	global $mailSent;
    global $missing;	
	if ($_POST && $mailSent) { ?>
    <tr>
      <td colspan="2" style="padding:0px 0px 315px 0px;"><p class="warning_sent">Your message has been sent successfuly.<br />
          I will get back to you shortly.</p></td>
    </tr>
    <?php } else {
      if ($_POST && isset($missing)) { ?>
    <tr>
      <td colspan="2" style="padding:0px 0px 10px 0px;"><p class="warning">Please complete the missing item(s) indicated.</p></td>
    </tr>
    <?php } elseif ($_POST && !isset($missing) && !$mailSent) { ?>
    <tr>
      <td colspan="2" style="padding:0px 0px 10px 0px;"><p class="warning">Sorry there was a problem sending your message.<br />
          Please try again later.</p></td>
    </tr>
    <?php } ?>
    <?php if (isset($missing) && in_array('frmname', $missing)) { ?>
      <tr>
        <th scope="row">&#160;</th>
        <td><span class="missing">Please provide your name</span></td>
      </tr>
      <?php } ?>
    <tr>
      <th scope="row"><label for="frmname" class="req">Name:</label></th>
      <td><input type="text" name="frmname" id="frmname" class="frmfld" <?php if (isset($missing)) { echo 'value="'.htmlentities($_POST['frmname']).'"'; } ?> /></td>
    </tr>
    <?php if (isset($missing) && in_array('frmemail', $missing)) { ?>
      <tr>
        <th scope="row">&#160;</th>
        <td><span class="missing">Please provide your valid email address</span></td>
      </tr>
      <?php } ?>
    <tr>
      <th scope="row"><label for="frmemail" class="req">Email:</label></th>
      <td><input type="text" name="frmemail" id="frmemail" class="frmfld" <?php if (isset($missing)) { echo 'value="'.htmlentities($_POST['frmemail']).'"'; } ?> /></td>
    </tr>
    <?php if (isset($missing) && in_array('frmtel', $missing)) { ?>
      <tr>
        <th scope="row">&#160;</th>
        <td><span class="missing">Please provide your telephone number</span></td>
      </tr>
      <?php } ?>
    <tr>
      <th scope="row"><label for="frmtel">Telephone:</label></th>
      <td><input type="text" name="frmtel" id="frmtel" class="frmfld" <?php if (isset($missing)) { echo 'value="'.htmlentities($_POST['frmtel']).'"'; } ?> /></td>
    </tr>
    <?php if (isset($missing) && in_array('frmmessage', $missing)) { ?>
      <tr>
        <th scope="row">&#160;</th>
        <td><span class="missing">Please provide your message</span></td>
      </tr>
      <?php } ?>
    <tr>
      <th scope="row"><label for="frmmessage" class="req">Message:</label></th>
      <td><textarea name="frmmessage" id="frmmessage" cols="" rows="" class="frmarea"><?php if (isset($missing)) { echo htmlentities($_POST['frmmessage']); } ?>
</textarea></td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <td><label for="btn" id="sbm">
          <input type="submit" name="btn" id="btn" value="" />
        </label></td>
    </tr>
    <?php } ?>
  </table>
</form>
