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
          $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM prescription where prescription.id_lang = 2");   
          $pagination = new Pagination();
          $pagination->total_items = $total_count[0];
          $pagination->mid_range = 3;
          $pagination->paginate();
          echo "<br />Συνολικές Συνταγογραφήσεις Ασθενών : <b>$total_count[0]</b> <br /><br /><br />";
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
                    <th class="nosort nocol noedit" id="action">Διαγραφή Εγγραφής</th>
                    <th class="sortfirstdesc sortcol sortasc" id="id_patient">Όνομα Ασθενή</th>
                    <th class="sortcol" id="prescriptionDate">Ημερ/νία Συντα/φησης</th>
                    <th class="sortcol" id="id_doctor">Ιατρός</th>
                    <th class="sortcol" id="product">Φάρμακο</th>
                    <th class="sortcol" id="prescription">Συντα/φηση</th>
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
                
				<tbody id="prescription_body">
                  
                  <?php
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM prescription LEFT OUTER JOIN patient on prescription.id_patient = patient.id_patient LEFT OUTER JOIN doctor on prescription.id_doctor = doctor.id_doctor where prescription.id_lang = 2 order by id_prescription asc ".$pagination->limit);                                   
                  $i=1;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : 
                  ?>
                    <tr class="<?php echo ($i % 2 == 0) ? 'rowodd' : 'roweven'; ?>" id="<?php echo $row['id_prescription']; ?>" onMouseOver="hilite(this);" onMouseOut="lowlite(this);">
                    <td>
                    <img src="images/delete.gif" />
                    <a class="delete" href="#" onClick="deleteRecord(<?php echo $row['id_prescription']; ?>); return false">Διαγραφή</a>
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
                    <font color='red'>Όλες οι συνταγογραφήσεις ασθενών διεγράφησαν</font>
                    </td></tr>";
                  }
                  DB::Close();
                  ?>
                   <?php
                  $stmtPat = DB::getInstance()->query("SELECT * FROM patient where patient.id_lang = 2 order by id_patient asc");
                  $patients = "";
                  while($patient = $stmtPat->fetch(PDO::FETCH_ASSOC))
                  {
                    $patients .= "['$patient[firstName] $patient[lastName]',";
                    $patients .= "'$patient[id_patient]'],";
                  }
                  DB::Close();
                  ?>
                   <?php
                  $stmtDoc = DB::getInstance()->query("SELECT * FROM doctor where doctor.id_lang = 2 order by id_doctor asc");
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
						['Αγουμεντίν','Αγουμεντίν'],
						['Ντεπόν','Ντεπόν'],
						['Ασπιρίνη','Ασπιρίνη'],
						['Κορτιζόνη','Κορτιζόνη'],
						['Ρονάλ','Ρονάλ'],																																															
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
    var answer = confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτή τη συνταγογράφηση ασθενή?');
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
           echo "<p class=\"paginate\">Σελίδα: <b>$pagination->current_page</b> από <b>$pagination->num_pages</b></p>\n"; 
          ?>
        </div>
      </div>