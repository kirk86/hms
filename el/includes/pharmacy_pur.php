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
<!--</head>-->

<body>
<div class="mainbar">
         <?php
          $total_count = DB::GetOne("SELECT COUNT(*) AS total_count FROM pharmacy_pur where pharmacy_pur.id_lang = 2");   
          $pagination = new Pagination();
          $pagination->total_items = $total_count[0];
          $pagination->mid_range = 3;
          $pagination->paginate();
          echo "<br />Συνολικές Αγορές Φαρμάκων : <b>$total_count[0]</b> <br /><br /><br />";
          echo $pagination->displayPages();
          echo "<span class=\"\">&nbsp;&nbsp;".$pagination->displayJumpMenu()."&nbsp;&nbsp;".
          $pagination->displayItemsPerPage()."</span>";
          ?>
        <div id="pharmacy_pur_div" class="article">
          <p></p>
          <br />
           <table id="pharmacy_pur" class="sortable resizable editable">
				<thead>
					<tr>
                    <th class="nosort nocol noedit" id="action">Διαγραφή Εγγραφής</th>
                    <th class="sortfirstdesc sortcol sortasc" id="pharmacyProduct">Προϊόν</th>
                    <th class="sortcol" id="supplier">Προμηθευτής</th>
                    <th class="sortcol" id="purchaseDate">Ημερ/νία Αγοράς</th>
                    <th class="sortcol" id="id_doctor">Ιατρός</th>
                    <th class="sortcol" id="quantity">Ποσότητα</th>
                    <th class="sortcol" id="unitPrice">Τιμή</th>
                    <th class="sortcol" id="status">Κατάσταση</th>
                    <th class="sortcol" id="discount">Έκτπωση%</th>
                    <th class="sortcol" id="tax">Φόρος%</th>
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
                    <td>Έκτπωση%</td>
                    <td>Φόρος%</td>
                    </tr>
				</tfoot>
                
				<tbody id="pharmacy_pur_body">
                  
                  <?php
                  if($total_count[0] > 0) {
                  $stmt = DB::getInstance()->query("SELECT * FROM pharmacy_pur LEFT OUTER JOIN doctor on pharmacy_pur.id_doctor = doctor.id_doctor where pharmacy_pur.id_lang = 2 order by id_pharmacy_pur asc ".$pagination->limit);                                   
                  $i=1;
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : 
                  ?>
                    <tr class="<?php echo ($i % 2 == 0) ? 'rowodd' : 'roweven'; ?>" id="<?php echo $row['id_pharmacy_pur']; ?>" onmouseover="hilite(this);" onmouseout="lowlite(this);">
                    <td>
                    <img src="../images/delete.gif" />
                    <a class="delete" href="#" onclick="deleteRecord(<?php echo $row['id_pharmacy_pur']; ?>); return false">Διαγραφή</a>
                    </td>
                    <td><?php echo $row['pharmacyProduct']; ?></td>
                    <td><?php echo $row['supplier']; ?></td>
                    <td><?php echo $row['purchaseDate']; ?></td>
                    <td><?php echo $row['doctorName']." ".$row['doctorSurname']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['unitPrice']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['discount']; ?></td>
                    <td><?php echo $row['tax']; ?></td>
                    </tr>
                  <?php  
                  $i++;
                  endwhile;
                  }
                  else
                  {
                    echo "<tr><td colspan='4' class='nocol'>
                    <font color='red'>Όλες οι αγορές φαρμάκων διεγράφησαν</font>
                    </td></tr>";
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
            
            TableKit.Editable.selectInput('id_doctor', {}, [
						<?php echo $doctors; ?>																												
					]);
            
            TableKit.Editable.selectInput('pharmacyProduct', {}, [
						['Αγουμεντίν','Αγουμεντίν'],
						['Ντεπόν','Ντεπόν'],
						['Ασπιρίνη','Ασπιρίνη'],
						['Κορτιζόνη','Κορτιζόνη'],
                        ['Ρονάλ','Ρονάλ']																																															
					]);
            TableKit.Editable.selectInput('supplier', {}, [
						['Λέξον','Λέξον'],
						['Βιομηχανίες Άρβις','Βιομηχανίες Άρβις'],
						['Χημική ΑΕ','Χημική ΑΕ'],
						['Όμικρον ΑΕ','Όμικρον ΑΕ']																																															
					]);
            TableKit.Editable.selectInput('status', {}, [
						['Σε παραγγελία','Σε παραγγελία'],
						['Παραλήφθηκε','Παραλήφθηκε']																
					]);
		</script>
          <p>
<script type="text/javascript" charset="utf-8">
  var users_table = new TableKit( 'pharmacy_pur', {
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
    var new_table = 'pharmacy_pur' + timestamp;
    var url = 'transaction.php' + '?id_delete=' + id_record + '&table_delete=pharmacy_pur' + '&timestamp=' + timestamp;
    var answer = confirm('Είστε σίγουρος ότι θέλετε να διαγράψετε αυτή την αγορά φαρμάκων?');
    if (answer) {
      new Ajax.Updater( 'pharmacy_pur_div', url, { 
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