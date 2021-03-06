<?php
require_once ('functions.php');
__autoload("pdo");
?>
<body>
<?php
if (isset($_GET['ToDo']) && $_GET['ToDo'] == "Search" && isset($_GET['patient']) &&
    empty($_GET['patient'])) {echo "<font color='#FF0000'><b>Παρακαλώ εισάγετε όνομα ασθενή!</b></font>";}

if (isset($_GET['ToDo']) && $_GET['ToDo'] == "Search" && isset($_GET['patient']) &&
    !empty($_GET['patient'])):
	
	if (get_magic_quotes_gpc())
		$query_string = @stripslashes(strip_tags(trim($_GET['patient']))); 
	else
		$query_string = @addslashes(strip_tags(trim($_GET['patient'])));

    try {
        $stmt = DB::getInstance()->query("SELECT * FROM patient 
								 LEFT OUTER JOIN category on patient.id_category = category.id_category
								 LEFT OUTER JOIN doctor on patient.id_doctor = doctor.id_doctor
								 WHERE CONCAT(patient.firstName, ' ', lastName) LIKE '$query_string%' ");
        $patient = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $patient['firstName'] = $row['firstName'];
            $patient['lastName'] = $row['lastName'];
            $patient['registrationDate'] = $row['registrationDate'];
            $patient['birthDate'] = $row['birthDate'];
            $patient['sex'] = $row['sex'];
            $patient['bloodGroup'] = $row['bloodGroup'];
            $patient['civilStatus'] = $row['civilStatus'];
            $patient['address'] = $row['address'];
            $patient['postCode'] = $row['postCode'];
            $patient['insuranceType'] = $row['insuranceType'];
            $patient['insuranceCompany'] = $row['insuranceCompany'];
            $patient['phone'] = $row['phone'];
            $patient['diagnosis'] = $row['diagnosis'];
            $patient['charges'] = $row['charges'];
            $patient['category_name'] = $row['category_name'];
            $patient['doctorName'] = $row['doctorName'];
            $patient['doctorSurname'] = $row['doctorSurname'];
            $patient['urgency'] = $row['urgency'];
        }
        DB::Close();
?>
      <div id="container" class="mainbar">
<form action="vars.php" method="post" class="niceform">
	<fieldset>
    	<legend>Προσωπικές Πληροφορίες</legend>
        <dl>
        	<dt><label for="First_Name">Όνομα:</label></dt>
            <dd><input type="text" name="First_Name" id="First_Name" size="32" maxlength="50" value="<?php echo
        !empty($patient['firstName']) ? $patient['firstName'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" /></dd>
        </dl>
        <dl>
        	<dt><label for="Last_Name">Επώνυμο:</label></dt>
            <dd><input type="text" name="Last_Name" id="Last_Name" size="32" maxlength="50" value="<?php echo
            !empty($patient['lastName']) ? $patient['lastName'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" /></dd>
        </dl>
        <dl>
        	<dt><label for="dobMonth">Ημερ/νία Εισαγωγής:</label></dt>
            <dd>
                <input type="text" name="Admission-Date" id="Admission-Date" size="32" maxlength="50" value="<?php echo
        !empty($patient['registrationDate']) ? $patient['registrationDate'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" />
            </dd>
        </dl>
        <dl>
        	<dt><label for="sex">Φύλο:</label></dt>
            <dd>
                <input type="text" name="sex" id="sex" size="32" maxlength="50" value="<?php echo
       !empty($patient['sex']) ? $patient['sex'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" />
            </dd>
        </dl>
        <dl>
        	<dt><label for="dobMonth">Ημερ/νία Γέννησης:</label></dt>
            <dd>
                <input type="text" name="Birth-Date" id="Birth-Date" size="32" maxlength="50" value="<?php echo
        !empty($patient['birthDate']) ? $patient['birthDate'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" />
            </dd>
        </dl>
        <dl>
        	<dt><label for="color">Όμαδα Αίματος:</label></dt>
            <dd>
                <input type="text" name="Blood_Group" id="Blood_Group" size="32" maxlength="50" value="<?php echo
        !empty($patient['bloodGroup']) ? $patient['bloodGroup'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" />
            </dd>
        </dl>
        <dl>
        	<dt><label for="Civil_Status">Οικογ. Κατάσταση:</label></dt>
            <dd>
                <input type="text" name="Civil_Status" id="Civil_Status" size="32" maxlength="50" value="<?php echo
        !empty($patient['civilStatus']) ? $patient['civilStatus'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" />
            </dd>
        </dl>
       <dl>
        	<dt><label for="Address">Διεύθυνση:</label></dt>
            <dd><input type="text" name="Address" id="Address" size="32" maxlength="50" value="<?php echo
		!empty($patient['address']) ? $patient['address'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" /></dd>
        </dl>
        <dl>
        	<dt><label for="Post_Code">Ταχυδρομικός Κωδικός:</label></dt>
            <dd><input type="text" name="postCode" id="postCode" size="32" maxlength="5" value="<?php echo
        !empty($patient['postCode']) ? $patient['postCode'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" /></dd>
        </dl>
        <dl>
        	<dt><label for="Phone">Τηλέφωνο:</label></dt>
            <dd><input type="text" name="phone" id="phone" size="32" maxlength="10" value="<?php echo
       !empty($patient['phone']) ? $patient['phone'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" /></dd>
        </dl> 
    </fieldset>
    
    <fieldset>
    	<legend>Προτιμήσεις</legend>
        <dl>
        	<dt><label for="category">Κατηγορία:</label></dt>
            <dd>
                <input type="text" name="category" id="category" size="32" maxlength="50" value="<?php echo
        !empty($patient['category_name']) ? $patient['category_name'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" />
            </dd>
        </dl>
        <dl>
        	<dt><label for="doctor">Ιατρός:</label></dt>
            <dd>
                <input type="text" name="doctor" id="doctor" size="32" maxlength="50" value="<?php echo
        !empty($patient['doctorName']) && !empty($patient['doctorSurname']) ? $patient['doctorName'] .
            " " . $patient['doctorSurname'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" />
            </dd>
        </dl>
        <dl>
        	<dt><label for="insuranceType">Είδος Ασφάλισης:</label></dt>
            <dd>
                <input type="text" name="insuranceType" id="insuranceType" size="32" maxlength="50" value="<?php echo
        !empty($patient['insuranceType']) ? $patient['insuranceType'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" />
            </dd>
        </dl>
        <dl>
        	<dt><label for="Insurance_Company">Ίδρυμα Ασφάλισης:</label></dt>
            <dd><input type="text" name="Insurance_Company" id="Insurance_Company" size="32" maxlength="50" value="<?php echo
        !empty($patient['insuranceCompany']) ? $patient['insuranceCompany'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" /></dd>
        </dl>
        <!--<dl>
        	<dt><label for="id_lang">Language:</label></dt>
            <dd>
                <input type="text" name="id_lang" id="id_lang" size="32" maxlength="50" value="<?php //echo
//!empty($patient['id_lang']) ? $patient['id_lang'] : "No results"; ?>" disabled="disabled" />
            </dd>
        </dl>-->
        <dl>
        	<dt><label for="urgency">Επείγον:</label></dt>
            <dd>
                <input type="text" name="urgency" id="urgency" size="32" maxlength="50" value="<?php echo
       !empty($patient['urgency']) ? $patient['urgency'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" />
            </dd>
        </dl>
        <dl>
        	<dt><label for="Charges">Χρέωση:</label></dt>
            <dd><input type="text" name="Charges" id="Charges" size="32" maxlength="50" value="<?php echo
        !empty($patient['charges']) ? $patient['charges'] : "Δεν βρέθηκαν αποτελέσματα"; ?>" disabled="disabled" /></dd>
        </dl>
    </fieldset>
    <fieldset>
    	<legend>Σχόλια</legend>
        <dl>
        	<dt><label for="diagnosis">Διάγνωση:</label></dt>
            <dd><textarea name="diagnosis" id="diagnosis" cols="60" rows="5" disabled><?php echo
        !empty($patient['diagnosis']) ? $patient['diagnosis'] : "Δεν βρέθηκαν αποτελέσματα"; ?></textarea></dd>
        </dl>
    </fieldset>
    <fieldset class="action">
     <input name="enableFields" type="image" class="send" id="enableFields" src="images/edit.jpg" />
     <input type="image" name="submit" id="submit" src="images/submit.jpg" class="send" align="right" value="Update Patient File" />
    </fieldset>
</form>
</div>
<?php
    }
    catch (PDOException $e) {
        DB::Close();
        echo "<font color='#FF0000'><b> Δεν βρέθηκαν αποτελέσματα. Καλυτέρη τύχη την επόμενη φορά! </b></font>";
    }
endif;
?>