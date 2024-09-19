<?php
namespace DAO;
use Models\User as User;
use DAO\IUserDAO as IUserDAO;

interface IUserDAO{
    function editFirstName($name);
    function editLastName($lastName);
    function editCellphone($cellphone);
    function editDescription($description);
    function editBirthdate($birthdate);
    function editPassword($password);
    function editQuestionRecovery($questionRecovery);
    function editAnswerRecovery($answerRecovery);
}
?>