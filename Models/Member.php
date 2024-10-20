<?php
namespace Models;

class Member {
    private $id;
    private $group_id; //Grupo
    private $user; //Usuario (Puede ser Owner o keeper)
    private $joined_at; //Fecha de ingreso
    private $status; //1- Activo, 0- Inactivo
    private $role; //1 - Administrador 2 - Moderador 3 - Miembro

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getGroupID() {
        return $this->group_id;
    }

    public function setGroupID($group_id) {
        $this->group_id = $group_id;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getJoinedAt() {
        return $this->joined_at;
    }

    public function setJoinedAt($joined_at) {
        $this->role = $joined_at;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }
    public function toArray() {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id ? $this->group_id->toArray() : null, // Si es un objeto
            'user' => $this->user ? $this->user->toArray() : null, // Convierte a array
            'joined_at' => $this->joined_at,
            'status' => $this->status,
            'role' => $this->role ? $this->role->toArray() : null // Convierte a array
        ];
    }
}
?>