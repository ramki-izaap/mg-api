<?php

require APPPATH . 'libraries/textlocal.class.php';

function sendSMS($numbers, $message)
{
	//$textlocal = new Textlocal(false, false, 'CuC4mVVQdc0-F2NPN2t9JIqy9Lxcs5ov6c19uOe0rh');
	$textlocal = new Textlocal(false, false, '7QmcHbKksIY-O2RnrQhkiPxpVyWMyZJKppEbi2oQTI');
	$sender = 'TXTLCL';
	
	try 
	{
	    $result = $textlocal->sendSms($numbers, $message, $sender);
	    
	    print_r($result);
	} catch (Exception $e) 
	{
	    die('Error: ' . $e->getMessage());
	}
}


function getSMSContent($type = '', $data = array())
{
	$CI =& get_instance();

	switch ($type) 
	{
		case 'welcome':
			$template = 'Hi {name}, Welcome to Muscle Garage.';
			break;

		case 'membership_confirmation':
			$template = 'Hi {name}, Thank you for joining Muscle Garage.Your membership deatails: 
						Membership:{membership_name}
						Amount:{amount}
						expired at: {expired_at}.';
			break;

		case 'membership_expiry_confirmation':
			$template = 'Hi {name}, Your membership is due for renewal. Renew before {expired_at}.';
			break;

		case 'payment_due':
			$template = 'Hi {name}, Your payment is due. ';
			break;
		
		default:
			$template = 'Hi {name}, Welcome to Muscle Garage.';
			break;
	}	

	return $CI->parser->parse_string($template, $data, TRUE);
}

?>