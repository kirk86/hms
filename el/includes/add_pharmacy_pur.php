<?php

if(isset($_POST['purchase']) && !empty($_POST['purchase']))
Validate::purchaseValidation($_POST);
?>
<div class="article" id="addPurchase" style="display:none">
<h2><span>Προθήκη Αγοράς Φαρμάκων</span></h2>
<p><img src="images/icon_required.gif" /> - Απαιτούμενο πεδίο</p>
<p>

<form id="pharmacy" name="form1" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td bgcolor="#dfe6ef">Ημερ/νία Αγοράς</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Purchase_Date" id="Purchase_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Purchase_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
     <tr>
      <td bgcolor="#dfe6ef">Προμηθευτής</td>
      <td>
      <select name="supplier" id="supplier" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <option value="Λέξον" id="supplier">Λέξον</option>
      <option value="Βιομηχανίες Άρβις" id="supplier">Βιομηχανίες Άρβις</option>
      <option value="Χημική ΑΕ" id="supplier">Χημική ΑΕ</option>
      <option value="Όμικρον ΑΕ" id="supplier">Όμικρον ΑΕ</option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Φαρμεκευτικό Προϊόν</td>
      <td width="100%">
      <select name="Pharmacy_Product" id="Pharmacy_Product" class="validate[required]">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <option value="Αγουμεντίν" id="Pharmacy_Product">Αγουμεντίν</option>
      <option value="Ντεπόν" id="Pharmacy_Product">Ντεπόν</option>
      <option value="Ασπιρίνη" id="Pharmacy_Product">Ασπιρίνη</option>
      <option value="Κορτιζόνη" id="Pharmacy_Product">Κορτιζόνη</option>
      <option value="Ρονάλ" id="Pharmacy_Product">Ρονάλ</option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Ιατρός</td>
      <td width="100%">
      <select name="doctor" id="doctor">
      <option selected="selected">Παρακαλώ επιλέξτε</option>
      <?php $stmt = DB::getInstance()->query("select * from doctor where doctor.id_lang = 2 order by id_doctor asc"); ?>
      <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_doctor']?>" id="<?php echo $row['doctorName']." ".$row['doctorSurname']; ?>"><?php echo $row['doctorName']." ".$row['doctorSurname']; ?></option>
      <?php endwhile; ?>
      <?php DB::Close(); ?>
      </option>
      </select>
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Ποσότητα</td>
      <td><input type="text" class="validate[required]" name="Quantity_" id="Quantity_" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Quantity_'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Τιμή Μονάδας</td>
      <td><input type="text" class="validate[required]" name="Unit_Price" id="Unit_Price" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Unit_Price'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    
    <tr>
      <td bgcolor="#dfe6ef">Κατάσταση</td>
      <td>
      <input type="radio" name="status" id="status" value="Σε παραγγελία" checked="checked" /> Σε παραγγελία
          <input type="radio" name="status" id="status" value="Παραλήφθηκε"  disabled="disabled"/> Παραλήφθηκε
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
      <td bgcolor="#dfe6ef">Έκπτωση %</td>
      <td><input type="text" name="Discount" id="Discount" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Discount'])) : '' ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Φόρος %</td>
      <td><input type="text" name="Tax" id="Tax" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Tax'])) : '' ?>" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="submit" name="purchase" id="purchase" value="Δημιουργία Παραγελλίας" /> <input type="reset" name="reset" id="reset" value="Καθαρισμός" />
      </td>
    </tr>
  </table>
</form>

        </div>
        <div class="article">
        <h2><span>Αγορές Φαρμάκων </span></h2>
          <p><a href="javascript:;" onmousedown="if(document.getElementById('addPurchase').style.display == 'none'){ document.getElementById('addPurchase').style.display = 'block'; }else{ document.getElementById('addPurchase').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Προσθήκη νέου</a>
