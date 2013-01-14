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
            echo "<font color='red'>Sorry! Due to an error we can't process your transaction</style>";
        }
        elseif($this->isEmpty($this->id_field))
        {
            echo "<font color='red'>Sorry. Empty field entered.</font>";
        }
        elseif($this->isEmpty($this->id_value))
        {
            echo "<font color='red'>Sorry. Empty value entered.</font>";
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
                echo "<font color='red'>Hack Attempt</font>";
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
            echo "<font color='red'>Hack Attempt</font>";
            trigger_error($e->getMessage, E_USER_ERROR);
         }
    }
    
    public function recreateTable()
    {         
          switch($this->table_delete)
          {
            case "patient" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM patient where patient.id_lang = 1");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br /><table id=$this->table_delete$this->timestamp class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Delete Record</th>
                    <th class='sortcol' id='urgency'>Urgency</th>
                    <th class='sortcol' id='id_category'>Category</th>
                    <th class='sortfirstdesc sortcol sortasc' id='firstName'>First Name</th>
                    <th class='sortcol' id='lastName'>Last Name</th>
                    <th class='sortcol' id='registrationDate'>Registration Date</th>
                    <th class='sortcol' id='birthDate'>Birth Date</th>
                    <th class='sortcol' id='sex'>Sex</th>
                    <th class='sortcol' id='bloodGroup'>Blood Group</th>
                    <th class='sortcol' id='civilStatus'>Civil Satus</th>
                    <th class='sortcol' id='address'>Address</th>
                    <th class='sortcol' id='postCode'>Post Code</th>
                    <th class='sortcol' id='insuranceType'>Ιnsurance Type</th>
                    <th class='sortcol' id='insuranceCompany'>Ιnsurance Company</th>
                    <th class='sortcol' id='phone'>Phone</th>
                    <th class='sortcol' id='diagnosis'>Diagnosis</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Delete Record</td>
                    <td>Urgency</td>
                    <td>Category</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td>Registration Date</td>
                    <td>Birth Date</td>
                    <td>Sex</td>
                    <td>Blood Group</td>
                    <td>Civil Status</td>
                    <td>Address</td>
                    <td>Post Code</td>
                    <td>Insurance Type</td>
                    <td>Insurance Company</td>
                    <td align='center'>Phone</td>
                    <td>Diagnosis</td>
                    </tr>
				</tfoot>
                
				<tbody id='patient_body'>";
                
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM patient LEFT OUTER JOIN category on patient.id_category = category.id_category where patient.id_lang = 1 order by id_patient asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                  $html .=  "<tr class='rowodd' id='$row[id_patient]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='../images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_patient]); return false'>delete</a>
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
                $html .= "<tr><td colspan='4' class='nocol'>All Patients Deleted</td></tr>";
              }
              
            $html .= "</tbody>
			</table><br />";
            DB::Close();
            break;
            
            case "doctor" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM doctor where doctor.id_lang = 1");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br />
           <table id='$this->table_delete$this->timestamp' class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Delete Record</th>
                    <th class='sortcol' id='id_department'>Department</th>
                    <th class='sortfirstdesc sortcol sortasc' id='doctorName'>First Name</th>
                    <th class='sortcol' id='doctorSurname'>Last Name</th>
                    <th class='sortcol' id='specialization'>Specialization</th>
                    <th class='sortcol' id='employmentDate'>Employment Date</th>
                    <th class='sortcol' id='releaseDate'>Release Date</th>
                    <th class='sortcol' id='salary'>Salary</th>
                    <th class='sortcol' id='sex'>Sex</th>
                    <th class='sortcol' id='civilStatus'>Civil Satus</th>
					<th class='sortcol' id='phone'>Email</th>
                    <th class='sortcol' id='phone'>Phone</th>
                    </tr>
			    </thead>
				<tfoot>
					<tr>
                    <td>Delete Record</td>
                    <td>Department</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td>Specialization</td>
                    <td>Employment Date</td>
                    <td>Release Date</td>
                    <td>Salary</td>
                    <td>Sex</td>
                    <td>Civil Status</td>
                    <td align='center'>Email</td>
                    <td align='center'>Phone</td>
                    </tr>
				</tfoot>
                
				<tbody id='doctor_body'>";
                  
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query('SELECT * FROM doctor LEFT OUTER JOIN department on doctor.id_doctor = department.id_department where doctor.id_lang = 1 order by id_doctor asc '.$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                  
                  $html .= "<tr class='rowodd' id='$row[id_doctor]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='../images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_doctor]); return false'>delete</a>
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
                $html .= "<tr><td colspan='4' class='nocol'>All Doctors Deleted</td></tr>";
              }

             $html .="</tbody>
			</table><br />";
            DB::Close();
            break;
            
            case "pvaccination" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM pvaccination where pvaccination.id_lang = 1");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br />
            <table id='$this->table_delete$this->timestamp' class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Delete Record</th>
                    <th class='sortfirstdesc sortcol sortasc' id='id_patient'>Patient Name</th>
                    <th class='sortcol' id='vaccinationDate'>Date</th>
                    <th class='sortcol' id='id_doctor'>Doctor Name</th>
                    <th class='sortcol' id='vaccine'>Vaccine</th>
                    </tr>
			    </thead>
				<tfoot>
					<tr>
                    <td>Delete Record</td>
                    <td>Patient Name</td>
                    <td>Date</td>
                    <td>Doctor Name</td>
                    <td>Vaccine</td>
                    </tr>
				</tfoot>
                
				<tbody id='pvaccination_body'>";
                 
                 if($total_count[0] > 0) {
                 $stmt = DB::getInstance()->query("SELECT * FROM pvaccination LEFT OUTER JOIN patient on pvaccination.id_patient = patient.id_patient LEFT OUTER JOIN doctor on pvaccination.id_doctor = doctor.id_doctor where pvaccination.id_lang = 1 order by id_pvaccination asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  
                  $html .= " <tr class='rowodd' id='$row[id_pvaccination]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_pvaccination]) return false'>delete</a>
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
                    $html .= "<tr><td colspan='4' class='nocol'>All Patient Vaccination Deleted</td></tr>";
                }
                
              $html .="</tbody>
			 </table><br />";
             DB::Close();
            break;
            
            case "plabtest" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM plabtest where plabtest.id_lang = 1");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br />
            <table id='$this->table_delete$this->timestamp' class='sortable resizable editable'>
                <thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Delete Record</th>
                    <th class='sortfirstdesc sortcol sortasc' id='id_patient'>Patient Name</th>
                    <th class='sortcol' id='testDate'>Test Date</th>
                    <th class='sortcol' id='id_doctor'>Doctor Name</th>
                    <th class='sortcol' id='testType'>Test Type</th>
                    <th class='sortcol' id='testCost'>Test Cost</th>
                    <th class='sortcol' id='result'>Result</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Delete Record</td>
                    <td>Patient Name</td>
                    <td>Test Date</td>
                    <td>Doctor Name</td>
                    <td>Test Type</td>
                    <td>Test Cost</td>
                    <td>Result</td>
                    </tr>
				</tfoot>
                
				<tbody id='plabtest_body'>";
                 
                 if($total_count[0] > 0) {
                 $stmt = DB::getInstance()->query("SELECT * FROM plabtest LEFT OUTER JOIN patient on plabtest.id_patient = patient.id_patient LEFT OUTER JOIN doctor on plabtest.id_doctor = doctor.id_doctor where plabtest.id_lang = 1 order by id_plabtest asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  
                  $html .= " <tr class='rowodd' id='$row[id_plabtest]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_plabtest]) return false'>delete</a>
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
                    $html .= "<tr><td colspan='4' class='nocol'>All Patient Lab Test Deleted</td></tr>";
                }
                
              $html .="</tbody>
			 </table><br />";
             DB::Close();
            break;
            
            case "prescription" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM prescription where prescription.id_lang = 1");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br />
            <table id='$this->table_delete$this->timestamp' class='sortable resizable editable'>
                <thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Delete Record</th>
                    <th class='sortfirstdesc sortcol sortasc' id='id_patient'>Patient Name</th>
                    <th class='sortcol' id='prescriptionDate'>Prescription Date</th>
                    <th class='sortcol' id='id_doctor'>Doctor Name</th>
                    <th class='sortcol' id='product'>Product</th>
                    <th class='sortcol' id='prescription'>Prescription</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Delete Record</td>
                    <td>Patient Name</td>
                    <td>Prescription Date</td>
                    <td>Doctor Name</td>
                    <td>Product</td>
                    <td>Prescription</td>
                    </tr>
				</tfoot>
                
				<tbody id='prescription_body'>";
                 
                 if($total_count[0] > 0) {
                 $stmt = DB::getInstance()->query("SELECT * FROM prescription LEFT OUTER JOIN patient on prescription.id_patient = patient.id_patient LEFT OUTER JOIN doctor on prescription.id_doctor = doctor.id_doctor where prescription.id_lang = 1 order by id_prescription asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  
                  $html .= " <tr class='rowodd' id='$row[id_prescription]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_prescription]) return false'>delete</a>
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
                    $html .= "<tr><td colspan='4' class='nocol'>All Patient Prescriptions Deleted</td></tr>";
                }
                
              $html .="</tbody>
			 </table><br />";
             DB::Close();
            break;
            
            case "pharmacy_pur" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM pharmacy_pur where pharmacy_pur.id_lang = 1");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br /><table id=$this->table_delete$this->timestamp class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Delete Record</th>
                    <th class='sortfirstdesc sortcol sortasc' id='pharmacyProduct'>Product</th>
                    <th class='sortcol' id='supplier'>Supplier</th>
                    <th class='sortcol' id='purchaseDate'>Purchase Date</th>
                    <th class='sortcol' id='id_doctor'>Doctor Name</th>
                    <th class='sortcol' id='quantity'>Quantity</th>
                    <th class='sortcol' id='unitPrice'>Price</th>
                    <th class='sortcol' id='status'>Status</th>
                    <th class='sortcol' id='discount'>Discount</th>
                    <th class='sortcol' id='tax'>Tax</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Delete Record</td>
                    <td>Product</td>
                    <td>Supplier</td>
                    <td>Purchase Date</td>
                    <td>Doctor Name</td>
                    <td>Quantity</td>
                    <td>Price</td>
                    <td>Status</td>
                    <td>Discount</td>
                    <td>Tax</td>
                    </tr>
				</tfoot>
                
				<tbody id='pharmacy_pur_body'>";
                
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM pharmacy_pur LEFT OUTER JOIN doctor on pharmacy_pur.id_doctor = doctor.id_doctor where pharmacy_pur.id_lang = 1 order by id_pharmacy_pur asc ".$pagination->limit);                                   
                  $i=0;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                  $html .=  "<tr class='rowodd' id='$row[id_pharmacy_pur]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='../images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_pharmacy_pur]); return false'>delete</a>
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
                $html .= "<tr><td colspan='4' class='nocol'><font color='red'>All Pharmacy Purchases Deleted</font></td></tr>";
              }
              
            $html .= "</tbody>
			</table><br />";
            DB::Close();
            break;
            
            case "employee" :
            $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM employee");
            $pagination = new Pagination();
            $pagination->total_items = $total_count[0];
            $pagination->mid_range = 3;
            $pagination->paginate();
            $html = "<br /><br /><table id=$this->table_delete$this->timestamp class='sortable resizable editable'>
				<thead>
					<tr>
                    <th class='nosort nocol noedit' id='action'>Delete Record</th>
                    <th class='sortfirstdesc sortcol sortasc' id='firstname'>First Name</th>
                    <th class='sortcol' id='lastname'>Last Name</th>
                    <th class='sortcol' id='email'>Email</th>
                    <th class='sortcol' id='passwd'>Password</th>
                    <th class='sortcol' id='id_profile'>Profile</th>
                    <th class='sortcol' id='active'>Status</th>
                    </tr>
                </thead>
				<tfoot>
					<tr>
                    <td>Delete Record</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td>Email</td>
                    <td>Password</td>
                    <td>Profile</td>
                    <td>Status</td>
                    </tr>
				</tfoot>
                
				<tbody id='employee_body'>";
                
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM employee LEFT OUTER JOIN profile on employee.id_profile = profile.id_profile order by id_employee asc ".$pagination->limit);                                   
                  $i=1;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                  $html .=  "<tr class='rowodd' id='$row[id_employee]' onmouseover='hilite(this)' onmouseout='lowlite(this)'>
                    <td>
                    <img src='images/delete.gif' />
                    <a class='delete' href='#' onclick='deleteRecord($row[id_employee]); return false'>delete</a>
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
                $html .= "<tr><td colspan='4' class='nocol'><font color='red'>All Employees Deleted</font></td></tr>";
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
        echo "<font color='red'>Sorry. Invalid Entry. Use letters and numbers 50 characters long.</font>";
        else
        return true;
    }
    
    public function validatePostCode()
    {
        if(!Validate::isPostCode($this->id_value))
        echo "<font color='red'>Sorry. Invalid Post Code. Must be 5-digit number.</font>";
        else
        $this->directUpdate();
    }
    
    public function validateDate()
    {
        if(!Validate::isDate($this->id_value)) 
        echo "<font color='red'>Sorry. Invalid Date. Please consider this form. YYYY-MM-DD</font>";
        else
        $this->directUpdate();
    }
    
    public function validatePhone()
    {
        if(!Validate::isPhoneNumber($this->id_value)) 
        echo "<font color='red'>The phone number must be in the following form. +2310.(XXX)-(XXX).</font>";
        else
        $this->directUpdate();
    }
    
    public function validatePrice()
    {
        if(!Validate::isPrice($this->id_value))
        echo "<font color='red'>Please insert a numeric value. For decimals use dots instead of commas</font>";
        else
        $this->directUpdate();
    }
    
     public function validateInteger()
    {
        if(!Validate::isInt($this->id_value))
        echo "<font color='red'>Please insert an integer numeric value</font>";
        else
        $this->directUpdate();
    }
    
    public function validateEmail()
    {
        if(!Validate::isEmail($this->id_value))
        echo "<font color='red'>This is not a valid e-mail address</font>";
        else
        $this->directUpdate();
    }
    
    public function validatePassword()
    {
        if(!Validate::isPasswdAdmin($this->id_value))
        echo "<font color='red'>Passwd must be at least 8 characters long</font>";
        else
        {
            $this->id_value = $this->encrypt($this->id_value);
            $this->directUpdate();
        }
        
    }
}

$transaction = new Transaction($id, $field, $id_table, $value, $id_delete, $table_delete, $timestamp);