<?php
require_once('config/config.php');
require_once(INCLUDES_DIR . '/functions.php');
__autoload("tools");

$destination = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME'])) + 1);
$url_redirect = '?redirect='.(empty($destination) ? 'index.php' : $destination);

if (!isLogged())
	Tools::redirectLink('login.php'.$url_redirect);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title></title>
</head>
	<script src="js/dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>
    <!--<script src="js/dhtmlxscheduler_active_links.js" type="text/javascript" charset="utf-8"></script>-->
   	<script src="js/dhtmlxscheduler_year_view.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/dhtmlxscheduler_pdf.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/dhtmlxscheduler_minical.js" type="text/javascript" charset="utf-8"></script>
    
	
<link rel="stylesheet" href="css/dhtmlxscheduler.css" type="text/css" media="screen" title="no title" charset="utf-8" />
    <link rel="stylesheet" href="css/dhtmlxscheduler_ext.css" type="text/css" title="no title" charset="utf-8" />
	
<style type="text/css" media="screen"> 
	html, body{
		margin:0px;
		padding:0px;
		height:100%;
		overflow:hidden;
	}	
</style>
 
<script type="text/javascript" charset="utf-8"> 
function init() {
    
		scheduler.config.xml_date="%Y-%m-%d %H:%i";
        scheduler.config.multi_day = true;
		scheduler.config.prevent_cache = true;
        scheduler.config.details_on_create=true;
		scheduler.config.details_on_dblclick=true;
        scheduler.config.auto_end_date = true;
        
         scheduler.attachEvent("onEventSave",function(id,data){
			if (!data.text) {
				alert("Text must not be empty");
				return false;
			}
			if (data.text.length<8) {
				alert("Text too small");
				return false;
			}
			return true;
		});
        
        //scheduler.config.full_day = true; // enable parameter to get full day event option on the lightbox form
        //scheduler.config.first_hour=4;
        
        scheduler.init('scheduler',new Date(2010,04,1),"month");
		scheduler.setLoadMode("month");
	
	    scheduler.locale.labels.section_location="Location";
        scheduler.locale.labels.section_type="Doctor";
        //scheduler.locale.labels.section_checkme = "I'm going to participate"; 
		scheduler.config.lightbox.sections=[	
			{name:"description", height:130, map_to:"text", type:"textarea" , focus:true},
			{name:"location", height:43, type:"textarea", map_to:"location" },
        //    { name:"checkme", map_to:"single_checkbox", type:"checkbox", checked_value: "registrable", unchecked_value: "unchecked" },
            {name:"type", height:21, map_to:"type", type:"select", options:scheduler.serverList("type")},
			{name:"time", height:72, type:"time", map_to:"auto"}
		];
        
		scheduler.load("connector/events.php");
		
		var dp = new dataProcessor("connector/events.php");
		dp.init(scheduler);
	}
    
    function show_minical(){
		if (scheduler.isCalendarVisible())
			scheduler.destroyCalendar();
		else
			scheduler.renderCalendar({
				position:"dhx_minical_icon",
				date:scheduler._date,
				navigation:true,
				handler:function(date,calendar){
					scheduler.setCurrentView(date);
					scheduler.destroyCalendar()
				}
			});
	}

</script>
 
<body onload="init();">
	<div id="scheduler" class="dhx_cal_container" style='width:100%; height:100%;'>
		<div class="dhx_cal_navline">
			<div class="dhx_cal_prev_button">&nbsp;</div>
			<div class="dhx_cal_next_button">&nbsp;</div>
			<div class="dhx_cal_today_button"></div>
			<div class="dhx_cal_date"></div>
            <div class="dhx_minical_icon" id="dhx_minical_icon" onclick="show_minical()">&nbsp;</div>
            <input type="button" name="print" value="Print" onclick="scheduler.toPDF('connector/pdf/generate.php')" style='position:absolute; right:350px; top:0px;' />
            <div class="dhx_cal_tab" name="year_tab" style="right:268px;"></div>
			<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
			<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
			<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
		</div>
		<div class="dhx_cal_header">
		</div>
		<div class="dhx_cal_data">
		</div>
	</div>
</body>