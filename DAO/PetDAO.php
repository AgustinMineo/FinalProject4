<?php
namespace DAO;
use Models\Pet as Pet;
use Helper\SessionHelper as SessionHelper;

class PetDAO implements IPetDAO{
    private $petList= array();

    public function AddPet($pet){
        $this->RetriveData();
        $pet->setPetID($this->getLastID());
        array_push($this->petList,$pet);
        $this->SaveData();
        return true;
    }

    public function GetAllPet(){
        // bring all of the owners
        $this->RetriveData();
        // return the owner list
        return $this->petList;
    }

    private function SaveData(){
        // creamos un arreglo de pets
        $petArrayEncode= array();
        // recorremos la lista y guardamos la info del los pets.
        foreach($this->petList as $pet){
            $petValue = array();
            $petValue["petID"] = $pet->getPetID();
            $petValue["petName"] = $pet->getPetName();
            $petValue["petImage"] = $pet->getPetImage();
            $petValue["breedID"] = $pet->getBreedID();
            $petValue["petSize"] = $pet->getPetSize();
            $petValue["petVaccinationPlan"] = $pet->getPetVaccinationPlan();
            $petValue["petDetails"] = $pet->getPetDetails();
            $petValue["petVideo"] = $pet->getPetVideo();
            $petValue["petWeight"] = $pet->getPetWeight();
            $petValue["ownerID"] = $pet->getOwnerID();
            $petValue["petAge"] = $pet->getPetAge();

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
                $petValue->setPetID($petDecode["petID"]);
                $petValue->setPetName($petDecode["petName"]);
                $petValue->setPetImage($petDecode["petImage"]);
                $petValue->setBreedID($petDecode["breedID"]);
                $petValue->setPetSize($petDecode["petSize"]);
                $petValue->setPetVaccinationPlan($petDecode["petVaccinationPlan"]);
                $petValue->setPetDetails($petDecode["petDetails"]);
                $petValue->setPetVideo($petDecode["petVideo"]);
                $petValue->setPetWeight($petDecode["petWeight"]);
                $petValue->setOwnerID($petDecode["ownerID"]);
                $petValue->setPetAge($petDecode["petAge"]);

                array_push($this->petList, $petValue);
            }
        }else{
            echo "<div class='alert alert-danger'>The pets file doesn't exists</div>";
        }

    }
    public function searchPets($id){
        $this->RetriveData();
        $petListSearch = array();
        foreach($this->petList as $ownerPet){
            if($ownerPet->getOwnerID() == $id){
                $ownerPet->setPetAge($this->getAgeOfPet($ownerPet->getPetAge()));
                array_push($petListSearch,$ownerPet);
            }
        }
        if($petListSearch){
            return $petListSearch;
            }else{
                return array();
            echo "<div class='alert alert-danger'>Usted no tiene pets disponibles</div>";
        }
    }
    public function searchPetsBySize($email,$size){
        $petListBySize = array(); // create pet array
        $petListBySize = $this->searchPets($email); // search all pets by owner, cambiar a id
        if($petListBySize){
            $petListFilter =array();
            if($petListBySize){ /// if the list is not empty we filter for the size of the pet
                foreach($petListBySize as $petSize){
                    if(strcmp($petSize->getPetSize(),$size)==0){
                        array_push($petListFilter,$petSize);
                    }
                }
            }else{
                echo "<div class='alert alert-danger'>No tiene mascotas que concuerden con el tamaño</div>";
                return array();
            }
            return $petListFilter;
        }else{
            return array();
        }
    }
    
    public function selectPetByID($petID,$petList){
        $petSelect = new Pet();
        foreach($petList as $pet){
            if($pet->getPetID()==$petID){
                return $pet->getPetID();
            }
        }
    }
    public function getLastID(){
        $this->RetriveData();
        if($this->petList != NULL){
            $pet = end($this->petList);
            return $pet->getPetID() + 1;
        }
        else{
            return 1;
        }
    }

    public function getAgeOfPet($age){
        $edad = 0;
        if($age){
            $fecha_nac = $age;
            $fecha_nac = strtotime($fecha_nac);
            $edad = date('Y', $fecha_nac);
            if (($mes = (date('m') - date('m', $fecha_nac))) < 0) {
                $edad++;
            } elseif ($mes == 0 && date('d') - date('d', $fecha_nac) < 0) {
                $edad++;
            }
            return date('Y') - $edad;
        }
    }

    public function searchPetList(){
        $petListSearch= array();
        if(SessionHelper::getCurrentUser()){
            $petListSearch = $this->searchPets(SessionHelper::getCurrentOwnerID()); // Buscamos la lista de pets que tenga el cliente por correo. (Cambiar a objeto)
            return $petListSearch;
        }
    }
    public function searchPet($petSearch){
        $this->RetriveData();
        foreach($this->petList as $pet){
            if($pet->getPetID() == $petSearch){
                return $pet;
            }
        }
    }
}
?>