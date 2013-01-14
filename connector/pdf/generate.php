<?php
/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
*/


require_once 'pdfGenerator.php';
require_once 'pdfWrapper.php';
require_once 'tcpdf_ext.php';

$error_handler = set_error_handler("PDFErrorHandler");

if (get_magic_quotes_gpc()) {
	$xmlString = stripslashes($_POST['mycoolxmlbody']);
} else {
	$xmlString = $_POST['mycoolxmlbody'];
}

$xml = new SimpleXMLElement($xmlString, LIBXML_NOCDATA);
$scPDF = new schedulerPDF();
$scPDF->printScheduler($xml);


function PDFErrorHandler ($errno, $errstr, $errfile, $errline) {
	global $xmlString;
	if ($errno < 1024) {
		error_log($xmlString, 3, 'error_report_'.date("Y_m_d__H_i_s").'.xml');
		exit(1);
	}
}

?>