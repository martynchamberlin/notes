<?php

class Validate
{
	public static $begin = "http";
	public static $usingSSL = false;

	public static $errorsArray = array();

	public static function postDataIsValid()
	{
		
		foreach ($_POST as $key=>$value)
		{
			if ($value == "")
			{
				self::$errorsArray[$key] = "Please enter text for this field";
			}
		}
		if (! empty (self::$errorsArray))
		{
			return false;
		}
		return true;
	}

	public static function isValidEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return true;
		}
		return false;
	}

	public static function areSame($password1, $password2)
	{
		if ($password1 != $password2)
		{	
			return false;
		}
		return true;
	}

	public static function Mod10($stringNumber)
	{
		$intNumber = (int)$stringNumber;
		$length = strlen($stringNumber);
		$numbersArray = array();

		for ($i = $length - 1; $i >= 0; $i--)
		{
			$newNumber = $stringNumber[$i];
			if ($length % 2 == $i % 2)
			{
				$newNumber = (string)($newNumber * 2);
				for ($j = 0; $j < strlen($newNumber); $j++)
				{
					array_push($numbersArray, $newNumber[$j]);
				}
				continue;
			}
			array_push($numbersArray, $newNumber);
		}

		for ($i = count($numbersArray) - 1; $i >= 0; $i--)
		{
			 $answer += $numbersArray[$i];
		}

		if ($answer % 10 == 0)
		{
			return true;
		}
		return false;		
	}

	public static function setUsingSSL()
	{	
		if (!isset(self::$using_ssl))
		{		
			self::$using_ssl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || $_SERVER['SERVER_PORT'] == 443;
		}
	}

	// Only invoke this function if you're for certain NO HTML has been sent yet
	public static function forceSSL()
	{
		self::setUsingSSL();

		if (! self::$using_ssl)
		{
   		header('HTTP/1.1 301 Moved Permanently');
 		  header('Location: https://' . $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
		  exit;
		}
	}
		public static function removeSSL()
	{	
		self::setUsingSSL();
		if (self::$using_ssl)
		{
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: http://' . $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
			exit;
		}
	}

	public static function begin()
	{
		self::setUsingSSL();
		if (self::$using_ssl)
		{
			self::$begin .= 's';
		}
		return self::$begin;
	}

}
?>
