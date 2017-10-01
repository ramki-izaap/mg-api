<?php

require APPPATH . 'libraries/textlocal.class.php';

function sendSMS($numbers, $message)
{
	$textlocal = new Textlocal(false, false, 'CuC4mVVQdc0-F2NPN2t9JIqy9Lxcs5ov6c19uOe0rh');
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

?>