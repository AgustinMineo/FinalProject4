<?php
namespace DAO;
use Models\Pet as Pet;

class PetDAO implements IPetDAO{
    private $petList= array();
    
    public function AddPet($pet){
        $this->RetriveData();
        array_push($this->petList,$pet);
        $this->SaveData();
    }

    public function GetAllPet(){
        // bring all of the owners
        $this->RetriveData();
        // return the owner list
        return $this->petList;
    }

    private function SaveData(){
        // creamos un arreglo de owners
        $petArrayEncode= array();
        // recorremos la lista y guardamos la info del los owners.
        foreach($this->petList as $pet){
            $petValue = array();
            $petValue["petId"] = $pet->getPetId();
           $petValue["petName"] = $pet->getPetName();
            $petValue["petImage"] = $pet->getPetImage();
            $petValue["breed"] = $pet->getBreed();
            $petValue["petSize"] = $pet->getPetSize();
            $petValue["vaccinationPlan"] = $pet->getVaccinationPlan();
            $petValue["petDetails"] = $pet->getPetDetails();
            $petValue["petVideo"] = $pet->getPetVideo();
            $petValue["petWeight"] = $pet->getPetWeight();
            $petValue["petOwner"] = $pet->getPetOwner();
            
            array_push($petArrayEncode, $petValue);
        }
        $petFile = json_encode($petArrayEncode, JSON_PRETTY_PRINT);
        file_put_contents('Data/Pets.json',$petFile);
    }

    private function RetriveData(){
        $this->petList = array();
        //Tenemos que tener el file creado
        if(file_exists('Data/Pets.json')){
            $petFile = file_get_contents(ROOT.'Data/Pets.json');
            // Si el file tiene datos hace un decode de la info y la guarda en el arreglo, sino devuelve un arreglo vacio.
            $petFileDecode = ($petFile) ? json_decode($petFile, true) : array();

            foreach($petFileDecode as $petDecode){
                $petValue = new pet();
                $petValue->setPetName($petDecode["petName"]);
                $petValue->setPetImage($petDecode["petImage"]);
                $petValue->setBreed($petDecode["breed"]);
                $petValue->setPetSize($petDecode["petSize"]);
                $petValue->setVaccinationPlan($petDecode["vaccinationPlan"]);
                $petValue->setPetDetails($petDecode["petDetails"]);
                $petValue->setPetVideo($petDecode["petVideo"]);
                $petValue->setPetWeight($petDecode["petWeight"]);
                $petValue->setPetOwner($petDecode["petOwner"]);

                array_push($this->petList, $petValue);
            }
        }else{
            echo "The pets file doesn't exists";
        }

    }
    public function searchPets($email){
        $this->RetriveData();
        $petListSearch = array();
        foreach($this->petList as $ownerPet){
            if($ownerPet->getPetOwner() == $email){
                array_push($petListSearch,$ownerPet);
            }
        }
        return $petListSearch;
    }
    public function searchPetsBySize($email,$size){
        $petListBySize = array(); // create pet array
        $petListBySize = $this->searchPets($email); // search all pets by owner, cambiar a id
        $petListFilter =array();
        if($petListBySize){ /// if the list is not empty we filter for the size of the pet
            foreach($petListBySize as $petSize){
                if(strcmp($petSize->getPetSize(),$size)==0){
                    array_push($petListFilter,$petSize);
                }
            }
        }else{
            echo "<h1>El owner no tiene pets</h1>";
            return null;
        }
        return $petListFilter;
    }
    
    public function selectPetByID($petID,$petList){
        $petSelect = new Pet();
        foreach($petList as $pet){
            if($pet->getPetId()==$petID){
                return $pet->getPetId();
            }
        }
    } 
}
?>