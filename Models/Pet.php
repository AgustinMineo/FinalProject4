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
    private $ownerID; // 
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
    public function setOwnerID(Owner $ownerID){
        $this->ownerID=$ownerID;
    }

    public function getPetAge(){
        return $this->petAge;
    }
    public function setPetAge($petAge){
        $this->petAge=$petAge;
    }

    public function getPetBreedByText() {
        $breeds = [
            1 => "Beagle",
            2 => "Chihuahua",
            3 => "Bulldog",
            4 => "German Shepherd",
            5 => "Shih-tzu",
            6 => "Dogo",
            7 => "Golden Retriever",
            8 => "Fox Terrier",
            9 => "Whippet",
            10 => "Pinscher",
            11 => "Cocker",
            12 => "Shiba Inu",
            13 => "Doberman",
            14 => "Border Collie",
            15 => "Yorkshire",
            16 => "Poodle",
            17 => "Rottweiler",
            18 => "Labrador Retriever",
            19 => "Pug",
            20 => "Siberian Husky",
            21 => "Boxer",
            22 => "Dalmatian",
            23 => "Maltese",
            24 => "Saint Bernard",
            25 => "Cavalier King Charles Spaniel",
            26 => "French Bulldog",
            27 => "Great Dane",
            28 => "Basenji",
            29 => "Akita",
            30 => "Alaskan Malamute",
            31 => "Samoyed",
            32 => "Basset Hound",
            33 => "Australian Shepherd",
            34 => "Pembroke Welsh Corgi",
            35 => "Bichon Frise",
            36 => "Papillon",
            37 => "Jack Russell Terrier",
            38 => "Weimaraner",
            39 => "Bull Terrier",
            40 => "Pekingese",
            41 => "Staffordshire Bull Terrier",
            42 => "Airedale Terrier",
            43 => "Cane Corso",
            44 => "English Setter",
            45 => "Saluki",
            46 => "Italian Greyhound",
            47 => "Portuguese Water Dog",
            48 => "Tibetan Mastiff",
            49 => "Chow Chow",
            50 => "Irish Wolfhound",
            51 => "Pitbull"
        ];
    
        return $breeds[$this->breedID] ?? "La raza no se encuentra en la base de datos.";
    }
    
}
?>