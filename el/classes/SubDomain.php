<?php

require_once('Pdo.php');

/**
  * SubDomain class, SubDomain.php
  * Sub domain management
  * @category classes
  *
  * @author John Mitros
  * @copyright HMS
  *
  */

class SubDomain extends ObjectModel
{
	public $name;

	protected $fieldsRequired = array('name');
	protected $fieldsSize = array('name' => 16);
	protected $fieldsValidate = array('name' => 'isSubDomainName');

	protected $table = 'subdomain';
	protected $identifier = 'id_subdomain';

	public function getFields()
	{
		parent::validateFields();
		$fields['name'] = $this->name;
		return $fields;
	}

	static public function getSubDomains()
	{
		if (!$result = DB::GetRow('SELECT `name` FROM `subdomain`'))
			return false;
		$subDomains = array();
		foreach ($result as $row)
			$subDomains[] = $row['name'];
		return $subDomains;
	}
}

?>