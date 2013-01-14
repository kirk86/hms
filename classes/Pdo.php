<?php

/**
 * @author John Mitros
 * @copyright 2010
 */


class DB
{
    private static $instance;
    
    private function __construct()
    {
        
    }
    
    public static function getInstance()
    {
        if(!isset(self::$instance))
        {
            try
            {
                self::$instance = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD, 
                                          array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                                                PDO::ATTR_PERSISTENT => DB_PERSISTENCY));
                
                // Configure PDO to throw exceptions
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                self::$instance->query("SET NAMES UTF8");
                
            }
            catch(PDOException $e)
            {
                // Close the database handler and trigger an error
				self::Close();
				trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }
        
        return self::$instance;
    }
    
    public static function Close()
    {
        self::$instance = null;
    }
    
    //Execute queries such as INSERT,UPDATE
    public static function Execute($query, $params = null)
    {
        try
        {
            $db = self::getInstance();
            
            $statement = $db->prepare($query);
            
            $statement->execute($params);
            
        }
        catch(PDOException $e)
        {
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
        
        return $statement->rowCount(); 
    }
    
    public static function GetAll($query, $params = null, $fetchStyle = PDO::FETCH_ASSOC)
    {
        $result = null;
        
        try
        {
            $db = self::getInstance();
            
            $statement = $db->prepare($query);
              
            $statement->execute($params);
            
            $result = $statement->fetchAll($fetchStyle);
        }
        catch(PDOException $e)
        {
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
        
        return $result;
    }
    
    public static function GetRow($query, $params = null, $fetchStyle = PDO::FETCH_ASSOC)
    {
        $result = null;

        try
        {
            $db = self::getInstance();
            
            $statement = $db->prepare($query);
            
            $statement->execute($params);
            
            $result = $statement->fetch($fetchStyle);
        }
        catch(PDOException $e)
        {
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
        
        return $result;
    }
    
    //return the first column value from a row.
    public static function GetOne($query, $params = null)
    {
        $result = null;
        
        try
        {
           $db = self::getInstance();

           $statement = $db->prepare($query);
           
           $statement->execute($params);
           
           $result = $statement->fetch(PDO::FETCH_NUM); 
        }
        catch(PDOException $e)
        {
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
        
        return $result;
    }
}