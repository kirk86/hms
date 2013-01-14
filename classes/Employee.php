<?php

require_once('Pdo.php');
require_once('ObjectModel.php');
require_once('Validate.php');
require_once('Tools.php');

/**
  * Employee class, Employee.php
  * Employees management
  * @category classes
  *
  * @author John Mitros
  * @copyright HMS
  *
  */

class Employee extends ObjectModel
{
	public 		$id;
	
	/** @var string Determine employee profile */
	public 		$id_profile;
    
    /** @var string employee language */
	public 		$id_lang;
	
	/** @var string Lastname */
	public 		$lastname;
	
	/** @var string Firstname */
	public 		$firstname;
	
	/** @var string e-mail */
	public 		$email;
	
	/** @var string Password */
	public 		$passwd;
	
	/** @var datetime Password */
	public 		$last_passwd_gen;
	
	public $stats_date_from;
	public $stats_date_to;
	
	/** @var boolean Status */
	public 		$active = 1;
	
	
 	protected 	$fieldsRequired = array('lastname', 'firstname', 'email', 'passwd', 'id_profile', 'id_lang');
 	protected 	$fieldsSize     = array('lastname' => 32, 'firstname' => 32, 'email' => 128, 'passwd' => 32);
 	protected 	$fieldsValidate = array('lastname' => 'isName', 'firstname' => 'isName', 'email' => 'isEmail', 
		'id_lang' => 'isUnsignedInt', 'passwd' => 'isPasswdAdmin', 'active' => 'isBool', 'id_profile' => 'isInt');
	
	protected 	$table = 'employee';
	protected 	$identifier = 'id_employee';

	public	function getFields()
	{
	 	parent::validateFields();
		
		$fields['id_profile'] = intval($this->id_profile);
        $fields['id_lang'] = (int)$this->id_lang;
		$fields['lastname'] = strip_tags(trim((get_magic_quotes_gpc) ? stripslashes($this->lastname) : addslashes($this->lastname)));
		$fields['firstname'] = strip_tags(trim((get_magic_quotes_gpc) ? stripslashes(Tools::ucfirst($this->firstname)) : addslashes(Tools::ucfirst($this->firstname))));
		$fields['email'] = strip_tags(trim((get_magic_quotes_gpc) ? stripslashes($this->email) : addslashes($this->email)));
		$fields['passwd'] = strip_tags(trim((get_magic_quotes_gpc) ? stripslashes($this->passwd) : addslashes($this->passwd)));
		$fields['last_passwd_gen'] = strip_tags(trim((get_magic_quotes_gpc) ? stripslashes($this->last_passwd_gen) : addslashes($this->last_passwd_gen)));
		$fields['stats_date_from'] = strip_tags(trim((get_magic_quotes_gpc) ? stripslashes($this->stats_date_from) : addslashes($this->stats_date_from)));
		$fields['stats_date_to'] = strip_tags(trim((get_magic_quotes_gpc) ? stripslashes($this->stats_date_to) : addslashes($this->stats_date_to)));
		$fields['active'] = intval($this->active);
		
		return $fields;
	}
	
	/**
	 * Return all employee id and email
	 *
	 * @return array Employees
	 */
	public static function getEmployees()
	{
		return (DB::GetAll('SELECT `id_employee`, CONCAT(`firstname`, \' \', `lastname`) AS "name"
                            FROM `employee`
                            WHERE `active` = 1
                            ORDER BY `email`'));
	}
	
	public function add($autodate = true, $nullValues = true)
	{
		$this->last_passwd_gen = date('Y-m-d H:i:s', strtotime('-'.PASSWD_TIME_BACK.'minutes'));
	 	return parent::add($autodate, $nullValues);
	}
		
	/**
	  * Return employee instance from its e-mail (optionnaly check password)
	  * 
	  * @param string $email e-mail
	  * @param string $passwd Password is also checked if specified
	  * @return Employee instance
	  */
	public function getByemail($email, $passwd = NULL)
	{
	 	if (!Validate::isEmail($email) || ($passwd != NULL && !Validate::isPasswd($passwd)))
	 		die(Tools::displayError());

		$result = DB::GetRow('
		SELECT * 
		FROM `employee`
		WHERE `active` = 1
		AND `email` = \''.$email.'\'
		'.($passwd ? 'AND `passwd` = \''.Tools::encrypt($passwd).'\'' : ''));
		if (!$result)
			return false;
		$this->id = $result['id_employee'];
		$this->id_profile = $result['id_profile'];
		foreach ($result as $key => $value)
			if (key_exists($key, $this))
				$this->{$key} = $value;
		return $this;
	}
	
	public static function employeeExists($email)
	{
	 	if (!Validate::isEmail($email))
	 		die (Tools::displayError());
	 	
		$result = DB::GetRow('
		SELECT `id_employee`
		FROM `employee`
		WHERE `email` = \''.$email.'\'');
		return isset($result['id_employee']);
	}

	/**
	  * Check if employee password is the right one
	  * 
	  * @param string $passwd Password
	  * @return boolean result
	  */
	public static function checkPassword($id_employee, $passwd)
	{
	 	if (!Validate::isUnsignedId($id_employee) || !Validate::isPasswd($passwd, 8))
	 		die (Tools::displayError());
		$result = DB::GetRow('
		SELECT `id_employee`
		FROM `employee`
		WHERE `id_employee` = '.intval($id_employee).' AND `passwd` = \''.$passwd.'\'');

		return isset($result['id_employee']) ? $result['id_employee'] : false;
	}
}