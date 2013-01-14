<?php
header('Content-Type: text/html; charset=UTF-8');
require_once('functions.php');
__autoload("pdo");
__autoload("validate");
__autoload("pagination");

$id = (isset($_POST['id']) && !empty($_POST['id'])) ? $_POST['id'] : "";
$field = (isset($_POST['field']) && !empty($_POST['field'])) ? $_POST['field'] : "";
$id_table = (isset($_POST['id_table']) && !empty($_POST['id_table'])) ? $_POST['id_table'] : "";
$value = (isset($_POST['value']) && !empty($_POST['value'])) ? $_POST['value'] : "";
$id_delete = (isset($_GET['id_delete']) && !empty($_GET['id_delete'])) ? $_GET['id_delete'] : "";
$table_delete = (isset($_GET['table_delete']) && !empty($_GET['table_delete'])) ? $_GET['table_delete'] : "";
$timestamp = (isset($_GET['timestamp']) && !empty($_GET['timestamp'])) ? $_GET['timestamp'] : "";

class Transaction extends Tools
{
    private $id_key;
    private $id_field;
    private $id_table;
    private $id_value;
    private $id_delete;
    private $table_delete;
    private $timestamp;
    public $url;
    private $fields = array();
    private $fields_string;
    private $result;

    public function __construct($id, $field, $table, $value, $id_delete, $table_delete, $timestamp)
    {
        
        $this->safePostVars();

        $this->id_key = strip_tags(trim($id));
        $this->id_field = strip_tags(trim($field));
        $this->id_value = strip_tags(trim($value));
        $this->id_table = strip_tags(trim($table));
        $this->id_delete = strip_tags(trim($id_delete));
        $this->table_delete = strip_tags(trim($table_delete));
        $this->timestamp = strip_tags(trim($timestamp));
        
        if(isset($this->id_key) && !empty($this->id_key))
        $this->validate();
        
        if(isset($this->id_delete) && !empty($this->id_delete) && 
           isset($this->table_delete) && !empty($this->table_delete))
            $this->delete();
           
    }
    
    public function validate()
    {
        if(!Validate::isNullOrUnsignedId($this->id_key))
        {
            echo "<font color='red'>Συγνώμη! Λόγω σφάλματος δεν μπορέσαμε να ολοκληρώσουμε την εγγραφή σας</style>";
        }
        elseif($this->isEmpty($this->id_field))
        {
            echo "<font color='red'>Συγνώμη. Εισαγωγή άδειου πεδίου.</font>";
        }
        elseif($this->isEmpty($this->id_value))
        {
            echo "<font color='red'>Συγνώμη. Εισαγωγή κενής τιμής.</font>";
        }
        elseif(isset($this->id_value) && isset($this->id_field))
        {
            switch($this->id_field)
            {
                case "registrationDate" :
                case "birthDate" :
                case "employmentDate" :
                case "releaseDate" :
                case "vaccinationDate" :
                case "testDate" :
                case "prescriptionDate" :
                case "purchaseDate" :
                $this->validateDate();
                break;
                
                case "phone" :
                $this->validatePhone();
                break;
                
                case "postCode" :
                $this->validatePostCode();
                break;
                
                case "salary" :
                case "charges" :
                case "unitPrice" :
                case "discount" :
                case "tax" :
                case "testCost" :
                $this->validatePrice();
                break;
                
                case "quantity" :
                $this->validateInteger();
                break;
                
                case "email" :
                $this->validateEmail();
                break;
                
                case "passwd" :
                $this->validatePassword();
                break;
                
                case "action" :
                $this->delete();
                //$this->postBack();
                break;
                
                default:
                $this->update();
                //$this->postBack();
                break;
                
            }
        }
    }
    
    public function update()
    {
        if($this->validateVarchar($this->id_value) === true)
        {
             try
             {
                if(!empty($this->id_table) && strlen($this->id_table) >= 13)
                {
                    $this->id_table = substr($this->id_table, 0, -13);
                    $rowsAffected = DB::Execute("UPDATE `hms_support`.`$this->id_table` SET `$this->id_field` = '$this->id_value' WHERE `$this->id_table`.`id_$this->id_table` =$this->id_key");
                    
                    if($rowsAffected > 0)
                    echo $this->id_value;
                }
                else
                {
                    //echo "UPDATE `hms_support`.`$this->id_table` SET `$this->id_field` = \'$this->id_value\' WHERE `$this->id_table`.`id_$this->id_table` =$this->id_key";exit;
                    $rowsAffected = DB::Execute("UPDATE `hms_support`.`$this->id_table` SET `$this->id_field` = '$this->id_value' WHERE `$this->id_table`.`id_$this->id_table` =$this->id_key");
                    
                    if($rowsAffected > 0)
                    echo $this->id_value;
                }
                
                
             }
             catch(PDOException $e)
             {
                DB::Close();
                echo "<font color='red'>Προσπάθεια δόλου</font>";
                //trigger_error($e->getMessage, E_USER_ERROR);
             }
        }
    }
    
    public function postBack()
    {
        $this->url = 'http://localhost/cleanblue/includes/mainbar.php';
        
        $this->fields = array( 

            'id'=>urlencode($this->id_key),
            'field'=>urlencode($this->id_field),
            'value'=>urlencode($this->id_value),
            'reload'=>urlencode("reloadPage") 
        );
        
        //url-ify the data for the POST
        foreach($this->fields as $key=>$value) { $this->fields_string .= $key.'='.$value.'&'; }
        rtrim($this->fields_string,'&');
        //implode('&', $fields);
        
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$this->url);
        curl_setopt($ch,CURLOPT_POST,count($this->fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$this->fields_string);
        
        //execute post
        $this->result = curl_exec($ch);
        
        //close connection
        curl_close($ch);
    }
    
    public function directUpdate()
    {
         try
         {
                if(!empty($this->id_table) && strlen($this->id_table) >= 13)
                {
                    $this->id_table = substr($this->id_table, 0, -13);
                    
                    $rowsAffected = DB::Execute("UPDATE `hms_support`.`$this->id_table` SET `$this->id_field` = '$this->id_value' 
                                        WHERE `$this->id_table`.`id_$this->id_table` =$this->id_key");
                    
                    if($rowsAffected > 0)
                    echo $this->id_value;
                }
                else
                {
                    $rowsAffected = DB::Execute("UPDATE `hms_support`.`$this->id_table` SET `$this->id_field` = '$this->id_value' 
                                        WHERE `$this->id_table`.`id_$this->id_table` =$this->id_key");
                    if($rowsAffected > 0)
                    echo $this->id_value;
                }
            
            
         }
         catch(PDOException $e)
         {
            DB::Close();
            //echo "<font color='red'>Hack Attempt</font>";
            trigger_error($e->getMessage, E_USER_ERROR);
         }
    }
    
    public function delete()
    {
         try
         {
            $rowsAffected = DB::Execute("DELETE FROM `hms_support`.`$this->table_delete` WHERE `$this->table_delete`.`id_$this->table_delete` =$this->id_delete");
            //echo $this->id_value;
            echo $this->recreateTable();
         }
         catch(PDOException $e)
         {
            DB::Close();
            echo "<font color='red'>Προσπάθεια δόλου</font>";
            trigger_error($e->getMessage, E_USER_ERROR);
         }
    }
    
    public function recreateTable()
    {         
          switch($this->table_delete)
          {
            case "patient" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM patient where patient.id_lang = 2");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br /><table id=$this->table_delete$this->timestamp class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Διαγραφή Εγγραφής</th>
                    <th class='sortcol' id='urgency'>Επείγον</th>
                    <th class='sortcol' id='id_category'>Κατηγορία</th>
                    <th class='sortfirstdesc sortcol sortasc' id='firstName'>Όνομα</th>
                    <th class='sortcol' id='lastName'>Επώνυμο</th>
                    <th class='sortcol' id='registrationDate'>Ημερ/νία Εγγραφής</th>
                    <th class='sortcol' id='birthDate'>Ημερ/νία Γέννησης</th>
                    <th class='sortcol' id='sex'>Φύλο</th>
                    <th class='sortcol' id='bloodGroup'>Ομάδα Αίματος</th>
                    <th class='sortcol' id='civilStatus'>Οικογ. Κατάσταση</th>
                    <th class='sortcol' id='address'>Διεύθυνση</th>
                    <th class='sortcol' id='postCode'>Τ.Κ.</th>
                    <th class='sortcol' id='insuranceType'>Είδος Ασφάλισης</th>
                    <th class='sortcol' id='insuranceCompany'>Ασφαλιστικός Φορέας</th>
                    <th class='sortcol' id='phone'>Τηλέφωνο</th>
                    <th class='sortcol' id='diagnosis'>Διάγνωση</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Διαγραφή Εγγραφής</td>
                    <td>Επείγον</td>
                    <td>Κατηγορία</td>
                    <td>Όνομα</td>
                    <td>Επώνυμο</td>
                    <td>Ημερ/νία Εγγραφής</td>
                    <td>Ημερ/νία Γέννησης</td>
                    <td>Φύλο</td>
                    <td>Ομάδα Αίματος</td>
                    <td>Οικογ. Κατάσταση</td>
                    <td>Διεύθυνση</td>
                    <td>Τ.Κ.</td>
                    <td>Είδος Ασφάλισης</td>
                    <td>Ασφαλιστικός Φορέας</td>
                    <td align='center'>Τηλέφωνο</td>
                    <td>Διάγνωση</td>
                    </tr>
				</tfoot>
                
				<tbody id='patient_body'>";
                
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM patient LEFT OUTER JOIN category on patient.id_category = category.id_category where patient.id_lang = 2 order by id_patient asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                  $html .=  "<tr class='rowodd' id='$row[id_patient]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='../images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_patient]); return false'>Διαγραφή</a>
                    </td>
                    <td><div class='urg$row[urgency]'>$row[urgency] </div></td>
                    <td>$row[category_name]</td>
                    <td>$row[firstName]</td>
                    <td>$row[lastName] </td>
                    <td>$row[registrationDate] </td>
                    <td>$row[birthDate] </td>
                    <td>$row[sex] </td>
                    <td>$row[bloodGroup] </td>
                    <td>$row[civilStatus]</td>
                    <td>$row[address] </td>
                    <td>$row[postCode] </td>
                    <td>$row[insuranceType] </td>
                    <td>$row[insuranceCompany]</td>
                    <td>$row[phone] </td>
                    <td>$row[diagnosis]</td>
                    </tr>";  
                  $i++;
                  }
              }
              else
              {
                $html .= "<tr><td colspan='4' class='nocol'>Όλοι οι ασθενείς διεγράφησαν</td></tr>";
              }
              
            $html .= "</tbody>
			</table><br />";
            DB::Close();
            break;
            
            case "doctor" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM doctor where doctor.id_lang = 2");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br />
           <table id='$this->table_delete$this->timestamp' class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Διαγραφή Εγγραφής</th>
                    <th class='sortcol' id='id_department'>Τμήμα</th>
                    <th class='sortfirstdesc sortcol sortasc' id='doctorName'>Όνομα</th>
                    <th class='sortcol' id='doctorSurname'>Επώνυμο</th>
                    <th class='sortcol' id='specialization'>Ειδικότητα</th>
                    <th class='sortcol' id='employmentDate'>Ημερ/νία Πρόσληψης</th>
                    <th class='sortcol' id='releaseDate'>Ημερ/νία Απόλυσης</th>
                    <th class='sortcol' id='salary'>Μισθός</th>
                    <th class='sortcol' id='sex'>Φύλο</th>
                    <th class='sortcol' id='civilStatus'>Οικογ. Κατάσταση</th>
					<th class='sortcol' id='phone'>Email</th>
                    <th class='sortcol' id='phone'>Τηλέφωνο</th>
                    </tr>
			    </thead>
				<tfoot>
					<tr>
                    <td>Delete Record</td>
                    <td>Department</td>
                    <td>Όνομα</td>
                    <td>Επώνυμο</td>
                    <td>Ειδικότητα</td>
                    <td>Ημερ/νία Πρόσληψης</td>
                    <td>Ημερ/νία Απόλυσης</td>
                    <td>Μισθός</td>
                    <td>Φύλο</td>
                    <td>Οικογ. Κατάσταση</td>
					<td align='center'>Email</td>
                    <td align='center'>Τηλέφωνο</td>
                    </tr>
				</tfoot>
                
				<tbody id='doctor_body'>";
                  
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query('SELECT * FROM doctor LEFT OUTER JOIN department on doctor.id_doctor = department.id_department where doctor.id_lang = 2 order by id_doctor asc '.$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                  
                  $html .= "<tr class='rowodd' id='$row[id_doctor]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='../images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_doctor]); return false'>Διαγραφή</a>
                    </td>
                    <td>$row[department_name]</td>
                    <td>$row[doctorName]</td>
                    <td>$row[doctorSurname] </td>
                    <td>$row[specialization] </td>
                    <td>$row[employmentDate] </td>
                    <td>$row[releaseDate] </td>
                    <td>$row[salary] </td>
                    <td>$row[sex]</td>
                    <td>$row[civilStatus]</td>
					<td>$row[email]</td>
                    <td>$row[phone]</td>
                    </tr>";
                    
                  $i++;
                  }
              }
              else
              {
                $html .= "<tr><td colspan='4' class='nocol'>Όλοι οι ιατροί διεγράφησαν</td></tr>";
              }

             $html .="</tbody>
			</table><br />";
            DB::Close();
            break;
            
            case "pvaccination" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM pvaccination where pvaccination.id_lang = 2");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br />
            <table id='$this->table_delete$this->timestamp' class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Διαγραφή Εγγραφής</th>
                    <th class='sortfirstdesc sortcol sortasc' id='id_patient'>Όνομα Ασθενή</th>
                    <th class='sortcol' id='vaccinationDate'>Ημερ/νία</th>
                    <th class='sortcol' id='id_doctor'>Όνομα Ιατρού</th>
                    <th class='sortcol' id='vaccine'>Εμβόλιο</th>
                    </tr>
			    </thead>
				<tfoot>
					<tr>
                    <td>Διαγραφή Εγγραφής</td>
                    <td>Όνομα Ασθενή</td>
                    <td>Ημερ/νία</td>
                    <td>Όνομα Ιατρού</td>
                    <td>Εμβόλιο</td>
                    </tr>
				</tfoot>
                
				<tbody id='pvaccination_body'>";
                 
                 if($total_count[0] > 0) {
                 $stmt = DB::getInstance()->query("SELECT * FROM pvaccination LEFT OUTER JOIN patient on pvaccination.id_patient = patient.id_patient LEFT OUTER JOIN doctor on pvaccination.id_doctor = doctor.id_doctor where pvaccination.id_lang = 2 order by id_pvaccination asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  
                  $html .= " <tr class='rowodd' id='$row[id_pvaccination]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_pvaccination]) return false'>Διαγραφή</a>
                    </td>
                    <td>  $row[firstName] $row[lastName] </td>
                    <td>  $row[vaccinationDate] </td>
                    <td>  $row[doctorName] $row[doctorSurname] </td>
                    <td>  $row[vaccine] </td>
                    </tr>";
                    
                    $i++;
                    } 
                }
                else
                {
                    $html .= "<tr><td colspan='4' class='nocol'>Όλοι οι εμβολιασμοί ασθενών διεγράφησαν</td></tr>";
                }
                
              $html .="</tbody>
			 </table><br />";
             DB::Close();
            break;
            
            case "plabtest" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM plabtest where plabtest.id_lang = 2");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br />
            <table id='$this->table_delete$this->timestamp' class='sortable resizable editable'>
                <thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Διαγραφή Εγγραφής</th>
                    <th class='sortfirstdesc sortcol sortasc' id='id_patient'>Όνομα Ασθενή</th>
                    <th class='sortcol' id='testDate'>Ημερ/νία Εξέτασης</th>
                    <th class='sortcol' id='id_doctor'>Ιατρός</th>
                    <th class='sortcol' id='testType'>Είδος Εξέτασης</th>
                    <th class='sortcol' id='testCost'>Κόστος Εξέτασης</th>
                    <th class='sortcol' id='result'>Αποτελέσματα</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Διαγραφή Εγγραφής</td>
                    <td>Όνομα Ασθενή</td>
                    <td>Ημερ/νία Εξέτασης</td>
                    <td>Ιατρός</td>
                    <td>Είδος Εξέτασης</td>
                    <td>Κόστος Εξέτασης</td>
                    <td>Αποτελέσματα</td>
                    </tr>
				</tfoot>
                
				<tbody id='plabtest_body'>";
                 
                 if($total_count[0] > 0) {
                 $stmt = DB::getInstance()->query("SELECT * FROM plabtest LEFT OUTER JOIN patient on plabtest.id_patient = patient.id_patient LEFT OUTER JOIN doctor on plabtest.id_doctor = doctor.id_doctor where plabtest.id_lang = 2 order by id_plabtest asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  
                  $html .= " <tr class='rowodd' id='$row[id_plabtest]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_plabtest]) return false'>Διαγραφή</a>
                    </td>
                    <td>$row[firstName] $row[lastName]</td>
                    <td>$row[testDate]</td>
                    <td>$row[doctorName] $row[doctorSurname]</td>
                    <td>$row[testType]</td>
                    <td>$row[testCost]</td>
                    <td>$row[result]</td>
                    </tr>";
                    
                    $i++;
                    } 
                }
                else
                {
                    $html .= "<tr><td colspan='4' class='nocol'>Όλες οι εργαστηριακές εξέτασεις διεγράφησαν</td></tr>";
                }
                
              $html .="</tbody>
			 </table><br />";
             DB::Close();
            break;
            
            case "prescription" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM prescription where prescription.id_lang = 2");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br />
            <table id='$this->table_delete$this->timestamp' class='sortable resizable editable'>
                <thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Διαγραφή Εγγραφής</th>
                    <th class='sortfirstdesc sortcol sortasc' id='id_patient'>Όνομα Ασθενή</th>
                    <th class='sortcol' id='prescriptionDate'>Ημερ/νία Συντα/φησης</th>
                    <th class='sortcol' id='id_doctor'>Ιατρός</th>
                    <th class='sortcol' id='product'>Φάρμακο</th>
                    <th class='sortcol' id='prescription'>Συντα/φηση</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Διαγραφή Εγγραφής</td>
                    <td>Όνομα Ασθενή</td>
                    <td>Ημερ/νία Συντα/φησης</td>
                    <td>Ιατρός</td>
                    <td>Φάρμακο</td>
                    <td>Συντα/φηση</td>
                    </tr>
				</tfoot>
                
				<tbody id='prescription_body'>";
                 
                 if($total_count[0] > 0) {
                 $stmt = DB::getInstance()->query("SELECT * FROM prescription LEFT OUTER JOIN patient on prescription.id_patient = patient.id_patient LEFT OUTER JOIN doctor on prescription.id_doctor = doctor.id_doctor where prescription.id_lang = 2 order by id_prescription asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  
                  $html .= " <tr class='rowodd' id='$row[id_prescription]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_prescription]) return false'>Διαγραφή</a>
                    </td>
                    <td>$row[firstName] $row[lastName]</td>
                    <td>$row[prescriptionDate]</td>
                    <td>$row[doctorName] $row[doctorSurname]</td>
                    <td>$row[product]</td>
                    <td>$row[prescription]</td>
                    </tr>";
                    
                    $i++;
                    } 
                }
                else
                {
                    $html .= "<tr><td colspan='4' class='nocol'>Όλες οι συνταγογραφήσεις ασθενών διεγράφησαν</td></tr>";
                }
                
              $html .="</tbody>
			 </table><br />";
             DB::Close();
            break;
            
            case "pharmacy_pur" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM pharmacy_pur where pharmacy_pur.id_lang = 2");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br /><table id=$this->table_delete$this->timestamp class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Διαγραφή Εγγραφής</th>
                    <th class='sortfirstdesc sortcol sortasc' id='pharmacyProduct'>Προϊόν</th>
                    <th class='sortcol' id='supplier'>Προμηθευτής</th>
                    <th class='sortcol' id='purchaseDate'>Ημερ/νία Αγοράς</th>
                    <th class='sortcol' id='id_doctor'>Ιατρός</th>
                    <th class='sortcol' id='quantity'>Ποσότητα</th>
                    <th class='sortcol' id='unitPrice'>Τιμή</th>
                    <th class='sortcol' id='status'>Κατάσταση</th>
                    <th class='sortcol' id='discount'>Έκπτωση%</th>
                    <th class='sortcol' id='tax'>Φόρος%</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Διαγραφή Εγγραφής</td>
                    <td>Προϊόν</td>
                    <td>Προμηθευτής</td>
                    <td>Ημερ/νία Αγοράς</td>
                    <td>Ιατρός</td>
                    <td>Ποσότητα</td>
                    <td>Τιμή</td>
                    <td>Κατάσταση</td>
                    <td>Έκπτωση%</td>
                    <td>Φόρος%</td>
                    </tr>
				</tfoot>
                
				<tbody id='pharmacy_pur_body'>";
                
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM pharmacy_pur LEFT OUTER JOIN doctor on pharmacy_pur.id_doctor = doctor.id_doctor where pharmacy_pur.id_lang = 2 order by id_pharmacy_pur asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                  $html .=  "<tr class='rowodd' id='$row[id_pharmacy_pur]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='../images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_pharmacy_pur]); return false'>Διαγραφή</a>
                    </td>
                    <td>$row[pharmacyProduct]</td>
                    <td>$row[supplier]</td>
                    <td>$row[purchaseDate] </td>
                    <td>$row[doctorName] $row[doctorSurname]</td>
                    <td>$row[quantity] </td>
                    <td>$row[unitPrice] </td>
                    <td>$row[status] </td>
                    <td>$row[discount]</td>
                    <td>$row[tax] </td>
                    </tr>";  
                  $i++;
                  }
              }
              else
              {
                $html .= "<tr><td colspan='4' class='nocol'><font color='red'>Όλες οι αγορές φαρμάκων διεγράφησαν</font></td></tr>";
              }
              
            $html .= "</tbody>
			</table><br />";
            DB::Close();
            break;
            
            case "employee" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM employee where employeee.id_lang = 2");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br /><table id=$this->table_delete$this->timestamp class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Διαγραφή Εγγραφής</th>
                    <th class='sortfirstdesc sortcol sortasc' id='firstname'>Όνομα</th>
                    <th class='sortcol' id='lastname'>Επώνυμο</th>
                    <th class='sortcol' id='email'>Email</th>
                    <th class='sortcol' id='passwd'>Κωδικός</th>
                    <th class='sortcol' id='id_profile'>Προφίλ</th>
                    <th class='sortcol' id='active'>Κατάσταση</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Διαγραφή Εγγραφής</td>
                    <td>Όνομα</td>
                    <td>Επώνυμο</td>
                    <td>Email</td>
                    <td>Κωδικός</td>
                    <td>Προφίλ</td>
                    <td>Κατάσταση</td>
                    </tr>
				</tfoot>
                
				<tbody id='employee_body'>";
                
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM employee LEFT OUTER JOIN profile on employee.id_profile = profile.id_profile where employee.id_lang = 2 order by id_employee asc ".$pagination->limit);                                   
                  $i=1;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                  $html .=  "<tr class='rowodd' id='$row[id_employee]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_employee]); return false'>Διαγραφή</a>
                    </td>
                    <td>$row[firstname]</td>
                    <td>$row[lastname]</td>
                    <td>$row[email] </td>
                    <td>".substr($row[passwd], 0, 10)."</td>
                    <td>$row[name] </td>
                    <td>$row[active]</td>
                    </tr>";  
                  $i++;
                  }
              }
              else
              {
                $html .= "<tr><td colspan='4' class='nocol'><font color='red'>Όλοι οι χρήστες διεγράφησαν</font></td></tr>";
              }
              
            $html .= "</tbody>
			</table><br />";
            DB::Close();
            break;
                
            default:
            break;
            
          }
          return $html;
    }
    
    public function validateVarchar()
    {
        if(!Validate::isVarchar($this->id_value))
        echo "<font color='red'>Συγνώμη. Λαθασμένη εισαγωγή. Χρησιμοποιήστε γράμματα και αριθμούς μέχρι 50 χαρακτήρες.</font>";
        else
        return true;
    }
    
    public function validatePostCode()
    {
        if(!Validate::isPostCode($this->id_value))
        echo "<font color='red'>Συγνώμη. Λαθασμένος Τ.Κ. Πρέπει να είναι 5-ψήφιος αριθμός.</font>";
        else
        $this->directUpdate();
    }
    
    public function validateDate()
    {
        if(!Validate::isDate($this->id_value)) 
        echo "<font color='red'>Συγνώμη. Λαθασμένη Ημερ/νία. Παρακαλώ ακολουθείστε αυτή τη μορφή. YYYY-MM-DD</font>";
        else
        $this->directUpdate();
    }
    
    public function validatePhone()
    {
        if(!Validate::isPhoneNumber($this->id_value)) 
        echo "<font color='red'> Ο τηλεφωνικός αριθμός πρέπει να είναι στην ακόλουθη μορφή. +2310.(XXX)-(XXX).</font>";
        else
        $this->directUpdate();
    }
    
    public function validatePrice()
    {
        if(!Validate::isPrice($this->id_value))
        echo "<font color='red'>Παρακαλώ εισάγετε μια αριθμητική τιμή. Για δεκαδικούς αριθμούς χρησιμοποιήστε τελείες αντί για κόμμα</font>";
        else
        $this->directUpdate();
    }
    
     public function validateInteger()
    {
        if(!Validate::isInt($this->id_value))
        echo "<font color='red'>Παρακαλώ εισάγετε μια ακέραιη αριθμητική τιμή</font>";
        else
        $this->directUpdate();
    }
    
    public function validateEmail()
    {
        if(!Validate::isEmail($this->id_value))
        echo "<font color='red'>Αυτή δεν είναι έγκυρη διεύθυνση e-mail</font>";
        else
        $this->directUpdate();
    }
    
    public function validatePassword()
    {
        if(!Validate::isPasswdAdmin($this->id_value))
        echo "<font color='red'>Ο κωδικός πρέπει να είναι τουλάχιστον 8 χαρακτήρες</font>";
        else
        {
            $this->id_value = $this->encrypt($this->id_value);
            $this->directUpdate();
        }
        
    }
}

$transaction = new Transaction($id, $field, $id_table, $value, $id_delete, $table_delete, $timestamp);