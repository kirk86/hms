<?php

if(isset($_POST['patient']) && !empty($_POST['patient']))
Validate::patientValidation($_POST);
?>
<div class="article" id="addPatientCharges" style="display:none">
<h2><span>Add Patient</span></h2>
<p><img src="images/icon_required.gif" /> - Required field</p>
<p>

<form name="form1" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Category</td>
      <td width="65%">
      <select name="category" id="category">
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
      <td width="65%">
      <select name="doctor" id="doctor">
      <option selected="selected">Please select one</option>
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
      <td bgcolor="#dfe6ef">First Name</td>
      <td><input type="text" name="First_Name" id="First_Name" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['First_Name'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Last Name</td>
      <td><input type="text" name="Last_Name" id="Last_Name" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Last_Name'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Addmission Date</td>
      <td><input type="text" name="Addmission_Date" id="Addmission_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Addmission_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
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
      <td><input type="text" name="Birth_Date" id="Birth_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Birth_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
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
      <select name="Civil_Status" id="Civil_Status">
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
      <td><input type="text" name="Address" id="Address" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Address'])) : '' ?>" />
      Nr.<input type="text" name="Address_Nr" id="Address_Nr" size="4" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Address_Nr'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Post Code</td>
      <td><input type="text" name="Post_Code" id="Post_Code" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Post_Code'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Phone</td>
      <td><input type="text" name="Phone" id="Phone" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Phone'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
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
      <td><input type="text" name="Insurance_Company" id="Insurance_Company" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Insurance_Company'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Urgency</td>
      <td>
      <select name="urgency" id="urgency">
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
      <input type="text" name="Charges" id="Charges" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Charges'])) : '' ?>" /><img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Diagnosis</td>
      <td><textarea name="diagnosis" id="diagnosis" cols="30" rows="5"></textarea></td>
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
        <h2><span>Χρεώσεις Ασθενών </span></h2>
          <!--<p><a href="javascript:;" onmousedown="if(document.getElementById('addPatientCharges').style.display == 'none'){ document.getElementById('addPatientCharges').style.display = 'block'; }else{ document.getElementById('addPatientCharges').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Add new</a></p>-->
