<?php

/**
 * @author John
 * @copyright 2010
 */
 
require_once(dirname(dirname(__FILE__)) . "/config/config.php");
require_once(CLASSES_DIR . "/Validate.php");
require_once(CLASSES_DIR . "/Employee.php");

function redirect($location = NULL)
{
	if($location != NULL)
	{
		header("Location: {$location}");
		exit;
	}
}

function outputMessage($message="")
{
	if(!empty($message))
	{
		return "<p class=\"message\">{$message}</p>";
	}
	else
	{
		return "";
	}
}

function __autoload($className)
{
	$className = ucfirst($className);
	$path = CLASSES_DIR . "/{$className}.php";
	if(file_exists($path))
	{
		require_once($path);
	}
	else
	{
		die("The file {$className}.php could not be found");
	}
}

function isLogged()
{
    session_start();
    if (isset($_SESSION['id_employee']) && Validate::isUnsignedId($_SESSION['id_employee']) && Employee::checkPassword(intval($_SESSION['id_employee']), $_SESSION['passwd']) && (!isset($_SESSION['remote_addr']) || $_SESSION['remote_addr'] == ip2long($_SERVER['REMOTE_ADDR'])))
			return true;
		return false;
  session_write_close();      
  
}

function logout()
{
    unset($_SESSION['id_employee']);
    unset($_SESSION['lastname']);
    unset($_SESSION['firstname']);
    unset($_SESSION['email']);
    unset($_SESSION['id_profile']);
    unset($_SESSION['passwd']);
    unset($_SESSION['remote_addr']);
    unset($_SESSION);
    session_destroy();
    
}

function isAdmin()
{
    if(isset($_SESSION['id_employee']) && !empty($_SESSION['id_employee']) && isset($_SESSION['id_profile']) && $_SESSION['id_profile'] == 1)
    return true;
}

function isSubscriber()
{
    if(isset($_SESSION['id_employee']) && !empty($_SESSION['id_employee']) && isset($_SESSION['id_profile']) && $_SESSION['id_profile'] == 1)
    return true;
    elseif(isset($_SESSION['id_employee']) && !empty($_SESSION['id_employee']) && isset($_SESSION['id_profile']) && $_SESSION['id_profile'] != 1)
    return true;
}