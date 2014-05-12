<?php
//Create PDF
include("mpdf/mpdf.php");

if(isset($_POST['printdata'])) {
  $html=$_POST['printdata'];
  create($html);
} else {
  echo "Sorry didnt get the data";
}

function create($html) {
  
  
  $mpdf = new mPDF();
  $mpdf->WriteHTML($html);
  $mpdf->Output();
  exit;
}

function create2($html) {
  

  $mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 
  
  $mpdf->SetDisplayMode('fullpage');
  
  $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
  
  // LOAD a stylesheet
  $stylesheet = file_get_contents('css/pdfcss.css');
  $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
  
  $mpdf->WriteHTML($html,2);
  
  $mpdf->Output('pdf/fazmin_test.pdf','I');
  echo 'YES !!! PDF CREATED';
  exit;
}
?>