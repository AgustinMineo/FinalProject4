<?php 


require 'autoload.php';

use Dompdf\Dompdf;
function generateCupon($firstDay,$lastDay,$totalReservation,$totalMoney,$KeeperName,$KeeperCUIT){}
    $lastDay = '2022-11-10';
    $totalReservation = 2022;
    $keeperCUIT='30-21212121-9';
    $html ='<H1>HOLA jose DEL SALVADOR CUACK</H1>
    ';
    $dompdf = new Dompdf();
    $dompdf->load_html($html);
    $dompdf->render();
    $output = $dompdf->output();
    $rutaGuardado = '../Vendor/dompdf/dompdf/output/';
    //$dompdf->stream("documento.pdf",array('Attachment'=>'0'));
    $nombreArchivo ="NUEVOPDF.pdf";
    if(file_exists($rutaGuardado)){
            file_put_contents($rutaGuardado.$nombreArchivo, $output);
    }else{ 
        echo "<h1>Error al intentar guardar el archivo</h1>";
    }

?>