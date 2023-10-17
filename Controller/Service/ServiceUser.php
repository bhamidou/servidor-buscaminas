<?php

require_once __DIR__ . '/../Mail/Mail.php';

class ServiceUser
{

    public function login($email, $password)
    {
        $user = new ConexionUsuario();

        $hasPass = md5($password);

        $checkDatos = $user->getUserByEmailAndPass($email, $hasPass);
        $rtnCheck = false;
        if (!empty($checkDatos)) {
            $rtnCheck = true;
        }
        return $rtnCheck;
    }

    public function getUser($email, $password)
    {
        $user = new ConexionUsuario();
        $hasPass = md5($password);
        return $user->getUserByEmailAndPass($email, $hasPass);

    }

    public function checkEmail($email){
        $user = new ConexionUsuario();
        return $user->getEmail($email);
    }

    public function getUserById($idUser)
    {

        $serviceJSON = new ServiceJSON();
        if (!empty($idUser)) {
            $user = new ConexionUsuario();

            $rtnUser = $user->getUserById($idUser);
            $code = 200;
            $mesg = "OK";
            $serviceJSON->send($code, $mesg, $rtnUser);
        } else {
            $code = 400;
            $mesg = "REQUIRED ID USER";
            $serviceJSON->send($code, $mesg);
        }
    }

    public function getEmailUserById($idUser)
    {
        $user = new ConexionUsuario();

        $rtnEmail = $user->getUserById($idUser);
        return $rtnEmail->getEmail();
    }

    public function getUsers()
    {
        $user = new ConexionUsuario();

        $rtnUsers = $user->getUsers();
        $serviceJSON = new ServiceJSON();
        $code = 200;
        $mesg = "OK";
        $serviceJSON->send($code, $mesg, $rtnUsers);
    }

    public function createUser($user){

        $serviceJSON = new ServiceJSON();
        if(!$this->checkEmail($user->getEmail())){
            $conexionUsuario = new ConexionUsuario();
            $conexionUsuario->createUser($user->getEmail(), $user->getPass(), $user->getNombre(), $user->getRole());
            $code = 201;
            $mesg = "OK";
            $serviceJSON->send($code, $mesg);
        }else{
            $code = 400;
            $mesg = "EMAIL IS ALREADY IN USE";
            $serviceJSON->send($code, $mesg);
        }
    }

    public function updateNombre($nombre, $email){
        
        $serviceJSON = new ServiceJSON();
        $conexionUsuario = new ConexionUsuario();

        $conexionUsuario->updateNombre($nombre, $email);

        $code = 200;
        $mesg = "OK";
        $serviceJSON->send($code, $mesg);
    }
    
    public function updateRole($role, $email){
        
        $serviceJSON = new ServiceJSON();
        $conexionUsuario = new ConexionUsuario();

        $conexionUsuario->updateRole($role, $email);

        $code = 200;
        $mesg = "OK";
        $serviceJSON->send($code, $mesg);
    }



    public function deleteUser($idUser){
        $serviceJSON = new ServiceJSON();
        if($this->checkId($idUser)){
            $conexionUsuario = new ConexionUsuario();
            $conexionUsuario->deleteUser($idUser);
            $code = 201;
            $mesg = "OK";
            $serviceJSON->send($code, $mesg);
        }else{
            $code = 404;
            $mesg = "USER DON'T EXIST";
            $serviceJSON->send($code, $mesg);
        }
    }

    public function checkId($idUser){
        $userConexion = new ConexionUsuario();
        $user = $userConexion->getUserById($idUser);
        return $user->getId();
    }

    public function newPassword($email, $newPass = null)
    {
        $serviceJSON = new ServiceJSON();
        if (!empty($email)) {
            if (empty($newPass)) {
                $newPass = $this->generatePassword(8);
            }
            $hasPash = md5($newPass);

            $conexionUsuario = new ConexionUsuario();

            $conexionUsuario->updatePassword($email, $hasPash);

            $mail = new Mail();
            $mail->sendmail();

            $code = 202;
            $mesg = "UPDATED PASSWORD";
            $serviceJSON->send($code, $mesg, $newPass);
        } else {
            $code = 402;
            $mesg = "EMAIL REQUIRED";
            $serviceJSON->send($code, $mesg);
        }
    }


    //función sacada de internet para generar contraseñas al azar
    public function generatePassword($length)
    {
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < $length; $i++) {
            $key .= substr($pattern, mt_rand(0, $max), 1);
        }
        return $key;
    }
}
