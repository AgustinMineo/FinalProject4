<?php
namespace Models;

class Pet{
    private $petID;
    private $petName;
    private $petImage; //Imagen de la mascota
    private $breedID; // Raza de la mascota (selector)??
    private $petSize; // int
    private $petVaccinationPlan; // as image
    private $petDetails;
    private $petVideo;// video de la mascota (url por el momento)
    private $petWeight; // peso del pet
    private $ownerID; // dueño del perro (objeto)
    private $petAge; // edad del perro

    public function getPetID(){
        return $this->petID;
    }
    public function setPetID($petID){
        $this->petID= $petID;
    }

    public function getPetName(){
        return $this->petName;
    }
    public function setPetName($petName){
        $this->petName=$petName;
    }

    public function getPetImage(){
        return $this->petImage;
    }
    public function setPetImage($petImage){
        $this->petImage = $petImage;
    }
    
    public function getBreedID(){
        return $this->breedID;
    }
    public function setBreedID($breedID){
        $this->breedID= $breedID;
    }

    public function getPetSize(){
        return $this->petSize;
    }
    public function setPetSize($petSize){
        $this->petSize = $petSize;
    }

    public function getPetVaccinationPlan(){
        return $this->petVaccinationPlan;
    }
    public function setPetVaccinationPlan($petVaccinationPlan){
        $this->petVaccinationPlan=$petVaccinationPlan;
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

    public function getOwnerID(){
        return $this->ownerID;
    }
    public function setOwnerID($ownerID){
        $this->ownerID=$ownerID;
    }

    public function getPetAge(){
        return $this->petAge;
    }
    public function setPetAge($petAge){
        $this->petAge=$petAge;
    }

}
?>