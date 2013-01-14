<?php

require_once('Pdo.php');
require_once('Tools.php');
require_once('Validate.php');

/**
  * Main abstract class, ObjectModel.php
  * Employee objects are extending this abstract class
  * @category classes
  *
  * @author John Mitros
  * @copyright HMS
  *
  */

abstract class ObjectModel
{
	/** @var integer Object id */
	public $id;

	/** @var string SQL Table name */
	protected $table = NULL;

	/** @var string SQL Table identifier */
	protected $identifier = NULL;

	/** @var array Required fields for admin panel forms */
 	protected $fieldsRequired = array();

 	/** @var array Maximum fields size for admin panel forms */
 	protected $fieldsSize = array();

 	/** @var array Fields validity functions for admin panel forms */
 	protected $fieldsValidate = array();

	/** @var array tables */
 	protected $tables = array();

	/**
	 * Returns object validation rules (fields validity)
	 *
	 * @param string $className Child class name for static use (optional)
	 * @return array Validation rules (fields validity)
	 */
	static public function getValidationRules($className = __CLASS__)
	{
		$object = new $className();
		return array(
		'required' => $object->fieldsRequired,
		'size' => $object->fieldsSize,
		'validate' => $object->fieldsValidate);
	}

	/**
	 * Prepare fields for ObjectModel class (add, update)
	 * All fields are verified (pSQL, intval...)
	 *
	 * @return array All object fields
	 */
	public function getFields()	{ return array(); }

	/**
	 * Build object
	 *
	 * @param integer $id Existing object id in order to load object (optional)
	 * @param integer $id_lang Required if object is multilingual (optional)
	 */
	public function __construct($id = NULL, $id_lang = NULL)
	{
	 	/* Connect to database and check SQL table/identifier */
	 	if (!Validate::isTableOrIdentifier($this->identifier) OR !Validate::isTableOrIdentifier($this->table))
			die(Tools::displayError());
		$this->identifier = $this->identifier;

		/* Load object from database if object id is present */
		if ($id)
		{
			$result = DB::GetRow('
			SELECT *
			FROM `'.$this->table.'` a '
			.' WHERE a.`'.$this->identifier.'` = '.intval($id));
			if (!$result) return false;
			$this->id = intval($id);
			foreach ($result as $key => $value)
				if (key_exists($key, $this))
					$this->{$key} = stripslashes($value);
		}
	}

	/**
	 * Save current object to database (add or update)
	 *
	 * return boolean Insertion result
	 */
	public function save($nullValues = false, $autodate = true)
	{
		return intval($this->id) > 0 ? $this->update($nullValues) : $this->add($autodate, $nullValues);
	}
    
    
    public function	autoExecute($table, $values, $type, $where = false, $limit = false)
	{
		if (!sizeof($values))
			return true;

		if (strtoupper($type) == 'INSERT')
		{
			$query = 'INSERT INTO `'.$table.'` (';
			foreach ($values as $key => $value)
				$query .= '`'.$key.'`,';
			$query = rtrim($query, ',').') VALUES (';
			foreach ($values as $key => $value)
				$query .= '\''.$value.'\',';
			$query = rtrim($query, ',').')';
			if ($limit)
				$query .= ' LIMIT '.intval($limit);
			return $query;
		}
		elseif (strtoupper($type) == 'UPDATE')
		{
			$query = 'UPDATE `'.$table.'` SET ';
			foreach ($values as $key => $value)
				$query .= '`'.$key.'` = \''.$value.'\',';
			$query = rtrim($query, ',');
			if ($where)
				$query .= ' WHERE '.$where;
			if ($limit)
				$query .= ' LIMIT '.intval($limit);
			return $query;
		}
		
		return false;
	}
    
    /**
	 * Filter SQL query within a blacklist
	 *
	 * @param string $table Table where insert/update data
	 * @param string $values Data to insert/update
	 * @param string $type INSERT or UPDATE
	 * @param string $where WHERE clause, only for UPDATE (optional)
	 * @param string $limit LIMIT clause (optional)
	 * @return mixed|boolean SQL query result
	 */
	public function	autoExecuteWithNullValues($table, $values, $type, $where = false, $limit = false)
	{
		if (!sizeof($values))
			return true;

		if (strtoupper($type) == 'INSERT')
		{
			$query = 'INSERT INTO `'.$table.'` (';
			foreach ($values as $key => $value)
				$query .= '`'.$key.'`,';
			$query = rtrim($query, ',').') VALUES (';
			foreach ($values as $key => $value)
				$query .= (($value === '' || $value === NULL) ? 'NULL' : '\''.$value.'\'').',';
			$query = rtrim($query, ',').')';
			if ($limit)
				$query .= ' LIMIT '.intval($limit);
			return $query;
		}
		elseif (strtoupper($type) == 'UPDATE')
		{
			$query = 'UPDATE `'.$table.'` SET ';
			foreach ($values as $key => $value)
				$query .= '`'.$key.'` = '.(($value === '' || $value === NULL) ? 'NULL' : '\''.$value.'\'').',';
			$query = rtrim($query, ',');
			if ($where)
				$query .= ' WHERE '.$where;
			if ($limit)
				$query .= ' LIMIT '.intval($limit);
			return $query;
		}
		
		return false;
	}

	/**
	 * Add current object to database
	 *
	 * return boolean Insertion result
	 */
	public function add($autodate = true, $nullValues = false)
	{
	 	if (!Validate::isTableOrIdentifier($this->table))
			die(Tools::displayError());

		/* Automatically fill dates */
		if ($autodate && key_exists('date_add', $this))
			$this->date_add = date('Y-m-d H:i:s');
		if ($autodate && key_exists('date_upd', $this))
			$this->date_upd = date('Y-m-d H:i:s');

		/* Database insertion */
		if ($nullValues)
			$result = DB::Execute($this->autoExecuteWithNullValues($this->table, $this->getFields(), 'INSERT'));
		else
			$result = DB::Execute($this->autoExecute($this->table, $this->getFields(), 'INSERT'));
		if (!$result)
			return false;

		/* Get object id in database */
		$this->id = DB::getInstance()->lastInserId();
		
		return $result;
	}

	/**
	 * Update current object to database
	 *
	 * return boolean Update result
	 */
	public function update($nullValues = false)
	{
	 	if (!Validate::isTableOrIdentifier($this->identifier) || !Validate::isTableOrIdentifier($this->table))
			die(Tools::displayError());

		/* Automatically fill dates */
		if (key_exists('date_upd', $this))
			$this->date_upd = date('Y-m-d H:i:s');

		/* Database update */
		if ($nullValues)
			$result = DB::getInstance()->Execute($this->autoExecuteWithNullValues($this->table, $this->getFields(), 'UPDATE', '`'.$this->identifier.'` = '.intval($this->id)));
		else
			$result = DB::getInstance()->Execute($this->autoExecute(_DB_PREFIX_.$this->table, $this->getFields(), 'UPDATE', '`'.$this->identifier.'` = '.intval($this->id)));
		if (!$result)
			return false;

		return $result;
	}

	/**
	 * Delete current object from database
	 *
	 * return boolean Deletion result
	 */
	public function delete()
	{
	 	if (!Validate::isTableOrIdentifier($this->identifier) || !Validate::isTableOrIdentifier($this->table))
	 		die(Tools::displayError());

		/* Database deletion */
		$result = DB::Execute('DELETE FROM `'.$this->table.'` WHERE `'.$this->identifier.'` = '.intval($this->id));
		if (!$result)
			return false;

		return $result;
	}

	/**
	 * Delete several objects from database
	 *
	 * return boolean Deletion result
	 */
	public function deleteSelection($selection)
	{
		if (!is_array($selection) || !Validate::isTableOrIdentifier($this->identifier) || !Validate::isTableOrIdentifier($this->table))
			die(Tools::displayError());
		$result = true;
		foreach ($selection as $id)
		{
			$this->id = intval($id);
			$result = $result && $this->delete();
		}
		return $result;
	}

	/**
	 * Toggle object status in database
	 *
	 * return boolean Update result
	 */
	public function toggleStatus()
	{
	 	if (!Validate::isTableOrIdentifier($this->identifier) || !Validate::isTableOrIdentifier($this->table))
	 		die(Tools::displayError());

	 	/* Object must have a variable called 'active' */
	 	elseif (!key_exists('active', $this))
	 		die(Tools::displayError());

		/* Change status to active/inactive */
		return DB::Execute('
		UPDATE `'.$this->table.'`
		SET `active` = !`active`
		WHERE `'.$this->identifier.'` = '.intval($this->id));
	}

	/**
	 * Check for fields validity before database interaction
	 */
	public function validateFields($die = true, $errorReturn = false)
	{
		foreach ($this->fieldsRequired as $field)
			if (Tools::isEmpty($this->{$field}) AND (!is_numeric($this->{$field})))
			{
				if ($die) die (Tools::displayError().' ('.get_class($this).' -> '.$field.' is empty)');
				return $errorReturn ? get_class($this).' -> '.$field.' is empty' : false;
			}
		foreach ($this->fieldsSize as $field => $size)
			if (isset($this->{$field}) AND Tools::strlen($this->{$field}) > $size)
			{
				if ($die) die (Tools::displayError().' ('.get_class($this).' -> '.$field.' length > '.$size.')');
				return $errorReturn ? get_class($this).' -> '.$field.' length > '.$size : false;
			}
		$validate = new Validate();
		foreach ($this->fieldsValidate as $field => $method)
			if (!method_exists($validate, $method))
				die (Tools::displayError('validation function not found').' '.$method);
			elseif (!Tools::isEmpty($this->{$field}) AND !call_user_func(array('Validate', $method), $this->{$field}))
			{
				if ($die) die (Tools::displayError().' ('.get_class($this).' -> '.$field.' = '.$this->{$field}.')');
				return $errorReturn ? get_class($this).' -> '.$field.' = '.$this->{$field} : false;
			}
		return true;
	}
    

	static public function displayFieldName($field, $className = __CLASS__, $htmlentities = true)
	{
		global $_FIELDS;
		$key = $className.'_'.md5($field);
		return ((is_array($_FIELDS) AND array_key_exists($key, $_FIELDS)) ? ($htmlentities ? htmlentities($_FIELDS[$key], ENT_QUOTES, 'utf-8') : $_FIELDS[$key]) : $field);
	}


	public function validateControler($htmlentities = true)
	{
		$errors = array();

		/* Checking for required fields */
		foreach ($this->fieldsRequired as $field)
		if (($value = Tools::getValue($field, $this->{$field})) == false AND (string)$value != '0')
			if (!$this->id OR $field != 'passwd')
				$errors[] = '<b>'.self::displayFieldName($field, get_class($this), $htmlentities).'</b> '.Tools::displayError('is required');


		/* Checking for maximum fields sizes */
		foreach ($this->fieldsSize as $field => $maxLength)
			if (($value = Tools::getValue($field, $this->{$field})) AND Tools::strlen($value) > $maxLength)
				$errors[] = '<b>'.self::displayFieldName($field, get_class($this), $htmlentities).'</b> '.Tools::displayError('is too long').' ('.Tools::displayError('maximum length:').' '.$maxLength.')';

		/* Checking for fields validity */
		foreach ($this->fieldsValidate as $field => $function)
		{
			// Hack for postcode required for country which does not have postcodes
			if ($value = Tools::getValue($field, $this->{$field}) OR ($field == 'postcode' AND $value == '0'))
			{
				if (!Validate::$function($value))
					$errors[] = '<b>'.self::displayFieldName($field, get_class($this), $htmlentities).'</b> '.Tools::displayError('is invalid');
				else
				{
					if ($field == 'passwd')
					{
						if ($value = Tools::getValue($field))
							$this->{$field} = Tools::encrypt($value);
					}
					else	
						$this->{$field} = $value;
				}
			}
		}
		return $errors;
	}
}
