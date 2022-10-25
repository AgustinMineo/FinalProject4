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
          $keeperValue["availabilityDays"] = $Keeper->getAvailabilityDays();
          $keeperValue["animalSize"] = $Keeper->getAnimalSize();
         // $keeperValue["points"] = $Keeper->getPoints();
         // $keeperValue["reviews"] = $Keeper->getReviews();
          
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
              $keeper->setAvailabilityDays($KeeperDecode["availabilityDays"]);
              $keeper->setAnimalSize($KeeperDecode["animalSize"]);
           //   $keeper->setPoints($KeeperDecode["points"]);
             // $keeper->setReviews($KeeperDecode["reviews"]);
              array_push($this->keeperList, $keeper);
          }
      }else{
          echo "The owners file doesn't exists";
      }
  }
  public function searchEmail($email){
    $this->RetriveData();
        foreach($this->keeperList as $value){ /// Buscamos dentro del arreglo de keeper
            if($value->getEmail()== $email){ /// Si el correo es el mismo, entonces devolvemos el keeper, sino
                    return $value;
            }
        }
        return null;
  }
}
?>