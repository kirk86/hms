<?php

if(isset($_POST['purchase']) && !empty($_POST['purchase']))
Validate::purchaseValidation($_POST);
?>
<div class="article" id="addPurchase" style="display:none">
<h2><span>Add Pharmacy Purchase</span></h2>
<p><img src="images/icon_required.gif" /> - Required field</p>
<p>

<form id="pharmacy" name="form1" method="post" action="">
  <table width="63%" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td bgcolor="#dfe6ef">Purchase Date</td>
      <td><input type="text" class="validate[required,custom[date]]" name="Purchase_Date" id="Purchase_Date" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Purchase_Date'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
     <tr>
      <td bgcolor="#dfe6ef">Supplier</td>
      <td>
      <select name="supplier" id="supplier" class="validate[required]">
      <option selected="selected">Please select one</option>
      <option value="Lexon" id="supplier">Lexon</option>
      <option value="Arvis Industries" id="supplier">Arvis Industries</option>
      <option value="Chemical CE" id="supplier">Chemical CE</option>
      <option value="Global Msc" id="supplier">Global Msc</option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Pharmacy Product</td>
      <td width="100%">
      <select name="Pharmacy_Product" id="Pharmacy_Product" class="validate[required]">
      <option selected="selected">Please select one</option>
      <option value="Agumentin" id="Pharmacy_Product">Agumentin</option>
      <option value="Depon" id="Pharmacy_Product">Depon</option>
      <option value="Aspirin" id="Pharmacy_Product">Aspirin</option>
      <option value="Cortizon" id="Pharmacy_Product">Cortizon</option>
      <option value="Ronal" id="Pharmacy_Product">Ronal</option>
      </select>
      <img src="images/icon_required.gif" align="right" />
      </td>
    </tr>
    <tr>
      <td width="35%" bgcolor="#dfe6ef">Doctor</td>
      <td width="100%">
      <select name="doctor" id="doctor">
      <option selected="selected">Please select one</option>
      <?php $stmt = DB::getInstance()->query("select * from doctor where doctor.id_lang = 1 order by id_doctor asc"); ?>
      <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
      <option value="<?php echo $row['id_doctor']?>" id="<?php echo $row['doctorName']." ".$row['doctorSurname']; ?>"><?php echo $row['doctorName']." ".$row['doctorSurname']; ?></option>
      <?php endwhile; ?>
      <?php DB::Close(); ?>
      </option>
      </select>
      </td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Quantity</td>
      <td><input type="text" class="validate[required]" name="Quantity_" id="Quantity_" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Quantity_'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Unit Price</td>
      <td><input type="text" class="validate[required]" name="Unit_Price" id="Unit_Price" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Unit_Price'])) : '' ?>" /><img src="images/icon_required.gif" align="right" /></td>
    </tr>
    
    <tr>
      <td bgcolor="#dfe6ef">Status</td>
      <td>
      <input type="radio" name="status" id="status" value="On order" checked="checked" /> On order
          <input type="radio" name="status" id="status" value="Received"  disabled="disabled"/> Received
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
      <td bgcolor="#dfe6ef">Discount %</td>
      <td><input type="text" name="Discount" id="Discount" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Discount'])) : '' ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#dfe6ef">Tax %</td>
      <td><input type="text" name="Tax" id="Tax" value="<?php echo (!empty(Validate::$errors) && sizeof(Validate::$errors) > 0) || (!empty(Validate::$empty) && sizeof(Validate::$empty) > 0) ? strip_tags(trim($_POST['Tax'])) : '' ?>" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="submit" name="purchase" id="purchase" value="Create Purchase" /> <input type="reset" name="reset" id="reset" value="Reset" />
      </td>
    </tr>
  </table>
</form>

        </div>
        <div class="article">
        <h2><span>Pharmacy Purchases </span></h2>
          <p><a href="javascript:;" onmousedown="if(document.getElementById('addPurchase').style.display == 'none'){ document.getElementById('addPurchase').style.display = 'block'; }else{ document.getElementById('addPurchase').style.display = 'none'; }">
          <img src="images/add.gif" border="0" />&nbsp;Add new</a>
