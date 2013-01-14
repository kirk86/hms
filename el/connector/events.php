<?php
	session_start();
	require_once('scheduler_connector.php');
	require_once('../config/config.php');
	
	$res=mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die("Could not connect to Database");
	mysql_select_db(DB_DATABASE) or die("Could not select Database");
    mysql_set_charset('utf8', $res);
	
	$scheduler = new schedulerConnector($res);
	//$scheduler->enable_log("log.txt",true);
    $list = new OptionsConnector($res);
    $list->render_table("types", "id_doctor", "id_doctor(value), fullName(label)");	
	$scheduler->set_options("type", $list);
        
	if($_SESSION['id_profile'] == 3)
	{
		$result = mysql_query("select * from doctor where doctor.doctorName = '".$_SESSION['firstname']."' and doctor.doctorSurname = '".$_SESSION['lastname']."'", $res) or die("Could not execute query");
		
		$doctor = array();
		while($row = mysql_fetch_assoc($result))
		{
			$doctor['id_doctor'] = $row['id_doctor'];
			$doctor['doctorName'] = $row['doctorName'];
			$doctor['doctorSurname'] = $row['doctorSurname'];
		}
		
		if ($scheduler->is_select_mode())//code for loading data
		    $scheduler->render_sql("Select * from events  where  type = '$doctor[id_doctor]'", "event_id","start_date, end_date, event_name, location, type");
    	else //code for other operations - i.e. update/insert/delete
			$scheduler->render_table("events", "event_id", "start_date, end_date, event_name, location, type");
	}
	else
		$scheduler->render_table("events", "event_id", "start_date, end_date, event_name, location, type");   
    session_destroy();
    mysql_close($res);
    
    
    
    //Multiselect 
    
   	//$cross = new CrossOptionsConnector($res);
//	$cross->dynamic_loading(true);
//	$cross->options->render_table("user","user_id","user_id(value),username(label)");
//	$cross->link->render_table("event_user","event_id", "user_id,event_id");
//	
//	$fruitCross = new CrossOptionsConnector($res);
//	$fruitCross->dynamic_loading(true);
//	$fruitCross->options->render_table("fruit","fruit_id","fruit_id(value),fruit_name(label)");
//	$fruitCross->link->render_table("event_fruit","event_id","fruit_id,event_id");
//	
//	//sleep(2);
//	$scheduler = new SchedulerConnector($res);
//	//$scheduler->enable_log("events_logs.txt",true);
//	
//	$scheduler->set_options("user_id", $cross->options);
//	$scheduler->set_options("fruit_id", $fruitCross->options);
//	
//	$scheduler->render_table("events_ms","event_id","start_date,end_date,event_name,details");
?>