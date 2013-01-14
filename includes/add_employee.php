<?php
if(isset($_POST['employee']) && !empty($_POST['employee']))
    Validate::employeeValidation($_POST);
?>
<div class="article" id="addEmployee" style="display:none">
<h2><span>Add Employee</span></h2>
<p><img src="images/icon_required.gif" /> - Required field</p>

<form id="user_registration" name="user_registration" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Last Name</td>
      <td width="100%">
      <input type="text" class="validate[required]" name="Last_Name" id="Last_Name" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Last_Name'])) : '' ?>" size="32" maxlength="32" />
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">First Name</td>
      <td width="100%">
      <input type="text" class="validate[required]" name="First_Name" id="First_Name" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['First_Name'])) : '' ?>" size="32" maxlength="32" />
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Password</td>
      <td width="100%">
      <input type="password" class="validate[required,minSize[8]]" name="Password" id="Password" size="32" maxlength="32" onkeyup="passwordStrength(this.value)" />
      <img src="images/icon_required.gif" align="right" /><br />
      Min. 8 characters; use only letters, numbers or -_
      </td>
    </tr>
    <tr>
    <td width="35%" bgcolor="#dfe6ef">Password Strength
    <td width="100%">
    <div id="passwordDescription">Password not entered</div>
    <div id="passwordStrength" class="strength0"></div>
    </td>
    </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">E-mail address</td>
      <td width="100%">
      <input type="text" class="validate[required,custom[email]]" name="Email" id="Email" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Email'])) : '' ?>" size="32" maxlength="128" />
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Status</td>
      <td width="100%">
      <input type="radio" name="Status" id="Status" value="1" <?php echo (!isset($_POST['Status']) ? "checked='checked'" : isset($_POST['Status']) && $_POST['Status'] == 1) ? "checked='checked'" : ""; ?> />
      <img src="images/enabled.gif" />
      <input type="radio" name="Status" id="Status" value="2" <?php echo (isset($_POST['Status']) && $_POST['Status'] == 2) ? "checked='checked'" : ""; ?> />
      <img src="images/disabled.gif" /><br />
      Allow or disallow this employee to log in to this Back Office
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Profile</td>
      <td>
      <?php $stmt1 = DB::getInstance()->query("select * from profile order by id_profile asc"); ?>
      <select name="Profile" id="Profile" class="validate[required]">
      <option selected="selected">Please select one</option>
      <?php while($row = $stmt1->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_profile']; ?>" id="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
      <?php endwhile; ?>
      </select>
      <?php DB::Close(); ?>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="submit" name="employee" id="employee" value="Create Employee" /> <input type="reset" name="reset" id="reset" value="Reset" />
      </td>
    </tr>
  </table>
</form>
        </div>
        <div class="article">
        <h2><span>Employees</span></h2>
          <p><a href="javascript:;" onmousedown="if(document.getElementById('addEmployee').style.display == 'none'){ document.getElementById('addEmployee').style.display = 'block'; }else{ document.getElementById('addEmployee').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Add new</a>
