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
        // we set the ID owner to add.
        $owner->setOwnerID($this->getLastID());
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
            $ownerValue["userDescription"] = $Owner->getDescription();
            $ownerValue["petAmount"] = $Owner->getPetAmount();
            $ownerValue["answerRecovery"] = $Owner->getAnswerRecovery();
            $ownerValue["questionRecovery"] = $Owner->getQuestionRecovery();
         
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
                $owner->setDescription($OwnerDecode["userDescription"]);
                $owner->setPetAmount($OwnerDecode["petAmount"]);
                $owner->setAnswerRecovery($OwnerDecode["answerRecovery"]);
                $owner->setQuestionRecovery($OwnerDecode["questionRecovery"]);
                
                array_push($this->ownerList, $owner);
            }
        }else{
            echo "The owners file doesn't exists";
        }

    }

    public function searchOwnerByEmail($email){ // Buscar un owner por el correo. (Tanto para login como para registro.)
        $this->RetriveData();
        foreach($this->ownerList as $value){ /// Buscamos dentro del arreglo de owners
            if($value->getEmail()== $email){ /// Si el correo es el mismo, entonces devolvemos el owner, sino
                     return $value;
            }
        }
        return null;
    }

    public function searchOwnerToLogin($email,$password){
        $newOwner =$this->searchOwnerByEmail($email);
        if($newOwner){
            if($newOwner->getPassword()==$password){
              //  session_start(); // start the session
                return $newOwner;
            }
        }else{
            return null;
            
        }
    }

    public function getLastID(){
        $this->RetriveData();
        if($this->ownerList != NULL){
            $owner = end($this->ownerList);
            return $owner->getOwnerID() + 1;
        }
        else{
            return 1;
        }
    }
    // No funciona
    public function incrementPetAmount($id){
        $this->RetriveData();
        if($this->ownerList!= NULL){
            foreach($this->ownerList as $owner){
                if($owner->getOwnerId() == $id){
                    return $owner->getPetAmount()+1;
                }
            }
        }
    }

    public function updateName($newName,$emailUser){
        $owner = $this->searchOwnerByEmail($emailUser);
        $owner->setLastName($newName);
        $this->SaveData();
        return $owner;
    }

    public function updateFirstName($newFirstName,$emailUser){
        $owner = $this->searchOwnerByEmail($emailUser);
        $owner->setFirstName($newFirstName);
        $this->SaveData();
        return $owner;
    }

    public function updateCellphone($newCellphone,$emailUser){
        $owner = $this->searchOwnerByEmail($emailUser);
        $owner->setCellPhone($newCellphone);
        $this->SaveData();
        return $owner;
    }
    public function updateDescription($newDescription,$emailUser){
        $owner = $this->searchOwnerByEmail($emailUser);
        $owner->setDescription($newDescription);
        $this->SaveData();
        return $owner;
    }
    public function updatePassword($password,$emailUser){
        $owner = $this->searchOwnerByEmail($emailUser);
        $owner->setPassword($password);
        $this->SaveData();
        return $owner;
    }

    public function recoveryComparte($question,$answer,$owner){
        if($owner){
            if(strcmp($owner->getQuestionRecovery(),$question) && strcmp($owner->getAnswerRecovery(),$answer)){
                return true;
            }else{
                return false;
            }
        }
    }
    public function updatePasswordRecovery($owner,$password){
        $ownerUpdate=$this->searchOwnerByEmail($owner);
        $ownerUpdate->setPassword($password);
        $this->SaveData();
        return true;
    }
}
?>