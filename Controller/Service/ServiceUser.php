<?php

require_once __DIR__.'/../Mail/Mail.php';

class ServiceUser
{

    public function login($email, $password)
    {
        $user = new ConexionUsuario();

        $hasPass = md5($password);

        $checkDatos = $user->getUserByEmailAndPass($email, $hasPass);
        $rtnCheck = false;
        if(!empty($checkDatos)){
            $rtnCheck = true;
        }
        return $rtnCheck;
    }

    public function getUser($email, $password)
    {
        $user = new ConexionUsuario();
        $hasPass = md5($password);
        $rtnUser = $user->getUserByEmailAndPass($email, $hasPass);
        return $rtnUser;
    }

    public function getUserById($idUser){
        $user = new ConexionUsuario();

        $rtnUser = $user->getUserById($idUser);

        $serviceJSON = new ServiceJSON();
        $code = 200;
        $mesg = "OK";
        $serviceJSON->send($code, $mesg, $rtnUser);
    }

    public function getUsers(){
        $user = new ConexionUsuario();

        $rtnUsers = $user->getUsers();

        $serviceJSON = new ServiceJSON();
        $code = 200;
        $mesg = "OK";
        $serviceJSON->send($code, $mesg, $rtnUsers);
    }

    public function newPassword($email, $newPass=null)
    {
        if(empty($newPass)){
            $newPass = $this->generatePassword(8);
        }
        
        $hasPash = md5($newPass);

        $conexionUsuario = new ConexionUsuario();

        $conexionUsuario->updatePassword($email, $hasPash);

        $mail = new Mail();
        $mail->sendmail();

        $serviceJSON = new ServiceJSON();
        $code = 202;
        $mesg = "UPDATED PASSWORD";
        $serviceJSON->send($code, $mesg);
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
