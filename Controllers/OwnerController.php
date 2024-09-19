<?php
 namespace Controllers;

//use DAO\OwnerDAO as OwnerDAO;
//use DAO\KeeperDAO as KeeperDAO;
use DAODB\OwnerDAO as OwnerDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAO\MailerDAO as MailerDAO;

use Models\Owner as Owner;

use Helper\SessionHelper as SessionHelper;

 class OwnerController
 {
    private $OwnerDAO;
    private $newOwner;
    private $newMailer;
    private $KeeperDAO;
    
    public function  __construct(){
        $this->OwnerDAO = new OwnerDAO();
        $this->newMailerDAO = new MailerDAO();
        $this->KeeperDAO = new KeeperDAO();
    }

    public function goLoginOwner(){
        require_once(VIEWS_PATH."loginUser.php");
    }
    public function goLandingOwner(){
        require_once(VIEWS_PATH."landingPage.php");
    }
    public function addOwnerView(){
        require_once(VIEWS_PATH."owner-add.php");
    }

    public function goMyProfile($owner){
        require_once(VIEWS_PATH."myProfileOwner.php");
    }

    public function newOwner($lastName,$firstName,$cellPhone,$birthDate,$email,$password,$confirmPassword,$userDescription,$QuestionRecovery,$answerRecovery){ 
        if($this->OwnerDAO->searchOwnerByEmail($email) == NULL){
            if($this->KeeperDAO->searchKeeperByEmail($email) == NULL){
                if(strcmp($password,$confirmPassword) == 0){
                    $newOwner = new Owner();
                    $newOwner->setLastName($lastName);
                    $newOwner->setfirstName($firstName);
                    $newOwner->setCellPhone($cellPhone);
                    $newOwner->setbirthDate($birthDate);
                    $newOwner->setEmail($email);
                    $newOwner->setPassword($password);
                    $newOwner->setDescription($userDescription);
                    $newOwner->setPetAmount('0');
                    $newOwner->setQuestionRecovery($QuestionRecovery);
                    $newOwner->setAnswerRecovery($answerRecovery);
                    $this->OwnerDAO->AddOwner($newOwner);
                    $this->newMailerDAO->welcomeMail($lastName,$firstName,$email);
                    $this->goLandingOwner();
                }else{
                    echo '<div class="alert alert-danger">Las contraseñas no son iguales. Intente de nuevo</div>';
                    $this->addOwnerView();  

                } }
                else{
                    echo '<div class="alert alert-danger">Email already exist! Please try again with another email/div>';
                    $this->addOwnerView();  
                }
    }
}

    public function showCurrentOwner(){
        $owner=$this->OwnerDAO->searchOwnerByEmail(SessionHelper::getCurrentUser()->getEmail());
        $this->goMyProfile($owner);
    }
} 
?>
