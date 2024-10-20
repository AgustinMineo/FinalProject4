<?php
 namespace Controllers;

//use DAO\OwnerDAO as OwnerDAO;
//use DAO\KeeperDAO as KeeperDAO;
use Models\Owner as Owner;
use DAODB\OwnerDAO as OwnerDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\UserDAO as UserDAO;
use DAO\MailerDAO as MailerDAO;
use Helper\FileUploader as FileUploader;
use Helper\SessionHelper as SessionHelper;

 class OwnerController
 {
    private $OwnerDAO;
    private $newOwner;
    private $newMailer;
    private $KeeperDAO;
    private $fileUploader;
    private $UserDAO;
    
    public function  __construct(){
        $this->OwnerDAO = new OwnerDAO();
        $this->UserDAO = new UserDAO();
        $this->newMailerDAO = new MailerDAO();
        $this->KeeperDAO = new KeeperDAO();
        $this->fileUploader = new FileUploader(USER_PATH);
    }

    public function goLoginOwner(){
        require_once(VIEWS_PATH."loginUser.php");
    }
    public function goLandingOwner(){
        $userRole=SessionHelper::InfoSession([1,2]);
        require_once(VIEWS_PATH."landingPage.php");
    }
    public function addOwnerView(){
        require_once(VIEWS_PATH."owner-add.php");
    }

    public function newOwner($rol,$lastName,$firstName,$cellPhone,$birthDate,$email,$password,
        $confirmPassword,$userDescription,$QuestionRecovery,$answerRecovery,$files){
        $userRole=0;
        if($rol!='0'){
            $userRole=SessionHelper::getCurrentRole();
        }

        if($lastName && $firstName && $cellPhone && $birthDate 
            && $email && $password && $confirmPassword && $userDescription 
            && $QuestionRecovery && $answerRecovery){
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
                    $newOwner->setQuestionRecovery($QuestionRecovery);
                    $newOwner->setAnswerRecovery($answerRecovery);
                    $newOwner->setPetAmount(0);
                    $newOwner->setRol(2);
                    if(intval($rol) === 1 && intval(SessionHelper::getCurrentRole()) === 1){
                        //Si el rol enviado es 1 y el rol del currentUser es 1 (Admin) pisamos el rol
                        $newOwner->setRol(1);//Asigno role admin
                    }
                    $ownerId = $this->OwnerDAO->AddOwner($newOwner);
                    if ($ownerId) {
                        if (isset($_FILES['imageOwner']) && $_FILES['imageOwner']['error'][0] === UPLOAD_ERR_OK) {
                            $formatName = function($files, $key) use ($ownerId) {
                                $extension = strtolower(pathinfo($_FILES['imageOwner']['name'][$key], PATHINFO_EXTENSION));
                                return "profile_image_{$ownerId}." . $extension;
                            };
                            $imageRoute = $this->fileUploader->uploadFiles($_FILES['imageOwner'], $ownerId, $formatName);
                            if ($imageRoute) {
                                $this->UserDAO->updateImage($imageRoute[0],$newOwner->getEmail());
                            }
                        }
                    }
                    $this->newMailerDAO->welcomeMail($lastName,$firstName,$email);

                    if($userRole===0){//Se evalua si es desde admin o desde registro owner
                        $_SESSION["loggedUser"] = $newOwner;
                        $this->goLoginOwner();
                    }else{
                        $userRole=SessionHelper::InfoSession([1]);
                        $ownerUsers = $this->OwnerDAO->GetAllOwner();
                        $keeperUsers= $this->KeeperDAO->GetAllKeeper();
                        $adminUsers = $this->OwnerDAO->GetAllAdminUser();
                        require_once(VIEWS_PATH."userListAdminView.php");
                        //return $newOwner;
                    }
                }else{
                    echo '<div class="alert alert-danger">Las contraseñas no son iguales. Intente de nuevo</div>';
                    $this->addOwnerView(); 
                } 
            }
            else{
                echo '<div class="alert alert-danger">Email already exist! Please try again with another email</div>';
                $this->addOwnerView();  
            }
        }
        }else{
            echo '<div class="alert alert-danger">All the values are required!</div>';
            $this->addOwnerView();
            }
    }

} 
?>
