<!-- LIBS -->
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/tablekit.js"></script>
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
<!--</head>-->

<body>
<div class="mainbar">
         <?php
          $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM prescription where prescription.id_lang = 1");   
          $pagination = new Pagination();
          $pagination->total_items = $total_count[0];
          $pagination->mid_range = 3;
          $pagination->paginate();
          echo "<br />Total Patient Prescriptions : <b>$total_count[0]</b> <br /><br /><br />";
          echo $pagination->displayPages();
          echo "<span class=\"\">&nbsp;&nbsp;".$pagination->displayJumpMenu()."&nbsp;&nbsp;".
          $pagination->displayItemsPerPage()."</span>";
          ?>
        <div id="prescription_div" class="article">
          <p></p>
          <br />
           <table id="prescription" class="sortable resizable editable">
				<thead>
					<tr>
                    <th class="nosort nocol noedit" id="action">Delete Record</th>
                    <th class="sortfirstdesc sortcol sortasc" id="id_patient">Patient Name</th>
                    <th class="sortcol" id="prescriptionDate">Prescription Date</th>
                    <th class="sortcol" id="id_doctor">Doctor Name</th>
                    <th class="sortcol" id="product">Product</th>
                    <th class="sortcol" id="prescription">Prescription</th>
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
                
				<tbody id="prescription_body">
                  
                  <?php
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM prescription LEFT OUTER JOIN patient on prescription.id_patient = patient.id_patient LEFT OUTER JOIN doctor on prescription.id_doctor = doctor.id_doctor where prescription.id_lang = 1 order by id_prescription asc ".$pagination->limit);                                   
                  $i=1;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : 
                  ?>
                    <tr class="<?php echo ($i % 2 == 0) ? 'rowodd' : 'roweven'; ?>" id="<?php echo $row['id_prescription']; ?>" onMouseOver="hilite(this);" onMouseOut="lowlite(this);">
                    <td>
                    <img src="images/delete.gif" />
                    <a class="delete" href="#" onClick="deleteRecord(<?php echo $row['id_prescription']; ?>); return false">delete</a>
                    </td>
                    <td><?php echo $row['firstName']." ".$row['lastName']; ?></td>
                    <td><?php echo $row['prescriptionDate']; ?></td>
                    <td><?php echo $row['doctorName']." ".$row['doctorSurname']; ?></td>
                    <td><?php echo $row['product']; ?></td>
                    <td><?php echo $row['prescription']; ?></td>
                    </tr>
                  <?php  
                  $i++;
                  endwhile;
                  }
                  else
                  {
                    echo "<tr><td colspan='4' class='nocol'>
                    <font color='red'>All Patient Prescriptions Deleted</font>
                    </td></tr>";
                  }
                  DB::Close();
                  ?>
                   <?php
                  $stmtPat = DB::getInstance()->query("SELECT * FROM patient where patient.id_lang = 1 order by id_patient asc");
                  $patients = "";
                  while($patient = $stmtPat->fetch(PDO::FETCH_ASSOC))
                  {
                    $patients .= "['$patient[firstName] $patient[lastName]',";
                    $patients .= "'$patient[id_patient]'],";
                  }
                  DB::Close();
                  ?>
                   <?php
                  $stmtDoc = DB::getInstance()->query("SELECT * FROM doctor where doctor.id_lang = 1 order by id_doctor asc");
                  $doctors = "";
                  while($doctor = $stmtDoc->fetch(PDO::FETCH_ASSOC))
                  {
                    $doctors .= "['$doctor[doctorName] $doctor[doctorSurname]',";
                    $doctors .= "'$doctor[id_doctor]'],";
                  }
                  DB::Close();
                  ?>

               </tbody>
			</table><br />
			<div class="panel" id="doco-editable"> </div>						
		</div>
        
		<script type="text/javascript">
		
			TableKit.options.editAjaxURI = 'includes/transaction.php';
            
            TableKit.Editable.selectInput('id_patient', {}, [
						<?php echo $patients; ?>																												
					]);
            
            TableKit.Editable.selectInput('id_doctor', {}, [
						<?php echo $doctors; ?>																												
					]);
            
            TableKit.Editable.selectInput('product', {}, [
						['Augumentin','Augumentin'],
						['Depon','Depon'],
						['Aspirin','Aspirin'],
						['Cortizon','Cortizon'],
						['Ronal','Ronal'],																																															
					]);
            TableKit.Editable.multiLineInput('prescription');
            
		</script>
          <p>
<script type="text/javascript" charset="utf-8">
  var users_table = new TableKit( 'prescription', {
    editAjaxURI: 'includes/transaction.php'
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
    var new_table = 'prescription' + timestamp;
    var url = 'includes/transaction.php' + '?id_delete=' + id_record + '&table_delete=prescription' + '&timestamp=' + timestamp;
    var answer = confirm('Are you sure you want to delete this patient prescription?');
    if (answer) {
      new Ajax.Updater( 'prescription_div', url, { 
        asynchronous: true,
        onComplete: function() {
          new TableKit( new_table, {
            editAjaxURI: 'includes/transaction.php'
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