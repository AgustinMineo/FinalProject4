<?php
namespace DAO;
use DAO\IKeeperDAO as IKeeperDAO;
use Models\Keeper as Keeper;

class KeeperDAO implements IKeeperDAO{
  // create array of keeper
  private $keeperList = array();

  public function AddKeeper(Keeper $keeper){
      // we bring the data of the json to add a new keeper
      $this->RetriveData();
      //We sett KEEPER ID for Save in the Json
      $keeper->setKeeperID($this->getLastID());
      // we add the new keeper at the list
      array_push($this->keeperList, $keeper);

      // save the data
      $this->SaveData();
  }

  public function getAllKeeper(){
      // bring all of the keeper
      $this->RetriveData();
      // return the keeper list
      return $this->keeperList;
  }
  private function SaveData(){
      // creamos un arreglo de keepers
      $keeperArrayEncode= array();
      // recorremos la lista y guardamos la info del los keeper.
      foreach($this->keeperList as $Keeper){
          $keeperValue = array();
          $keeperValue["keeperId"] = $Keeper->getKeeperId();
          $keeperValue["lastName"] = $Keeper->getLastName();
          $keeperValue["firstName"] = $Keeper->getfirstName();
          $keeperValue["cellPhone"] = $Keeper->getCellPhone();
          $keeperValue["birthDate"] = $Keeper->getbirthDate();
          $keeperValue["email"] = $Keeper->getEmail();
          $keeperValue["password"] = $Keeper->getPassword();
          $keeperValue["firstAvailabilityDays"] = $Keeper->getFirstAvailabilityDays();// cambiar a 2 variables
          $keeperValue["lastAvailabilityDays"] = $Keeper->getLastAvailabilityDays();// cambiar a 2 variables
          $keeperValue["animalSize"] = $Keeper->getAnimalSize();
          $keeperValue["cbu"] = $Keeper->getCBU();
          $keeperValue["price"] = $Keeper->getPrice();   
          $keeperValue["answerRecovery"] = $Keeper->getAnswerRecovery();
          $keeperValue["questionRecovery"] = $Keeper->getQuestionRecovery();       
          
          array_push($keeperArrayEncode, $keeperValue);
      }
      $keeperFile = json_encode($keeperArrayEncode, JSON_PRETTY_PRINT);
      file_put_contents('Data/Keepers.json',$keeperFile);
  }

  private function RetriveData(){
      $this->keeperList = array();
      //Tenemos que tener el file creado
      if(file_exists('Data/Keepers.json')){
          $keeperFile = file_get_contents(ROOT.'Data/Keepers.json');
          // Si el file tiene datos hace un decode de la info y la guarda en el arreglo, sino devuelve un arreglo vacio.
          $keeperFileDecode = ($keeperFile) ? json_decode($keeperFile, true) : array();

          foreach($keeperFileDecode as $KeeperDecode){
              $keeper = new Keeper();
              $keeper->setKeeperId($KeeperDecode["keeperId"]);
              $keeper->setLastName($KeeperDecode["lastName"]);
              $keeper->setfirstName($KeeperDecode["firstName"]);
              $keeper->setCellPhone($KeeperDecode["cellPhone"]);
              $keeper->setbirthDate($KeeperDecode["birthDate"]);
              $keeper->setEmail($KeeperDecode["email"]);
              $keeper->setPassword($KeeperDecode["password"]);
              $keeper->setFirstAvailabilityDays($KeeperDecode["firstAvailabilityDays"]);
              $keeper->setLastAvailabilityDays($KeeperDecode["lastAvailabilityDays"]);
              $keeper->setAnimalSize($KeeperDecode["animalSize"]);
              $keeper->setPrice($KeeperDecode["price"]);
              $keeper->setPoints($KeeperDecode["points"]);
              $keeper->setCBU($KeeperDecode["cbu"]);
              $keeper->setAnswerRecovery($KeeperDecode["answerRecovery"]);
              $keeper->setQuestionRecovery($KeeperDecode["questionRecovery"]);
              
              array_push($this->keeperList, $keeper);
          }
      }else{
        echo '<div class="alert alert-danger">The keepers file doesnt exists!</div>';
      }
  }
  public function searchKeeperByEmail($email){
    $this->RetriveData();
        foreach($this->keeperList as $value){ /// Buscamos dentro del arreglo de keeper
            if($value->getEmail() == $email){ /// Si el correo es el mismo, entonces devolvemos el keeper, sino
                    return $value;
            }
        }
        return null;
  }

  public function searchKeeperByID($id){
    $this->RetriveData();
        foreach($this->keeperList as $value){ /// Buscamos dentro del arreglo de keeper
            if($value->getKeeperId() == $id){ /// Si el id es el mismo, entonces devolvemos el keeper, sino
                    return $value;
            }
        }
        return null;
  }

  public function searchKeeperToLogin($email,$password){
    $newKeeper =$this->searchKeeperByEmail($email);
    if($newKeeper){
        if($newKeeper->getPassword()==$password){
            return $newKeeper;
        }
    }else{
        return null;
    }
}
  public function changeAvailabilityDays($id,$value1, $value2){
    $newValues = array();
    $value = $this->searchKeeperByID($id);
    if($value1<$value2 && isset($value)){
       $value->setFirstAvailabilityDays($value1);
        $value->setLastAvailabilityDays($value2);
        $this->SaveData();
        return true;
    }else{
        echo '<div class="alert alert-danger">The date first date cant be less that the second date</div>';
        return false;
    }
  }
  public function getKeeperByDisponibility($date1,$date2){
    $keeperList = array();
    $keeperList = $this->getAllKeeper();
    if($keeperList){
    $keeperListDisponibility= array();
    foreach($keeperList as $value){
        if($value->getFirstAvailabilityDays()<=$date1 && $value->getLastAvailabilityDays()>=$date1){
            if($value->getFirstAvailabilityDays()<=$date2 && $value->getLastAvailabilityDays()>=$date2){
                array_push($keeperListDisponibility,$value);
            }
        }
    }
}else{
    echo '<div class="alert alert-danger">There are no keepers </div>';
    return array();
}
    return $keeperListDisponibility;
}

    public function getLastID(){
    $this->RetriveData();
    if($this->keeperList != NULL){
        $keeper = end($this->keeperList);
        return $keeper->getKeeperID() + 1;
    }
    else{
        return 1;
    }
} 

    public function updateLastNameKeeper($newName,$emailUser){
    $keeper = $this->searchKeeperByEmail($emailUser);
    $keeper->setLastName($newName);
    $this->SaveData();
    return $keeper;
}

    public function updateFirstNameKeeper($newFirstName,$emailUser){
    $keeper = $this->searchKeeperByEmail($emailUser);
    $keeper->setFirstName($newFirstName);
    $this->SaveData();
    return $keeper;
}

    public function updateCellphoneKeeper($newCellphone,$emailUser){
    $keeper = $this->searchKeeperByEmail($emailUser);
    $owner->setCellPhone($newCellphone);
    $keeper->SaveData();
    return $keeper;
}
    public function updateDescriptionKeeper($newDescription,$emailUser){
    $keeper = $this->searchKeeperByEmail($emailUser);
    $keeper->setDescription($newDescription);
    $this->SaveData();
    return $keeper;
}
    public function updateAnimalSizeKeeper($newAnimalSize,$emailUser){
    $keeper = $this->searchKeeperByEmail($emailUser);
    $keeper->setAnimalSize($newAnimalSize);
    $this->SaveData();
    return $keeper;
}
    public function updatePriceKeeper($newPrice,$emailUser){
    $keeper = $this->searchKeeperByEmail($emailUser);
    $keeper->setDescription($newPrice);
    $this->SaveData();
    return $keeper;
}
    public function updateCBUKeeper($newCBU,$emailUser){
    $keeper = $this->searchKeeperByEmail($emailUser);
    $keeper->setDescription($newCBU);
    $this->SaveData();
    return $keeper;
}

    public function recoveryComparte($question,$answer,$keeper){
        if($keeper){
            if(strcmp($keeper->getQuestionRecovery(),$question) && strcmp($keeper->getAnswerRecovery(),$answer)){
                return true;
            }else{
                return false;
            }
        }
    }
    public function updatePasswordRecovery($keeper,$password){
        $keeperUpdate=$this->searchKeeperByEmail($keeper);
        $keeperUpdate->setPassword($password);
        $this->SaveData();
    }
}?>
