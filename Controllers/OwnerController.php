<?php
 namespace Controllers;

//use DAO\OwnerDAO as OwnerDAO;
use DAODB\OwnerDAO as OwnerDAO;
use Models\Owner as Owner;

 class OwnerController
 {
    private $OwnerDAO;
    private $newOwner;
    
    public function __construct(){
        $this->OwnerDAO = new OwnerDAO();
    }

    public function goLoginOwner(){
        require_once(VIEWS_PATH."loginOwner.php");
    }
    public function goLandingOwner(){
        require_once(VIEWS_PATH."landingPage.php");
    }
    public function addOwnerView(){
        require_once(VIEWS_PATH."owner-add.php");
    }

     public function newOwner($lastName,$firstName,$cellPhone,$birthDate,$email,$password,$userImage,$userDescription){

        if($this->OwnerDAO->searchOwnerByEmail($email) == NULL){
            $newOwner = new Owner();
            $newOwner->setLastName($lastName);
            $newOwner->setfirstName($firstName);
            $newOwner->setCellPhone($cellPhone);
            $newOwner->setbirthDate($birthDate);
            $newOwner->setEmail($email);
            $newOwner->setPassword($password);
            $newOwner->setImage($userImage);
            $newOwner->setDescription($userDescription);
            $newOwner->setPetAmount('0');
            $this->OwnerDAO->AddOwner($newOwner);
            $this->goLoginOwner();
        }
        else{
            //echo '<div class="alert alert-danger">Email already exist! Please try again with another email</div>';
            $this->addOwnerView();
        }
    }
    
      public function loginOwner($email,$password){
        if($email && $password){
            try {
                //code...
                $newOwner = $this->OwnerDAO->searchOwnerToLogin($email,$password);
                if($newOwner){
                    //  session_start(); // start the session
                    $loggedUser = $newOwner;
                    $_SESSION["loggedUser"] = $loggedUser;
                    echo '<div class="alert alert-success">Login successful!</div>';
                    $this->goLandingOwner();
                    
                } else{
                    echo '<div class="alert alert-danger">Incorrect Email or Password. Please try again!</div>';
                    require_once(VIEWS_PATH."loginOwner.php");
                }
            } catch ( Exception $ex) {
                //throw $th;
                echo 'COLOCAR ALERTA';
            }
        }else{
            echo '<div class="alert alert-danger">Incorrect Email or Password. Please try again!</div>';
            require_once(VIEWS_PATH."loginOwner.php");
        }
    }
 }

?>