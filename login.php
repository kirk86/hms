<?php

/**
  * Login for admin panel, login.php
  * @category admin
  *
  * @author John Mitros
  * @copyright HMS
  *
  */
 
require_once('config/config.php');
require_once(CLASSES_DIR . '/Validate.php');
require_once(CLASSES_DIR . '/Employee.php');

$errors = array();

// Checking path
$pathUser = preg_replace('!^/!', '', str_replace('\\', '/', $_SERVER['PHP_SELF']));
$pathServer = preg_replace('!^/!', '', str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME'])));
if ($pathServer != $pathUser)
	$errors[] = Tools::displayError('Path is not the same between your browser and you server :').'<br /><br /><b>'.
				Tools::displayError('- Server:').'</b><br />'.htmlentities($pathServer).'<br /><br /><b>'.
				Tools::displayError('- Browser:').'</b><br />'.htmlentities($pathUser);

/* Cookie creation and redirection */
if (Tools::isSubmit('Submit'))
{
 	/* Check fields validity */
	$passwd = trim(Tools::getValue('passwd'));
	$email = trim(Tools::getValue('email'));
	if (empty($email))
		$errors[] = Tools::displayError('e-mail is empty');
	elseif (!Validate::isEmail($email))
		$errors[] = Tools::displayError('invalid e-mail address');
	elseif (empty($passwd))
		$errors[] = Tools::displayError('password is blank');
	elseif (!Validate::isPasswd($passwd))
		$errors[] = Tools::displayError('invalid password');
	else
	{
	 	/* Seeking for employee */
		$employee = new Employee();
		$employee = $employee->getByemail($email, $passwd);
		if (!$employee)
			$errors[] = Tools::displayError('employee does not exist, or bad password');
		else
		{   
            /* Creating session */
            session_start();
			$_SESSION['id_employee'] = $employee->id;
			$_SESSION['lastname'] = $employee->lastname;
			$_SESSION['firstname'] = $employee->firstname;
			$_SESSION['email'] = $employee->email;
			$_SESSION['id_profile'] = $employee->id_profile;
            $_SESSION['id_lang'] = $employee->id_lang;
			$_SESSION['passwd'] = $employee->passwd;
			$_SESSION['remote_addr'] = ip2long($_SERVER['REMOTE_ADDR']);
            session_write_close();
           
			/* Redirect to admin panel */
			if (isset($_GET['redirect']))
				$url = strval($_GET['redirect'].(isset($_GET['ToDo']) ? ('&ToDo='.$_GET['ToDo']) : ''));
			else
				$url = 'index.php';
			if (!Validate::isCleanHtml($url))
				die(Tools::displayError());
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
				<meta http-equiv="Refresh" content="0;URL='.Tools::safeOutput($url, true).'">
				<head>
					<script language="javascript" type="text/javascript">
						window.location.replace("'.Tools::safeOutput($url, true).'");
					</script>
					<div style="text-align:center; margin-top:250px;"><a href="'.Tools::safeOutput($url, true).'">'.'Click here to launch Administration panel'.'</a></div>
				</head>
			</html>';
			exit ;
		}
	}
}

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="css/login.css" />
		<title>HMS&trade; - '.'Administration panel'.'</title>';
echo '
	</head>
	<body>
		<div id="container">';

if ($nbErrors = sizeof($errors))
{
	echo '
	<div id="error">
		<h3>'.($nbErrors > 1 ? 'There are' : 'There is').' '.$nbErrors.' '.($nbErrors > 1 ? 'errors' : 'error').'</h3>
		<ol style="margin: 0 0 0 20px;">';
		foreach ($errors AS $error)
			echo '<li>'.$error.'</li>';
		echo '
		</ol>
	</div>
	<br />';
}

echo '
			<div id="login">
				<form action="'.$_SERVER['REQUEST_URI'].'" method="post">';

$randomNb = rand(100, 999);

	echo '			<label>'.'E-mail address:'.'</label><br />
					<input type="text" id="email" name="email" value="'.Tools::safeOutput(Tools::getValue('email')).'" class="input"/>
					<div style="margin: 0.5em 0 0 0;">
						<label>'.'Password:'.'</label><br />
						<input type="password" name="passwd" class="input" value=""/>
					</div>
					<div>
						<div id="submit"><input type="submit" name="Submit" value="'.'Connection'.'" class="button" /></div>
						<!--<div id="lost"><a href="password.php">'.'Lost password?'.'</a></div>-->
					</div>
	';
//}
?>
<script type="text/javascript">
<!--
if (document.getElementById('email')) document.getElementById('email').focus();
-->
</script>
<?php
echo '
				</form>
			</div>
		</div>
	</body>
</html>';

?>
