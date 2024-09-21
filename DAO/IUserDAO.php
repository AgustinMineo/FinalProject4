<?php
namespace DAO;
use Models\User as User;


interface IUserDAO{
    function updateFirstName($name,$emailUser);
    function updateLastName($lastName,$emailUser);
    function updateCellphone($cellphone,$emailUser);
    function updateDescription($description,$emailUser);
    //function updateBirthdate($birthdate,$emailUser);
    function updatePassword($password,$emailUser);
    function updateQuestionRecovery($questionRecovery,$emailUser);
    function updateAnswerRecovery($answerRecovery,$emailUser);
}
?>