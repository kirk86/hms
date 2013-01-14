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
          $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM doctor where doctor.id_lang = 2");   
          $pagination = new Pagination();
          $pagination->total_items = $total_count[0];
          $pagination->mid_range = 3;
          $pagination->paginate();
          echo "<br />Συνολικοί Ιατροί : <b>$total_count[0]</b> <br /><br /><br />";
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
                    <th class="nosort nocol noedit" id="action">Διαγραφή Εγγραφής</th>
                    <th class="sortcol" id="id_department">Τμήμα</th>
                    <th class="sortfirstdesc sortcol sortasc" id="doctorName">Όνομα</th>
                    <th class="sortcol" id="doctorSurname">Επώνυμο</th>
                    <th class="sortcol" id="specialization">Ειδικότητα</th>
                    <th class="sortcol" id="employmentDate">Ημερ/νία Πρόσληψης</th>
                    <th class="sortcol" id="releaseDate">Ημερ/νία Απόλυσης</th>
                    <th class="sortcol" id="salary">Μισθός</th>
                    <th class="sortcol" id="sex">Φύλο</th>
                    <th class="sortcol" id="civilStatus">Οικογ. Κατάσταση</th>
                    <th class="sortcol" id="email">Email</th>
                    <th class="sortcol" id="phone">Τηλέφωνο</th>
                    </tr>
			</thead>
				<tfoot>
					<tr>
                    <td>Διαγραφή Εγγραφής</td>
                    <td>Τμήμα</td>
                    <td>Όνομα</td>
                    <td>Επώνυμο</td>
                    <td>Ειδικότητα</td>
                    <td>Ημερ/νία Πρόσληψης</td>
                    <td>Ημερ/νία Απόλυσης</td>
                    <td>Μισθός</td>
                    <td>Φύλο</td>
                    <td>Οικογ. Κατάσταση</td>
                    <td align="center">Email</td>
                    <td align="center">Τηλέφωνο</td>
                    </tr>
				</tfoot>
                
				<tbody id="doctor_body">
                  
                  <?php
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM doctor LEFT OUTER JOIN department on doctor.id_department = department.id_department where doctor.id_lang = 2 order by id_doctor asc ".$pagination->limit);                                   
                  $i=1;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : 
                  ?>
                    <tr class="<?php echo ($i % 2 == 0) ? 'rowodd' : 'roweven'; ?>" id="<?php echo $row['id_doctor']; ?>" onmouseover="hilite(this);" onmouseout="lowlite(this);">
                    <td>
                    <img src="../images/delete.gif" />
                    <a class="delete" href="#" onclick="deleteRecord(<?php echo $row['id_doctor']; ?>); return false">Διαγραφή</a>
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
                    <font color='red'>Όλοι οι ιατροί διεγράφησαν</font>
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
						['Αναισθησιολογικό','27'],
						['Βακτηριολογικό Εργαστήριο','28'],
                        ['Ομάδα Αίματος','29'],
						['Κεντρικό Εργαστήριο','30'],
                        ['Χημικό Εργαστήριο','31'],
						['Ωττορυνολαρυγολόγος','32'],
						['Γενική Επειγόντων','33'],
                        ['Επείγοντα Χειρουργικά','34'],
						['Κεντρική Νοσηλευτική','35'],
						['Γενική Εξωτερική Κλινική','36'],
                        ['Γενική Χειρουργική','37'],
						['Μονάδα Εντατικής Φροντίδας','38'],
                        ['Ενδιάμεση Μονάδα Φροντίδας','39'],
						['Εσωτερική Ιατρική','40'],
						['Φαρμακευτική Νοσηλευτική','41'],
                        ['Νεογνών','42'],
						['Πυρηνικής Διάγνωσης','43'],
						['Γυναικολογική','44'],
                        ['Οκγολογική','45'],
						['Οφθαλμολογική','46'],
						['Παθολογική','47'],
                        ['Φυσικής Θεραπείας','48'],
						['Πλαστική Χειρουργική','49'],
						['Ραδιολογίας','50'],
                        ['Ορώδες Εργαστήριο','51'],
						['Σονογράφος','52']
                        																												
					]);
            TableKit.Editable.selectInput('sex', {}, [
						['Άρρεν','Άρρεν'],
						['Θύλη','Θύλη']						
					]);
            TableKit.Editable.selectInput('civilStatus', {}, [
						['Παντρεμένος','Παντρεμένος'],
						['Ανύπαντρος','Ανύπαντρος'],
						['Διαζευγμένος','Διαζευγμένος'],
						['Χήρος','Χήρος'],
						['Ενδιάσταση','Ενδιάσταση']																												
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
    var answer = confirm('Είστε σίγουρος ότι θέλετε να διαγράψετε αυτόν τον γιατρό?');
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
           echo "<p class=\"paginate\">Σελίδα: <b>$pagination->current_page</b> από <b>$pagination->num_pages</b></p>\n";
          ?>
        </div>
      </div>
</body>
</html>      