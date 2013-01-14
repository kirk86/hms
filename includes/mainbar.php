<?php 
require_once('functions.php');
__autoload("pdo");
__autoload("pagination");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Patients</title>
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
          $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM patient where patient.id_lang = 1");   
          $pagination = new Pagination();
          $pagination->total_items = $total_count[0];
          $pagination->mid_range = 3;
          $pagination->paginate();
          echo "<br />Total Patients : <b>$total_count[0]</b> <br /><br /><br />";
          echo $pagination->displayPages();
          echo "<span class=\"\">&nbsp;&nbsp;".$pagination->displayJumpMenu()."&nbsp;&nbsp;".
          $pagination->displayItemsPerPage()."</span>";
          ?>
        <div id="patient_div" class="article">
          <p></p>
          <br />
           <table id="patient" class="sortable resizable editable">
				<thead>
					<tr>
                    <th class="nosort nocol noedit" id="action">Delete Record</th>
                    <th class="sortcol" id="urgency">Urgency</th>
                    <th class="sortcol" id="id_category">Category</th>
                    <th class="sortfirstdesc sortcol sortasc" id="firstName">First Name</th>
                    <th class="sortcol" id="lastName">Last Name</th>
                    <th class="sortcol" id="registrationDate">Registration Date</th>
                    <th class="sortcol" id="birthDate">Birth Date</th>
                    <th class="sortcol" id="sex">Sex</th>
                    <th class="sortcol" id="bloodGroup">Blood Group</th>
                    <th class="sortcol" id="civilStatus">Civil Satus</th>
                    <th class="sortcol" id="address">Address</th>
                    <th class="sortcol" id="postCode">Post Code</th>
                    <th class="sortcol" id="insuranceType">Insurance Type</th>
                    <th class="sortcol" id="insuranceCompany">Insurance Company</th>
                    <th class="sortcol" id="phone">Phone</th>
                    <th class="sortcol" id="diagnosis">Diagnosis</th>
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
                    <td align="center">Phone</td>
                    <td>Diagnosis</td>
                    </tr>
				</tfoot>
                
				<tbody id="patient_body">
                  
                  <?php
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM patient LEFT OUTER JOIN category on patient.id_category = category.id_category where patient.id_lang = 1 order by id_patient asc ".$pagination->limit);                                   
                  $i=1;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : 
                  ?>
                    <tr class="<?php echo ($i % 2 == 0) ? 'rowodd' : 'roweven'; ?>" id="<?php echo $row['id_patient']; ?>" onmouseover="hilite(this);" onmouseout="lowlite(this);">
                    <td>
                    <img src="../images/delete.gif" />
                    <a class="delete" href="#" onclick="deleteRecord(<?php echo $row['id_patient']; ?>); return false">delete</a>
                    </td>
                    <td><div class="urg<?php echo $row['urgency']; ?>"><?php echo $row['urgency']; ?></div></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['firstName']; ?></td>
                    <td><?php echo $row['lastName']; ?></td>
                    <td><?php echo $row['registrationDate']; ?></td>
                    <td><?php echo $row['birthDate']; ?></td>
                    <td><?php echo $row['sex']; ?></td>
                    <td><?php echo $row['bloodGroup']; ?></td>
                    <td><?php echo $row['civilStatus']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['postCode']; ?></td>
                    <td><?php echo $row['insuranceType']; ?></td>
                    <td><?php echo $row['insuranceCompany']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['diagnosis']; ?></td>
                    </tr>
                  <?php  
                  $i++;
                  endwhile;
                  }
                  else
                  {
                    echo "<tr><td colspan='4' class='nocol'>
                    <font color='red'>All Patients Deleted</font>
                    </td></tr>";
                  }
                  DB::Close();
                  ?>

               </tbody>
			</table><br />
			<div class="panel" id="doco-editable"> </div>						
		</div>
	
		<script type="text/javascript" charset="utf-8">
			//TableKit.Sortable.addSortType(new TableKit.Sortable.Type('status', {
			//		pattern : /^[New|Assigned|In Progress|Closed]$/,
			//		normal : function(v) {
			//			var val = 4;
			//			switch(v) {
			//				case 'New':
			//					val = 0;
			//					break;
			//				case 'Assigned':
			//					val = 1;
			//					break;
			//				case 'In Progress':
			//					val = 2;
			//					break;
			//				case 'Closed':
			//					val = 3;
			//					break;
			//			}
			//			return val;
			//		}
			//	}
			//));
			TableKit.options.editAjaxURI = 'transaction.php';
            TableKit.Editable.selectInput('urgency', {}, [
						['1','1'],
						['2','2'],
						['3','3'],
						['4','4'],
						['5','5']																												
					]);
            TableKit.Editable.selectInput('id_category', {}, [
						['Inpatient','1'],
						['Discharged','2'],
						['Died','3']																												
					]);
            TableKit.Editable.selectInput('sex', {}, [
						['Male','Male'],
						['Female','Female']						
					]);
            TableKit.Editable.selectInput('bloodGroup', {}, [
						['A','A'],
						['B','B'],
						['AB','AB'],
						['O','O']																												
					]);
            TableKit.Editable.selectInput('civilStatus', {}, [
						['Married','Married'],
						['Single','Single'],
						['Divorced','Divorced'],
						['Widowed','Widowed'],
						['Seperated','Seperated']																												
					]);
            TableKit.Editable.selectInput('insuranceType', {}, [
						['Public','Public'],
						['Private','Private'],
						['Self Founded','Self Founded']																											
					]);                                        
			TableKit.Editable.multiLineInput('address');
		</script>
          <p>
<script type="text/javascript" charset="utf-8">
  var users_table = new TableKit( 'patient', {
    editAjaxURI: 'transaction.php'
  });
  
/*  function addUser() {
    var timestamp = new Date().getTime();
    var new_table = 'patient' + timestamp;
    var url = 'transaction/addPatient' + '?timestamp=' + timestamp;
    new Ajax.Updater( 'article', url, { 
      asynchronous: true,
      onComplete: function() {
        new TableKit( new_table, {
          editAjaxURI: 'mainbar.php'
        })
      }
    });
  }*/
  
  function deleteRecord( id_record ) {
    var timestamp = new Date().getTime();
    var new_table = 'patient' + timestamp;
    var url = 'transaction.php' + '?id_delete=' + id_record + '&table_delete=patient' + '&timestamp=' + timestamp;
    var answer = confirm('Are you sure you want to delete this patient?');
    if (answer) {
      new Ajax.Updater( 'patient_div', url, { 
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