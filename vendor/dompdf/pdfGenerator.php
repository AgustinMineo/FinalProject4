<?php 


require 'autoload.php';

use Dompdf\Dompdf;
function generateCupon($firstDay,$lastDay,$totalReservation,$totalMoney,$KeeperName,$KeeperCUIT){}
    $lastDay = 'asdadsa';
    $totalReservation = 21212121212122121;
    $keeperCUIT='3321321312312313123123120';
    $html ='asddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd
    ';
    $dompdf = new Dompdf();
    $dompdf->load_html($html);
    $dompdf->render();
    $output = $dompdf->output();
    $rutaGuardado = '../vendor/output/';
    $dompdf->stream("documento.pdf",array('Attachment'=>'1'));
    $nombreArchivo ="$lastDay$totalReservation$keeperCUIT.pdf";
    if(file_exists($rutaGuardado)){
            file_put_contents($rutaGuardado.$nombreArchivo, $output);
            echo "procese ok";
    }else{ 
        echo "<h1>Error al intentar guardar el archivo</h1>";
    }
?>