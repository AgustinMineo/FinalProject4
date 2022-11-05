<?php
namespace DAODB;
use \Exception as Exception;
use DAODB\Connect as Connect;
use DAO\IKeeperDAO as IKeeperDAO;
use Models\Keeper as Keeper;

class KeeperDAODB implements IKeeperDAO{
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