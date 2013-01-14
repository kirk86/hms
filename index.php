<?php
require_once('config/config.php');
require_once(INCLUDES_DIR . '/header.php');
?>
<body>
<div class="main">
  <?php require_once(INCLUDES_DIR . '/top_menu.php'); ?>
  <div class="content">
    <div class="content_resize">
        <div class="searchform">
           <form id="formsearch" name="formsearch" method="get" action="index.php">
<!--           <input type="image" name="ToDo" id="ToDo" class="button_search" title="ToDo" value="<?php //echo md5("patient_search"); ?>" src="images/search_btn.gif" alt="ToDo" />-->
           <input type="submit" name="ToDo" id="ToDo" class="button_search" value="Search" />
           <span>
           <div class="ausu-suggest">
           <input type="text" class="editbox_search" name="patient" id="patient" autocomplete="off" maxlength="80" value="" size="25"  />
           </div><!--Cose ausu-suggest div-->
           </span>
            <div class="clr"></div>
           </form>
          </div>
    <div class="mainbar">
    <?php
    if(isset($_GET['ToDo']) && !empty($_GET['ToDo']))
    {
        switch($_GET['ToDo'])
        {
            
            case md5("patients"):
            require_once(INCLUDES_DIR . '/add_patient.php');
            echo " <iframe id='mainboard' frameborder='0' scrolling='auto' height='560' width='720' marginheight='0'
                   marginwidth='0' name='mainboard' title='mainboard' src='includes/mainbar.php'></iframe>";
            break;
            
            case md5("patient_charges"):
            require_once(INCLUDES_DIR . '/add_patient_charge.php');
            require_once(INCLUDES_DIR . '/patient_charges.php');
            break;
            
            case md5("patient_vaccination"):
            require_once(INCLUDES_DIR . '/add_pvaccination.php');
            require_once(INCLUDES_DIR . '/pvaccination.php');
            break;
            
            case md5("patient_labtest"):
            require_once(INCLUDES_DIR . '/add_plabtest.php');
            require_once(INCLUDES_DIR . '/plabtest.php');
            break;
            
            case md5("patient_prescriptions"):
            require_once(INCLUDES_DIR . '/add_prescription.php');
            require_once(INCLUDES_DIR . '/prescription.php');
            break;
            
            case md5("doctors"):
            require_once(INCLUDES_DIR . '/add_doctor.php');
            echo " <iframe id='mainboard' frameborder='0' scrolling='auto' height='560' width='720' marginheight='0'
                   marginwidth='0' name='mainboard' title='mainboard' src='includes/doctors.php'></iframe>";
            break;
            
            case md5("pharmacy_purchase"):
            require_once(INCLUDES_DIR . '/add_pharmacy_pur.php');
            echo " <iframe id='mainboard' frameborder='0' scrolling='auto' height='560' width='720' marginheight='0'
                   marginwidth='0' name='mainboard' title='mainboard' src='includes/pharmacy_pur.php'></iframe>";
            break;
            
            case md5("employees"):
            require_once(INCLUDES_DIR . '/add_employee.php');
            require_once(INCLUDES_DIR . '/employees.php');
            break;
			
            case "Search":
            require_once(INCLUDES_DIR . '/patient_file.php');
            break;
            
            default:
            require_once(INCLUDES_DIR . '/add_patient.php');
            echo "<iframe id='mainboard' frameborder='0' scrolling='auto' height='560' width='720' marginheight='0'
                  marginwidth='0' name='mainboard' title='mainboard' src='includes/mainbar.php'></iframe>";
            break;
        }
    }
    else
    {
       require_once(INCLUDES_DIR . "/add_patient.php"); 
       echo "<iframe id='mainboard' frameborder='0' scrolling='auto' height='560' width='720' marginheight='0'
            marginwidth='0' name='mainboard' title='mainboard' src='includes/mainbar.php'></iframe>";
    }
    ?>
       </div>
      </div>
      <div class="sidebar">
        <div class="searchform">
          <?php echo (isset($_SESSION['id_employee']) && !empty($_SESSION['id_employee'])) ? "<img src='images/userpic.gif' align='top' />&nbsp;Welcome ".$_SESSION['firstname']." ".$_SESSION['lastname']."!&nbsp;&nbsp;<a href='index.php?ToDo=logout'>(Logout)</a>" : ""; ?>
        </div>
        <?php require_once(INCLUDES_DIR . '/mainmenu.php'); ?>
        
      </div>
      <div class="clr"></div>
    </div>
  </div>

  <div class="fbg">
    
  </div>
  <?php require_once(INCLUDES_DIR . '/footer.php'); ?>
</div>
</body>
</html>