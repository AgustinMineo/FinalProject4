<?php
namespace DAO;
use DAO\IOwnerDAO as IOwnerDAO;
use Models\Owner as Owner;

class OwnerDAO implements IOwnerDAO {
    // create array of owners
    private $ownerList = array();

    public function AddOwner(Owner $owner){
        // we bring the data of the json to add a new owner
        $this->RetriveData();
        // we add the new owner at the list
        array_push($this->ownerList, $owner);

        // save the data
        $this->SaveData();
    }

    public function getAllOwner(){
        // bring all of the owners
        $this->RetriveData();
        // return the owner list
        return $this->ownerList;
    }
    private function SaveData(){
        // creamos un arreglo de owners
        $ownerArrayEncode= array();
        // recorremos la lista y guardamos la info del los owners.
        foreach($this->ownerList as $Owner){
            $ownerValue = array();
            $ownerValue["ownerId"] = $Owner->getOwnerId();
            $ownerValue["lastName"] = $Owner->getLastName();
            $ownerValue["firstName"] = $Owner->getfirstName();
            $ownerValue["cellPhone"] = $Owner->getCellPhone();
            $ownerValue["birthDate"] = $Owner->getbirthDate();
            $ownerValue["email"] = $Owner->getEmail();
            $ownerValue["password"] = $Owner->getPassword();
            //$ownerValue["petAmount"] = $Owner->getPetAmount();
            
            array_push($ownerArrayEncode, $ownerValue);
        }
        $ownerFile = json_encode($ownerArrayEncode, JSON_PRETTY_PRINT);
        file_put_contents('Data/Owners.json',$ownerFile);
    }

    private function RetriveData(){
        $this->ownerList = array();
        //Tenemos que tener el file creado
        if(file_exists('Data/Owners.json')){
            $ownerFile = file_get_contents(ROOT.'Data/Owners.json');
            // Si el file tiene datos hace un decode de la info y la guarda en el arreglo, sino devuelve un arreglo vacio.
            $ownerFileDecode = ($ownerFile) ? json_decode($ownerFile, true) : array();

            foreach($ownerFileDecode as $OwnerDecode){
                $owner = new Owner();
                $owner->setOwnerId($OwnerDecode["ownerId"]);
                $owner->setLastName($OwnerDecode["lastName"]);
                $owner->setfirstName($OwnerDecode["firstName"]);
                $owner->setCellPhone($OwnerDecode["cellPhone"]);
                $owner->setbirthDate($OwnerDecode["birthDate"]);
                $owner->setEmail($OwnerDecode["email"]);
                $owner->setPassword($OwnerDecode["password"]);
                //$owner->setPetAmount($OwnerDecode["petAmount"]);

                array_push($this->ownerList, $owner);
            }
        }else{
            echo "The owners file doesn't exists";
        }

    }

    public function searchEmail($email){ // Buscar un owner por el correo. (Tanto para login como para registro.)
        $searchArray = array();
        $searchArray = getAllOwner();
        if(array_filter($searchArray, "$email")){
            
        }
    }
}
?>