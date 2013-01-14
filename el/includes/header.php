<?php
require_once(INCLUDES_DIR . '/functions.php');
__autoload("errorhandler");
__autoload("pdo");
__autoload("tools");
ErrorHandler::setHandler();

$destination = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME'])) + 1);
$url_redirect = '?redirect='.(empty($destination) ? 'index.php' : ($destination == 'index.php?ToDo=logout') ? 'index.php' : $destination);

if (!isLogged())
Tools::redirectLink('login.php'.$url_redirect);
elseif(isset($_GET['ToDo']) && $_GET['ToDo'] == "logout")
{
    logout();
    Tools::redirectLink('login.php'.$url_redirect);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Σύστημα Διαχείρισης Νοσοκομειακής Μονάδας</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/tablekit.css" rel="stylesheet" type="text/css" />
<link href="css/paginate.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-themes/ui-lightness/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<link href="css/validationEngine.jquery.css" rel="stylesheet"  type="text/css"/>
<link rel="stylesheet" type="text/css" media="all" href="css/niceforms-default.css" />
<link href="css/ausu-autosuggest.css" rel="stylesheet"  type="text/css"/>


<!-- CuFon: Enables smooth pretty custom font rendering. 100% SEO friendly. To disable, remove this section -->
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/arial.js"></script>
<script type="text/javascript" src="js/cuf_run.js"></script>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/tablekit.js"></script>
<!-- CuFon ends -->

<!--jquery: Enables effects-->
<script type="text/javascript" src="js/jquery/jquery-1.4.4.js"></script> 
<script type="text/javascript" src="js/jquery/jquery.ui.core.js"></script> 
<script type="text/javascript" src="js/jquery/jquery.ui.widget.js"></script> 
<script type="text/javascript" src="js/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="js/jquery/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.validationEngine-en.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery/jquery.validationEngine.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery/jquery.ausu-autosuggest.min.js"></script>
<!--jquery ends-->

<style type="text/css">
<!--
	#passwordStrength
    {
	   height:10px;
       display:block;
       float:left;
    }
    
    .strength0
    {
        width:230px;
        background:#cccccc;
    }
     
     .strength1
     {
        width:50px;
        background:#ff0000;
     }
     
     .strength2
     {
        width:100px;
        background:#ff5f5f;
     }
     
     .strength3
     {
        width:150px;
        background:#56e500;
     }
     
     .strength4
     {
        background:#4dcd00;
        width:200px;
     }
     
     .strength5
     {
        background:#399800;
        width:250px;
     }
-->
</style>

<script type="text/javascript"> 
	$(function() {
		$( "#Addmission_Date, #Birth_Date, #Employment_Date, #Release_Date, #Purchase_Date" ).datepicker({
			changeMonth: true,
			changeYear: true,
            showButtonPanel: true,
            showWeek: true,
            firstDay: 1,
            /*numberOfMonths: 3,*/
            showOn: "button",
            buttonImage: "images/cal.gif",
            buttonImageOnly: true,  //this is to show calendar only when icon is clicked and not the input box
            dateFormat: "yy-mm-dd"
            
		}); 
	});
    
	function passwordStrength(password)
    {
	   var desc = new Array();
       desc[0] = "Very Weak";
       desc[1] = "Weak";
       desc[2] = "Better";
       desc[3] = "Medium";
       desc[4] = "Strong";
       desc[5] = "Strongest";
       var score = 0;
       //if password bigger than 6 give 1 point 
       if (password.length > 6) score++; 
       //if password has both lower and uppercase characters give 1 point 
       if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
       //if password has at least one number give 1 point 
       if (password.match(/\d+/)) score++;
       //if password has at least one special caracther give 1 point
       if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;
       //if password bigger than 12 give another 1 point
       if (password.length > 12) score++;
       document.getElementById("passwordDescription").innerHTML = desc[score];
       document.getElementById("passwordStrength").className = "strength" + score;
       }
	   
	   jQuery(function($){
		   $("#Phone").mask("+9999.(999).(999)");
		   $("#Post_Code").mask("99999");
	   });
	   
	   jQuery(document).ready(function(){
		   $("#patient, #doctor, #pharmacy").validationEngine();
   		   $("#user_registration").validationEngine();
   		   $("#labtest").validationEngine();
   		   $("#vaccination").validationEngine();
   		   $("#prescription").validationEngine();
		   
		    $.fn.autosugguest({  
           className: 'ausu-suggest',
          methodType: 'POST',
            minChars: 1,
              rtnIDs: true,
            dataFile: 'includes/autosuggest.php'
			});
   });
   
   jQuery(function() { 
            $('input[id$=\'enableFields\']').click(function() { 
                $('input, select, textarea').not(this).attr('disabled', ''); 
                return false; 
            }); 
        });
</script>  

</head>