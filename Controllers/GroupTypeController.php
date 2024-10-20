<?php
namespace Controllers;
use \Exception as Exception;
use DAODB\GroupTypeDAO as GroupTypeDAO;

class GroupTypeController{
    private $GroupTypeDAO;

    public function __construct(){
        $this->GroupTypeDAO = new GroupTypeDAO();
    }

    public function getGroupTypeById($typeID){
        if($typeID){
            try{
                $type= $this->GroupTypeDAO->getGroupTypeById($typeID);
                if($type){
                    return $type;
                }else{
                    return null;
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }
    }
}
?>