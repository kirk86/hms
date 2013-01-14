<?php
if(isset($_POST['patient']) && !empty($_POST['patient']))
Validate::patientValidation($_POST);
?>
<div class="article" id="addPatient" style="display:none">
<h2><span>Add Patient</span></h2>
<p><img src="images/icon_required.gif" /> - Required field</p>
<p>

<form id="patient" name="form1" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Category</td>
      <td width="100%">
      <select name="category" id="category" class="validate[required]">
      <option selected="selected">Please select one</option>
      <option value="1" id="inpatient">Inpatient</option>
      <option value="2" id="discharged">Discharged</option>
      <option value="3" id="died">Died
      </option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Doctor</td>
      <td width="100%">
      <select name="doctor" id="doctor" class="validate[required]">
      <option selected="selected">Please select one</option>
      <?php $stmt = DB::getInstance()->query("select * from doctor where doctor.id_lang = 1 order by id_doctor asc"); ?>
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
      <td bgcolor="#dfe6ef">First Name</td>
      <td><input type="text" class="validate[required]" name="First_Name" id="First_Name" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['First_Name'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Last Name</td>
      <td><input type="text" class="validate[required]" name="Last_Name" id="Last_Name" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Last_Name'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Addmission Date</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Addmission_Date" id="Addmission_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Addmission_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Sex</td>
      <td>
      <input type="radio" name="sex" id="sex" value="Male" checked="checked" /> Male
      <input type="radio" name="sex" id="sex" value="Female" /> Female
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Date of Birth</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Birth_Date" id="Birth_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Birth_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Blood Group</td>
      <td>
        <input type="radio" name="Blood_Group" id="Blood_Group" value="A" checked="checked" /> A 
        <input type="radio" name="Blood_Group" id="Blood_Group" value="B" /> B 
        <input type="radio" name="Blood_Group" id="Blood_Group" value="AB" /> AB 
        <input type="radio" name="Blood_Group" id="Blood_Group" value="O" /> O
        </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Civil Status</td>
      <td>
      <select name="Civil_Status" id="Civil_Status" class="validate[required]">
      <option selected="selected">Please select one</option>
      <option value="Single" id="Civil_Status">Single</option>
      <option value="Married" id="Civil_Status">Married</option>
      <option value="Divorced" id="Civil_Status">Divorced</option>
      <option value="Widowed" id="Civil_Status">Widowed</option>
      <option value="Seperated" id="Civil_Status">Seperated</option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Address</td>
      <td><input type="text" class="validate[required]" name="Address" id="Address" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Address'])) : '' ?>" />
      Nr.<input type="text" class="validate[required]" name="Address_Nr" id="Address_Nr" size="4" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Address_Nr'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Post Code</td>
      <td><input type="text" class="validate[required,minSize[5]]" name="Post_Code" id="Post_Code" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Post_Code'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Phone</td>
      <td><input type="text" class="validate[required]" name="Phone" id="Phone" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Phone'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Insurance Type</td>
      <td>
        <input type="radio" name="insuranceType" id="insuranceType" value="Private" checked="checked" /> Private 
        <input type="radio" name="insuranceType" id="insuranceType" value="Public" /> Public 
        <input type="radio" name="insuranceType" id="insuranceType" value="Self Founded" /> Self Founded
        </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Insurance Company</td>
      <td><input type="text" class="validate[required]" name="Insurance_Company" id="Insurance_Company" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Insurance_Company'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
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
      <td bgcolor="#dfe6ef">Urgency</td>
      <td>
      <select name="urgency" id="urgency" class="validate[required]">
      <option selected="selected">Please select one</option>
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
      <td bgcolor="#dfe6ef">Charges</td>
      <td>
      <input type="text" class="validate[required]" name="Charges" id="Charges" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Charges'])) : '' ?>" /><img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Diagnosis</td>
      <td><textarea name="diagnosis" id="diagnosis" cols="28" rows="5"></textarea></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="submit" name="patient" id="patient" value="Create Patient" /> <input type="reset" name="reset" id="reset" value="Reset" />
      </td>
    </tr>
  </table>
</form>

        </div>
        <div class="article">
        <h2><span>Patients </span></h2>
          <p><a href="javascript:;" onmousedown="if(document.getElementById('addPatient').style.display == 'none'){ document.getElementById('addPatient').style.display = 'block'; }else{ document.getElementById('addPatient').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Add new</a>
