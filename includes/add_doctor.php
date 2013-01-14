<?php

if(isset($_POST['doctor']) && !empty($_POST['doctor']))
Validate::doctorValidation($_POST);
?>
<div class="article" id="addDoctor" style="display:none">
<h2><span>Add Doctor</span></h2>
<p><img src="images/icon_required.gif" /> - Required field</p>
<p>

<form id="doctor" name="form1" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
  <?php $stmt = DB::getInstance()->query("select * from department where department.id_lang = 1 order by id_department asc"); ?>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Department</td>
      <td width="100%">
      <select name="department" id="department" class="validate[required]">
      <option selected="selected">Please select one</option>
      <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_department']; ?>" id="<?php echo $row['department_name']; ?>"><?php echo $row['department_name']; ?></option>
      <?php endwhile; ?>
      <?php DB::Close(); ?>
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
      <td bgcolor="#dfe6ef">Employment Date</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Employment_Date" id="Employment_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Employment_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Sex</td>
      <td>
      <input type="radio" name="sex" id="sex" value="Male" checked="checked" /> Male
          <input type="radio" name="sex" id="sex" value="Female" /> Female
        </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Release Date</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Release_Date" id="Release_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Release_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
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
      <td bgcolor="#dfe6ef">Specialization</td>
      <td><input type="text" class="validate[required]" name="Specialization" id="Specialization" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Specialization'])) : '' ?>" />
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Salary</td>
      <td><input type="text" class="validate[required]" name="Salary" id="Salary" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Salary'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">E-mail</td>
      <td><input type="text" class="validate[required,custom[email]]" name="Email" id="Email" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Email'])) : '' ?>" size="32" maxlength="128" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Phone</td>
      <td><input type="text" class="validate[required]" name="Phone" id="Phone" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Phone'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="submit" name="doctor" id="doctor" value="Create Doctor" /> <input type="reset" name="reset" id="reset" value="Reset" />
      </td>
    </tr>
  </table>
</form>
        </div>
        <div class="article">
        <h2><span>Doctors </span></h2>
          <p><a href="javascript:;" onmousedown="if(document.getElementById('addDoctor').style.display == 'none'){ document.getElementById('addDoctor').style.display = 'block'; }else{ document.getElementById('addDoctor').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Add new</a>
