<?php

if(isset($_POST['doctor']) && !empty($_POST['doctor']))
Validate::doctorValidation($_POST);
?>
<div class="article" id="addDoctor" style="display:none">
<h2><span>Προσθήκη Ιατρού</span></h2>
<p><img src="images/icon_required.gif" /> - Απαιτούμενο πεδίο</p>
<p>

<form id="doctor" name="form1" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
  <?php $stmt = DB::getInstance()->query("select * from department where id_lang = 2 order by id_department asc"); ?>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Τμήμα</td>
      <td width="100%">
      <select name="department" id="department" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_department']; ?>" id="<?php echo $row['department_name']; ?>"><?php echo $row['department_name']; ?></option>
      <?php endwhile; ?>
      <?php DB::Close(); ?>
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
      <td bgcolor="#dfe6ef">Ημερ/νία Πρόσληψης</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Employment_Date" id="Employment_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Employment_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Φύλο</td>
      <td>
      <input type="radio" name="sex" id="sex" value="Άρρεν" checked="checked" /> Άρρεν
          <input type="radio" name="sex" id="sex" value="Θύλη" /> Θύλη
        </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Ημερ/νία Απόλυσης</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Release_Date" id="Release_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Release_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
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
      <td bgcolor="#dfe6ef">Ειδικότητα</td>
      <td><input type="text" class="validate[required]" name="Specialization" id="Specialization" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Specialization'])) : '' ?>" />
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Μισθός</td>
      <td><input type="text" class="validate[required]" name="Salary" id="Salary" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Salary'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
     <tr>
      <td bgcolor="#dfe6ef">E-mail</td>
      <td><input type="text" class="validate[required,custom[email]]" name="Email" id="Email" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Email'])) : '' ?>" size="32" maxlength="128" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Τηλέφωνο</td>
      <td><input type="text" class="validate[required]" name="Phone" id="Phone" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Phone'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="submit" name="doctor" id="doctor" value="Δημιουργία Ιατρού" /> <input type="reset" name="reset" id="reset" value="Καθαρισμός" />
      </td>
    </tr>
  </table>
</form>
        </div>
        <div class="article">
        <h2><span>Ιατροί </span></h2>
          <p><a href="javascript:;" onmousedown="if(document.getElementById('addDoctor').style.display == 'none'){ document.getElementById('addDoctor').style.display = 'block'; }else{ document.getElementById('addDoctor').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Προσθήκη νέου</a>
