<?php
if(isset($_POST['pvaccination']) && !empty($_POST['pvaccination']))
Validate::vaccinationValidation($_POST);   
?>
<link href="css/calendar.css" rel="stylesheet" type="text/css" />
<!--calendar files-->
<script type="text/javascript" src="js/calendar/calendar_db.js"></script>
<!--calendar ends-->
<div class="article" id="addPvaccination" style="display:none">
<h2><span>Add Patient Vaccination</span></h2>
<p><img src="images/icon_required.gif" /> - Required field</p>
<p>

<form id="vaccination" name="form1" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Doctor</td>
      <td width="100%">
      <?php $stmt = DB::getInstance()->query("select * from doctor where doctor.id_lang = 1 order by id_doctor asc"); ?>
      <select name="doctor" id="doctor" class="validation[required]">
      <option selected="selected">Please select one</option>
      <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_doctor']; ?>" id="<?php echo $row['doctorName']." ".$row['doctorSurname']; ?>"><?php echo $row['doctorName']." ".$row['doctorSurname']; ?></option>
      <?php endwhile; ?>
      </select>
      <?php DB::Close(); ?>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Patient</td>
      <td width="100%">
      <?php $stmt1 = DB::getInstance()->query("select * from patient where patient.id_lang = 1 order by id_patient asc"); ?>
      <select name="patient" id="patient" class="validation[required]">
      <option selected="selected">Please select one</option>
      <?php while($row = $stmt1->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_patient']; ?>" id="<?php echo $row['firstName']." ".$row['lastName']; ?>"><?php echo $row['firstName']." ".$row['lastName']; ?></option>
      <?php endwhile; ?>
      </select>
      <?php DB::Close(); ?>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Vaccination Date</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Vaccination_Date" id="Vaccination_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Vaccination_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /> <script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form1',
		// input name
		'controlname': 'Vaccination_Date'
	});

	</script></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Vaccination</td>
      <td>
      <select name="vaccination" id="vaccination" class="validate[required]">
      <option selected="selected">Please select one</option>
      <option value="Hilara" id="Vaccination">Hilara</option>
      <option value="Eps B" id="Vaccination">Eps B</option>
      <option value="Hiv" id="Vaccination">Hiv</option>
      <option value="Chicken Flue" id="Vaccination">Chicken Flue</option>
      <option value="Hipatitis" id="Vaccination">Hipatitis</option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Language</td>
      <td>
        <input type="radio" name="id_lang" id="id_lang" value="1" <?php echo (!isset($_POST['id_lang']) ? 'checked="checked"' : isset($_POST['id_lang']) && $_POST['id_lang'] == 1) ? 'checked="checked"' : ""; ?> /> 
        <img src="images/1.jpg" width="16" height="11" alt="English" />
        <input type="radio" name="id_lang" id="id_lang" value="2" <?php echo (isset($_POST['id_lang']) && $_POST['id_lang'] == 2) ? 'checked="checked"' : ""; ?> /> 
        <img src="images/2.jpg" width="16" height="11" alt="Greek" />
        </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="submit" name="pvaccination" id="pvaccination" value="Create Vaccination" /> <input type="reset" name="reset" id="reset" value="Reset" />
      </td>
    </tr>
  </table>
</form>
        </div>
        <div class="article">
        <h2><span>Patient Vaccination </span></h2>
          <p><a href="javascript:;" onmousedown="if(document.getElementById('addPvaccination').style.display == 'none'){ document.getElementById('addPvaccination').style.display = 'block'; }else{ document.getElementById('addPvaccination').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Add new</a>
