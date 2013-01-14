<?php 
require_once('functions.php');
__autoload("pdo");
__autoload("pagination");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Doctors</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="../css/tablekit.css" rel="stylesheet" type="text/css" />
<link href="../css/paginate.css" rel="stylesheet" type="text/css" />

<!-- LIBS -->
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/tablekit.js"></script>	
<!-- ENDLIBS -->
<script>
function hilite(elem)
{
	elem.style.background = '#FFC';
}

function lowlite(elem)
{
	elem.style.background = '';
}
</script>
</head>

<body>
<div class="mainbar">
        <?php
          $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM doctor where doctor.id_lang = 1");   
          $pagination = new Pagination();
          $pagination->total_items = $total_count[0];
          $pagination->mid_range = 3;
          $pagination->paginate();
          echo "<br />Total Doctors : <b>$total_count[0]</b> <br /><br /><br />";
          echo $pagination->displayPages();
          echo "<span class=\"\">&nbsp;&nbsp;".$pagination->displayJumpMenu()."&nbsp;&nbsp;".
          $pagination->displayItemsPerPage()."</span>";
          ?>
        <div id="doctor_div" class="article">
          <p> </p>
          <br />
           <table id="doctor" class="sortable resizable editable">
				<thead>
					<tr>
                    <th class="nosort nocol noedit" id="action">Delete Record</th>
                    <th class="sortcol" id="id_department">Department</th>
                    <th class="sortfirstdesc sortcol sortasc" id="doctorName">First Name</th>
                    <th class="sortcol" id="doctorSurname">Last Name</th>
                    <th class="sortcol" id="specialization">Specialization</th>
                    <th class="sortcol" id="employmentDate">Employment Date</th>
                    <th class="sortcol" id="releaseDate">Release Date</th>
                    <th class="sortcol" id="salary">Salary</th>
                    <th class="sortcol" id="sex">Sex</th>
                    <th class="sortcol" id="civilStatus">Civil Satus</th>
                    <th class="sortcol" id="email">Email</th>
                    <th class="sortcol" id="phone">Phone</th>
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
                    <td align="center">Email</td>
                    <td align="center">Phone</td>
                    </tr>
				</tfoot>
                
				<tbody id="doctor_body">
                  
                  <?php
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM doctor LEFT OUTER JOIN department on doctor.id_department = department.id_department where doctor.id_lang = 1 order by id_doctor asc ".$pagination->limit);                                   
                  $i=1;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : 
                  ?>
                    <tr class="<?php echo ($i % 2 == 0) ? 'rowodd' : 'roweven'; ?>" id="<?php echo $row['id_doctor']; ?>" onmouseover="hilite(this);" onmouseout="lowlite(this);">
                    <td>
                    <img src="../images/delete.gif" />
                    <a class="delete" href="#" onclick="deleteRecord(<?php echo $row['id_doctor']; ?>); return false">delete</a>
                    </td>
                    <td><?php echo $row['department_name']; ?></td>
                    <td><?php echo $row['doctorName']; ?></td>
                    <td><?php echo $row['doctorSurname']; ?></td>
                    <td><?php echo $row['specialization']; ?></td>
                    <td><?php echo $row['employmentDate']; ?></td>
                    <td><?php echo $row['releaseDate']; ?></td>
                    <td><?php echo $row['salary']; ?></td>
                    <td><?php echo $row['sex']; ?></td>
                    <td><?php echo $row['civilStatus']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    </tr>
                  <?php  
                  $i++;
                  endwhile;
                  }
                  else
                  {
                    echo "<tr><td colspan='4' class='nocol'>
                    <font color='red'>All Doctors Deleted</font>
                    </td></tr>";
                  }
                  DB::Close();
                  ?>

               </tbody>
			</table><br />
			<div class="panel" id="doco-editable"> </div>						
		</div>
		<script type="text/javascript">
			TableKit.options.editAjaxURI = 'transaction.php';
            TableKit.Editable.selectInput('id_department', {}, [
						['Anesthesiology','1'],
						['Bacteriological Laboratory','2'],
                        ['Blood Bank','3'],
						['Central Laboratory','4'],
                        ['Chemical Laboratory','5'],
						['Ear-Nose-Throat','6'],
						['Emergency Ambulatory','7'],
                        ['Emergency Surgery','8'],
						['General Ambulatory','9'],
						['General Outpatient Clinic','10'],
                        ['General Surgery','11'],
						['Intensive Care Unit','12'],
                        ['Intermediate Care Unit ','13'],
						['Internal Medicine','14'],
						['Internal Medicine Ambulatory','15'],
                        ['Neonatal','16'],
						['Nuclear Diagnostics','17'],
						['Ob-Gynecology','18'],
                        ['Oncology','19'],
						['Opthalmology','20'],
						['Pathology','21'],
                        ['Physical Therapy','22'],
						['Plastic Surgery','23'],
						['Radiology','24'],
                        ['Serological Laboratory','25'],
						['Sonography','26']
                        																												
					]);
            TableKit.Editable.selectInput('sex', {}, [
						['Male','Male'],
						['Female','Female']						
					]);
            TableKit.Editable.selectInput('civilStatus', {}, [
						['Married','Married'],
						['Single','Single'],
						['Divorced','Divorced'],
						['Widowed','Widowed'],
						['Seperated','Seperated']																												
					]);               
			TableKit.Editable.multiLineInput('specialization');
		</script>
          <p>
<script type="text/javascript" charset="utf-8">
  var users_table = new TableKit( 'doctor', {
    editAjaxURI: 'transaction.php'
  });
  
/*  function addRecord() {
    var timestamp = new Date().getTime();
    var new_table = 'doctor' + timestamp;
    var url = 'transaction/addPatient' + '?timestamp=' + timestamp;
    new Ajax.Updater( 'doctorContainer', url, { 
      asynchronous: true,
      onComplete: function() {
        new TableKit( new_table, {
          editAjaxURI: 'transaction.php/update'
        })
      }
    });
  }*/
  
  function deleteRecord( id_record ) {
    var timestamp = new Date().getTime();
    var new_table = 'doctor' + timestamp;
    var url = 'transaction.php' + '?id_delete=' + id_record + "&table_delete=doctor" + '&timestamp=' + timestamp;
    var answer = confirm('Are you sure you want to delete this doctor?');
    if (answer) {
      new Ajax.Updater( 'doctor_div', url, { 
        asynchronous: true,
        onComplete: function() {
          new TableKit( new_table, {
            editAjaxURI: 'transaction.php'
          })
        }
      });
    }
  }
</script>
           <?php
           echo $pagination->displayPages();
           echo "<p class=\"paginate\">Page: <b>$pagination->current_page</b> of <b>$pagination->num_pages</b></p>\n";
          ?>
        </div>
      </div>
</body>
</html>      