<?php
namespace Models;

class Pet(){
    private $petId;
    private $petImage; //Imagen de la mascota
    private $breed; // Raza de la mascota (selector)??
    private $petSize; // int
    private $vaccinationPlan; // as image
    private $petDetails;
    private $petVideo;// video de la mascota (url por el momento)
    private $petWeight; // peso del pet
    private $petOwner; // dueño del perro (objeto)

    public function getPetId(){
        return $this->petId;
    }
    public function setPetId($petId){
        $this->petId= $petId;
    }

    public function getPetImage(){
        return $this->petImage;
    }
    public function setPetImage($petImage){
        $this->petImage = $petImage;
    }
    
    public function getBreed(){
        return $this->breed;
    }
    public function setBreed($breed){
        $this->breed= $breed;
    }

    public function getPetSize(){
        return $this->petSize;
    }
    public function setPetSize($petSize){
        $this->petSize = $petSize;
    }

    public function getVaccinationPlan(){
        return $this->vaccinationPlan;
    }
    public function setVaccinationPlan($vaccinationPlan){
        $this->vaccinationPlan=$vaccinationPlan;
    }

    public function getPetDetails(){
        return $this->petDetails;
    }
    public function setPetDetails($petDetails){
        $this->petDetails=$petDetails;
    }

    public function getPetVideo(){
        return $this->petVideo;
    }
    public function setPetVideo($petVideo){
        $this->petVideo=$petVideo;
    }

    public function getPetWeight(){
        return $this->petWeight;
    }
    public function setPetWeight($petWeight){
        $this->petWeight=$petWeight;
    }

    public function getPetOwner(){
        return $this->petOwner;
    }
    public function setPetOwner($petOwner){
        $this->petOwner=$petOwner;
    }

}
?>