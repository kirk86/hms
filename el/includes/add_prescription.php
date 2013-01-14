<?php
if(isset($_POST['prescription']) && !empty($_POST['prescription']))
Validate::prescriptionValidation($_POST);   
?>
<link href="css/calendar.css" rel="stylesheet" type="text/css" />
<!--calendar files-->
<script type="text/javascript" src="js/calendar/calendar_db.js"></script>
<!--calendar ends-->
<div class="article" id="addPrescription" style="display:none">
<h2><span>Προσθήκη Συνταγογράφησης Ασθενή </span></h2>
<p><img src="images/icon_required.gif" /> - Απατούμενο πεδίο</p>
<p>

<form id="prescription" name="form1" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Ιατρός</td>
      <td width="100%">
      <?php $stmt = DB::getInstance()->query("select * from doctor where doctor.id_lang = 2 order by id_doctor asc"); ?>
      <select name="doctor" id="doctor" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_doctor']; ?>" id="<?php echo $row['doctorName']." ".$row['doctorSurname']; ?>"><?php echo $row['doctorName']." ".$row['doctorSurname']; ?></option>
      <?php endwhile; ?>
      </select>
      <?php DB::Close(); ?>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Ασθενής</td>
      <td width="100%">
      <?php $stmt1 = DB::getInstance()->query("select * from patient where patient.id_lang = 2 order by id_patient asc"); ?>
      <select name="patient" id="patient" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <?php while($row = $stmt1->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_patient']; ?>" id="<?php echo $row['firstName']." ".$row['lastName']; ?>"><?php echo $row['firstName']." ".$row['lastName']; ?></option>
      <?php endwhile; ?>
      </select>
      <?php DB::Close(); ?>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Ημερ/νία Συνταγ/φησης</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Prescription_Date" id="Prescription_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Prescription_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /><script language="JavaScript">
	new tcal ({
		// form name
		'formname': 'form1',
		// input name
		'controlname': 'Prescription_Date'
	});

	</script></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Φάρμακο</td>
      <td>
      <select name="product" id="product" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <option value="Αγουμεντίν" id="product">Αγουμεντίν</option>
      <option value="Ντεπόν" id="product">Ντεπόν</option>
      <option value="Ασπιρίνη" id="product">Ασπιρίνη</option>
      <option value="Κορτιζόνη" id="product">Κορτιζόνη</option>
      <option value="Ρονάλ" id="product">Ρονάλ</option>
      </select>
      </td>
    </tr>
     <tr>
      <td bgcolor="#dfe6ef">Γλώσσα</td>
      <td>
        <input type="radio" name="id_lang" id="id_lang" value="1" <?php echo (isset($_POST['id_lang']) && $_POST['id_lang'] == 1) ? 'checked="checked"' : ""; ?> /> 
        <img src="images/1.jpg" width="16" height="11" alt="English" />
        <input type="radio" name="id_lang" id="id_lang" value="2" <?php echo (!isset($_POST['id_lang']) ? 'checked="checked"' : isset($_POST['id_lang']) && $_POST['id_lang'] == 2) ? 'checked="checked"' : ""; ?> /> 
        <img src="images/2.jpg" width="16" height="11" alt="Greek" />
        </td>
    </tr>
     <tr>
      <td bgcolor="#dfe6ef">Συνταγ/φηση</td>
      <td><img src="images/icon_required.gif" align="right" />
      <textarea name="Prescription_" id="Prescription_" cols="28" rows="5" ><?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Prescription_'])) : '' ?></textarea>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="submit" name="prescription" id="prescription" value="Δημιουργία Συνταγογράφησης" /> <input type="reset" name="reset" id="reset" value="Καθαρισμός" />
      </td>
    </tr>
  </table>
</form>
        </div>
        <div class="article">
        <h2><span>Συνταγογράφηση Ασθενή </span></h2>
          <p><a href="javascript:;" onmousedown="if(document.getElementById('addPrescription').style.display == 'none'){ document.getElementById('addPrescription').style.display = 'block'; }else{ document.getElementById('addPrescription').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Προσθήκη νέου</a>
