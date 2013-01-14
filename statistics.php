<?php require_once('config/config.php'); ?>
<?php require_once(INCLUDES_DIR . '/header.php'); ?>
<!--Chart Libs-->
<script type="text/javascript" src="js/highcharts.js"></script>
<!--<script type="text/javascript" src="js/exporting.js"></script>-->
<!--END Chart Libs-->
<?php
$instance = DB::getInstance();
$salary = array();
$charges = array();
$january = $instance->query("SELECT sum(salary) as january from `doctor` WHERE `employmentDate` between '2010-01-01' and '2010-01-31'");
$jan = $january->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($jan['january'], 1, '.', '');

$february = $instance->query("SELECT sum(salary) as february from `doctor` WHERE `employmentDate` between '2010-02-01' and '2010-02-28'");
$feb = $february->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($feb['february'], 1, '.', '');

$march = $instance->query("SELECT sum(salary) as march from `doctor` WHERE `employmentDate` between '2010-03-01' and '2010-03-31'");
$mar = $march->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($mar['march'], 1, '.', '');

$april = $instance->query("SELECT sum(salary) as april from `doctor` WHERE `employmentDate` between '2010-04-01' and '2010-04-30'");
$apr = $april->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($apr['april'], 1, '.', '');

$may = $instance->query("SELECT sum(salary) as may from `doctor` WHERE `employmentDate` between '2010-05-01' and '2010-05-31'");
$may = $may->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($may['may'], 1, '.', '');

$june = $instance->query("SELECT sum(salary) as june from `doctor` WHERE `employmentDate` between '2010-06-01' and '2010-06-30'");
$jun = $june->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($jun['june'], 1, '.', '');

$july = $instance->query("SELECT sum(salary) as july from `doctor` WHERE `employmentDate` between '2010-07-01' and '2010-07-31'");
$jul = $july->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($jul['july'], 1, '.', '');

$august = $instance->query("SELECT sum(salary) as august from `doctor` WHERE `employmentDate` between '2010-08-01' and '2010-08-31'");
$aug = $august->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($aug['august'], 1, '.', '');

$september = $instance->query("SELECT sum(salary) as september from `doctor` WHERE `employmentDate` between '2010-09-01' and '2010-09-30'");
$sep = $september->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($sep['september'], 1, '.', '');

$october = $instance->query("SELECT sum(salary) as october from `doctor` WHERE `employmentDate` between '2010-10-01' and '2010-10-31'");
$oct = $october->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($oct['october'], 1, '.', '');

$november = $instance->query("SELECT sum(salary) as november from `doctor` WHERE `employmentDate` between '2010-11-01' and '2010-11-30'");
$nov = $november->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($nov['november'], 1, '.', '');

$december = $instance->query("SELECT sum(salary) as december from `doctor` WHERE `employmentDate` between '2010-12-01' and '2010-12-31'");
$dec = $december->fetch(PDO::FETCH_ASSOC);
$salary[] .= number_format($dec['december'], 1, '.', '');
$salary = implode(",", $salary);

$january = $instance->query("SELECT sum(charges) AS january FROM `patient` WHERE `registrationDate` BETWEEN '2010-01-01'
AND '2010-01-31'");
$jan = $january->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($jan['january'], 1, '.', '');

$february = $instance->query("SELECT sum(charges) AS february FROM `patient` WHERE `registrationDate` BETWEEN '2010-02-01'
AND '2010-02-28'");
$feb = $february->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($feb['february'], 1, '.', '');

$march = $instance->query("SELECT sum(charges) AS march FROM `patient` WHERE `registrationDate` BETWEEN '2010-03-01'
AND '2010-03-31'");
$mar = $march->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($mar['march'], 1, '.', '');

$april = $instance->query("SELECT sum(charges) AS april FROM `patient` WHERE `registrationDate` BETWEEN '2010-04-01'
AND '2010-04-30'");
$apr = $april->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($apr['april'], 1, '.', '');

$may = $instance->query("SELECT sum(charges) AS may FROM `patient` WHERE `registrationDate` BETWEEN '2010-05-01'
AND '2010-05-31'");
$may = $may->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($may['may'], 1, '.', '');

$june = $instance->query("SELECT sum(charges) AS june FROM `patient` WHERE `registrationDate` BETWEEN '2010-06-01'
AND '2010-06-30'");
$jun = $june->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($jun['june'], 1, '.', '');

$july = $instance->query("SELECT sum(charges) AS july FROM `patient` WHERE `registrationDate` BETWEEN '2010-07-01'
AND '2010-07-31'");
$jul = $july->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($jul['july'], 1, '.', '');

$august = $instance->query("SELECT sum(charges) AS august FROM `patient` WHERE `registrationDate` BETWEEN '2010-08-01'
AND '2010-08-31'");
$aug = $august->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($aug['august'], 1, '.', '');

$september = $instance->query("SELECT sum(charges) AS september FROM `patient` WHERE `registrationDate` BETWEEN '2010-09-01' AND '2010-09-30'");
$sep = $september->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($sep['september'], 1, '.', '');

$october = $instance->query("SELECT sum(charges) AS october FROM `patient` WHERE `registrationDate` BETWEEN '2010-10-01'
AND '2010-10-31'");
$oct = $october->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($oct['october'], 1, '.', '');

$november = $instance->query("SELECT sum(charges) AS november FROM `patient` WHERE `registrationDate` BETWEEN '2010-11-01'
AND '2010-11-30'");
$nov = $november->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($nov['november'], 1, '.', '');

$december = $instance->query("SELECT sum(charges) AS december FROM `patient` WHERE `registrationDate` BETWEEN '2010-12-01'
AND '2010-12-31'");
$dec = $december->fetch(PDO::FETCH_ASSOC);
$charges[] .= number_format($dec['december'], 1, '.', '');
$charges = implode(",", $charges);


$pharmacy = $instance->query("SELECT sum( quantity * unitPrice * 0.19) AS pharmacy FROM pharmacy_pur WHERE `purchaseDate` BETWEEN '2010-01-01' AND '2010-12-31'");
$phar = $pharmacy->fetch(PDO::FETCH_ASSOC);
$pharmacyExpense = number_format($phar['pharmacy'] / 218206.2591548158, 2, '.', '');

$patient = $instance->query("SELECT sum(charges) AS patient FROM `patient` WHERE `registrationDate` BETWEEN '2010-01-01'
AND '2010-12-31'");
$pat = $patient->fetch(PDO::FETCH_ASSOC);
$patientExpense = number_format($pat['patient'] / 218206.2591548158, 2, '.', '');

$plabtest = $instance->query("SELECT sum(testCost) AS plabtest FROM `plabtest` WHERE `testDate` BETWEEN '2010-01-01'
AND '2010-12-31'");
$pla = $plabtest->fetch(PDO::FETCH_ASSOC);
$plabtestExpense = number_format($pla['plabtest'] / 218206.2591548158, 2, '.', '');

$doctor = $instance->query("SELECT sum(salary) AS doctor FROM `doctor` WHERE `employmentDate` BETWEEN '2010-01-01'
AND '2010-12-31'");
$doc = $doctor->fetch(PDO::FETCH_ASSOC);
$doctorExpense = number_format($doc['doctor'] / 218206.2591548158, 2, '.', '');

DB::Close();
?>
<script type="text/javascript">
		
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						margin: [80, 100, 60, 100],
						zoomType: 'xy'
					},
					title: {
						text: 'Average Monthly Expenses and Revenue',
						style: {
							margin: '10px 0 0 0' // center it
						}
					},
					subtitle: {
						text: 'Doctor Salaries And Patient Charges',
						style: {
							margin: '0 0 0 0' // center it
						}
					},
					xAxis: [{
						categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
							'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					}],
					yAxis: [{ // Primary yAxis
						labels: {
							formatter: function() {
								return this.value +' Euro';
							},
							style: {
								color: '#89A54E'
							}
						},
						title: {
							text: 'Patient Charges',
							style: {
								color: '#89A54E'
							},
							margin: 90
						}
					}, { // Secondary yAxis
						title: {
							text: 'Doctor Salaries',
							margin: 80,
							style: {
								color: '#4572A7'
							}
						},
						labels: {
							formatter: function() {
								return this.value +' Euro';
							},
							style: {
								color: '#4572A7'
							}
						},
						opposite: true
					}],
					tooltip: {
						formatter: function() {
							return ''+ this.x +': '+ this.y + (this.series.name == 'Doctor Salaries' ? ' Euro' : 'Euro');
						}
					},
					legend: {
						layout: 'vertical',
						style: {
							left: '120px',
							bottom: 'auto',
							right: 'auto',
							top: '100px'
						},
						backgroundColor: '#FFFFFF'
					},
					series: [{
						name: 'Doctor Salaries',
						color: '#4572A7',
						type: 'column',
						yAxis: 1,
						data: [<?php echo $salary; ?>]		
					
					}, {
						name: 'Patient Charges',
						color: '#89A54E',
						type: 'spline',
						data: [<?php echo $charges; ?>]
					}]
				});
				
				
			});
            
            
            var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'pieChart',
						margin: [50, 200, 60, 170]
					},
					title: {
						text: 'Expenses And Revenue of 2010'
					},
					plotArea: {
						shadow: null,
						borderWidth: null,
						backgroundColor: null
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								formatter: function() {
									if (this.y > 5) return this.point.name;
								},
								color: 'white',
								style: {
									font: '13px Trebuchet MS, Verdana, sans-serif'
								}
							}
						}
					},
					legend: {
						layout: 'vertical',
						style: {
							left: 'auto',
							bottom: 'auto',
							right: '50px',
							top: '100px'
						}
					},
				    series: [{
						type: 'pie',
						name: 'Yearly Expenses And Income',
						data: [
							['Patient Lab Test', <?php echo $plabtestExpense; ?>],
							['Pharmacy Purchases', <?php echo $pharmacyExpense; ?>],
							{
								name: 'Doctor Salaries',    
								y: <?php echo $doctorExpense; ?>,
								sliced: true,
								selected: true
							},
							['Patient Charges', <?php echo $patientExpense; ?>]
						]
					}]
				});
			});
				
</script>        
<body>
<div class="main">

  <?php require_once(INCLUDES_DIR . "/top_menu.php"); ?>

  <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article" id="container" style="width: 730px; height: 400px; margin: 0 auto">
         <!--Chart body-->
        </div>
      </div>
      <div class="sidebar">
        <div class="searchform">
          <?php echo (isset($_SESSION['id_employee']) && !empty($_SESSION['id_employee'])) ? "<img src='images/userpic.gif' align='top' />&nbsp;Welcome ".$_SESSION['firstname']." ".$_SESSION['lastname']."!&nbsp;&nbsp;<a href='index.php?ToDo=logout'>(Logout)</a>" : ""; ?>
        </div>
        <?php require_once(INCLUDES_DIR . '/mainmenu.php'); ?>
       
      </div>
      <div class="clr"></div>
    </div>
  </div>

  <div class="fbg">
    <div class="fbg_resize">
      <div class="col c2" id="pieChart" style="width: 730px; height: 400px; margin: 0 auto">
        <!--Pie Chart Body-->
      </div>
      <div class="clr"></div>
    </div>
  </div>
    <?php require_once(INCLUDES_DIR . '/footer.php'); ?>
</div>
</body>
</html>