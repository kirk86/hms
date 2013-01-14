<?php
	require_once('scheduler_connector.php');
	require_once('../config/config.php');
	
	$res=mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
	mysql_select_db(DB_DATABASE);
    mysql_set_charset('utf8', $res);
	
	$list = new OptionsConnector($res);
	$list->render_table("types", "typeid" ,"typeid(value), name(label)");
	
	$scheduler = new schedulerConnector($res);
	//$scheduler->enable_log("log.txt",true);
	
	$scheduler->set_options("type", $list);
	$scheduler->render_table("tevents", "event_id", "event_name, start_date, end_date, type");
?>