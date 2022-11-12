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
          //$keeperValue["points"] = $Keeper->getPoints();
          // $keeperValue["reviews"] = $Keeper->getReviews();
          $keeperValue["price"] = $Keeper->getPrice();
          
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
              //$keeper->setKeeperImg($KeeperDecode["keeperImg"]);
              $keeper->setLastName($KeeperDecode["lastName"]);
              $keeper->setfirstName($KeeperDecode["firstName"]);
              $keeper->setCellPhone($KeeperDecode["cellPhone"]);
              $keeper->setbirthDate($KeeperDecode["birthDate"]);
              $keeper->setEmail($KeeperDecode["email"]);
              $keeper->setPassword($KeeperDecode["password"]);
              $keeper->setFirstAvailabilityDays($KeeperDecode["firstAvailabilityDays"]);
              $keeper->setLastAvailabilityDays($KeeperDecode["lastAvailabilityDays"]);
              $keeper->setAnimalSize($KeeperDecode["animalSize"]);
              //$keeper->setPoints($KeeperDecode["points"]);
             // $keeper->setReviews($KeeperDecode["reviews"]);
              $keeper->setPrice($KeeperDecode["price"]);
              array_push($this->keeperList, $keeper);
          }
      }else{
          echo "The owners file doesn't exists";
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
  public function changeAvailabilityDays($email,$value1, $value2){
    $newValues = array();
    $value = $this->searchEmail($email);
    if($value1<$value2 && isset($value)){
       $value->setFirstAvailabilityDays($value1);
        $value->setLastAvailabilityDays($value2);
        $this->SaveData();
        return true;
    }else{
        echo"The date $value1 cant be less to $value2";
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
    echo "<h1>No existen keepers </h1>";
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
}
?>