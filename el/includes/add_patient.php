<?php

if(isset($_POST['patient']) && !empty($_POST['patient']))
Validate::patientValidation($_POST);
?>
<div class="article" id="addPatient" style="display:none">
<h2><span>Προσθήκη Ασθενή</span></h2>
<p><img src="images/icon_required.gif" /> - Απαιτούμενο πεδίο</p>
<p>

<form id="patient" name="form1" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Κατηγορία</td>
      <td width="100%">
      <select name="category" id="category" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <option value="1" id="inpatient">Εσωτερικός Ασθενής</option>
      <option value="2" id="discharged">Απαλλαγμένος</option>
      <option value="3" id="died">Αποθανών
      </option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Ιατρός</td>
      <td width="100%">
      <select name="doctor" id="doctor" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <?php $stmt = DB::getInstance()->query("select * from doctor where doctor.id_lang = 2 order by id_doctor asc"); ?>
      <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_doctor']?>" id="<?php echo $row['doctorName']." ".$row['doctorSurname']; ?>"><?php echo $row['doctorName']." ".$row['doctorSurname']; ?></option>
      <?php endwhile; ?>
      <?php DB::Close(); ?>
      </option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Όνομα</td>
      <td><input type="text" class="validate[required]" name="First_Name" id="First_Name" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['First_Name'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Επώνυμο</td>
      <td><input type="text" class="validate[required]" name="Last_Name" id="Last_Name" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Last_Name'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Ημερ/νία Εισαγωγής</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Addmission_Date" id="Addmission_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Addmission_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Φύλο</td>
      <td>
      <input type="radio" name="sex" id="sex" value="Άρρεν" checked="checked" /> Άρρεν
      <input type="radio" name="sex" id="sex" value="Θύλη" /> Θύλη
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Ημερ/νία Γέννησης</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Birth_Date" id="Birth_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Birth_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Ομάδα Αίματος</td>
      <td>
        <input type="radio" name="Blood_Group" id="Blood_Group" value="A" checked="checked" /> A 
        <input type="radio" name="Blood_Group" id="Blood_Group" value="B" /> B 
        <input type="radio" name="Blood_Group" id="Blood_Group" value="AB" /> AB 
        <input type="radio" name="Blood_Group" id="Blood_Group" value="O" /> O
        </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Οικογενειακή Κατάσταση</td>
      <td>
      <select name="Civil_Status" id="Civil_Status" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <option value="Ανύπαντρος" id="Civil_Status">Ανύπαντρος</option>
      <option value="Παντρεμένος" id="Civil_Status">Παντρεμένος</option>
      <option value="Διαζευγμένος" id="Civil_Status">Διαζευγμένος</option>
      <option value="Χήρος" id="Civil_Status">Χήρος</option>
      <option value="Ενδιάσταση" id="Civil_Status">Ενδιάσταση</option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Διεύθυνση</td>
      <td><input type="text" class="validate[required]" name="Address" id="Address" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Address'])) : '' ?>" />
      Nr.<input type="text" class="validate[required]" name="Address_Nr" id="Address_Nr" size="4" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Address_Nr'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Ταχυδρομικός Κωδικός</td>
      <td><input type="text" class="validate[required,minSize[5]]" name="Post_Code" id="Post_Code" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Post_Code'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Τηλέφωνο</td>
      <td><input type="text" class="validate[required]" name="Phone" id="Phone" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Phone'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Είδος Ασφάλισης</td>
      <td>
        <input type="radio" name="insuranceType" id="insuranceType" value="Ιδιωτική" checked="checked" /> Ιδιωτική 
        <input type="radio" name="insuranceType" id="insuranceType" value="Δημόσια" /> Δημόσια 
        <input type="radio" name="insuranceType" id="insuranceType" value="Αυτοτελής" /> Αυτοτελής
        </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Ίδρυμα Ασφάλισης</td>
      <td><input type="text" class="validate[required]" name="Insurance_Company" id="Insurance_Company" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Insurance_Company'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
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
      <td bgcolor="#dfe6ef">Επείγoν</td>
      <td>
      <select name="urgency" id="urgency" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <option value="1" id="1">1</option>
      <option value="2" id="2">2</option>
      <option value="3" id="3">3</option>
      <option value="4" id="4">4</option>
      <option value="5" id="5">5</option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Χρεώση</td>
      <td>
      <input type="text" class="validate[required]" name="Charges" id="Charges" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Charges'])) : '' ?>" /><img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Διάγνωση</td>
      <td><textarea name="diagnosis" id="diagnosis" cols="28" rows="5"></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="submit" name="patient" id="patient" value="Δημιουργία Ασθενή" /> <input type="reset" name="reset" id="reset" value="Καθαρισμός" />
      </td>
    </tr>
  </table>
</form>

        </div>
        <div class="article">
        <h2><span>Ασθενείς </span></h2>
          <p><a href="javascript:;" onmousedown="if(document.getElementById('addPatient').style.display == 'none'){ document.getElementById('addPatient').style.display = 'block'; }else{ document.getElementById('addPatient').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Προσθήκη νέου</a>
