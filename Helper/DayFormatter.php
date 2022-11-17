<?php
namespace Helper;

class DayFormatter{

    public function __construct(){
    }

    public static function formatDate($fecha){
        $date=date_create($fecha);
        return date_format($date,"d/m/Y");
    }

}

?>