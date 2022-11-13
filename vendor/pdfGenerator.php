<?php 
require 'autoload.php';

$html ='

<h1>HOLAAAA DESDE PDF desde php xd</h1>



';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("documento.pdf",array('Attachment'=>'0'));
?>