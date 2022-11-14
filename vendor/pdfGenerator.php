<?php 
require 'autoload.php';

$html ='
';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("documento.pdf",array('Attachment'=>'0'));
?>