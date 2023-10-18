<?php

require_once './Controller/Partida.php';
require_once './Controller/Usuario.php';
require_once './Controller/Service/ServiceUser.php';
require_once './Controller/Service/ServicePartida.php';
require_once './Controller/Service/ServiceJSON.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];

$content = file_get_contents('php://input');
$decode = json_decode($content, true);

$ruta = explode('/', $paths);

unset($ruta[0]);

//servicios que se van a ir llamando
$servicePartida = new ServicePartida();
$serviceUsuario = new ServiceUser();
$serviceJSON = new ServiceJSON();

$checkPersona = $serviceUsuario->login($decode['email'], $decode['pass']);

if ($checkPersona) {
    $getUser = $serviceUsuario->getUser($decode['email'], $decode['pass']);
    $user = new Usuario();
    $user->setArrUser($getUser);

    if ($user->getRole() == 1) {
        if ($ruta[1] == 'admin') {
            switch ($requestMethod) {
                case 'GET':
                    if (!empty($ruta[2])) {
                        switch ($ruta[2]) {
                            case 'users':
                                $serviceUsuario->getUsers();
                                break;
                            case 'user':
                                if (!empty($ruta[3])) {
                                    $serviceUsuario->getUserById($ruta[3]);
                                } else {
                                    $code = 402;
                                    $msg = "PARAMETER REQUIRED";
                                    $serviceJSON->send($code, $msg);
                                }
                                break;
                        }
                    } else {
                        notFound($serviceJSON);
                    }

                    break;
                case 'POST':
                    if (!empty($ruta[2]) && $ruta[2] == 'user') {
                        if(!empty($decode["user"])){

                            $newUser = new Usuario();
                            $newUser->setUser($decode["user"]);
                            $serviceUsuario->createUser($newUser);
                        }else {
                            noParameters($serviceJSON);
                        }
                    } else {
                        noParameters($serviceJSON);
                    }
                    if(!empty($decode["getNewPassword"])){
                        $email = $decode["getNewPassword"]["email"];
                        $pass = $decode["getNewPassword"]["pass"];
                        $serviceUsuario->newPassword($email, $pass);
                    }
                    break;
                case 'PUT':{
                    if(!empty($decode["update"])){
                        //checkEmpty de $decode sobre nombre y rol
                        $serviceUsuario->updateNombre($decode["update"]["nombre"], $decode["update"]["email"]);
                        $serviceUsuario->updateRole($decode["update"]["rol"], $decode["update"]["email"]);
                    }
                }
                    break;
                case 'DELETE': {
                        if (!empty($ruta[2]) && !empty($ruta[3]) && $ruta[2] == 'user') {
                            $serviceUsuario->deleteUser($ruta[3]);
                        }
                    }
                    break;
                default: {
                        notFound($serviceJSON);
                    }
                    break;
            }
        }
    }
    if ($user->getRole() >= 0 && $ruta[1] == 'jugar' || $ruta[1] == 'ranking') {
        switch ($requestMethod) {
            case 'GET':
                switch ($ruta[1]) {
                    case 'jugar':{
                        $servicePartida->createPartida($user->getId());
                    }
                        break;
                    case 'ranking':{
                        $servicePartida->getRanking();
                    }
                        break;
                    case 'surrender':{
                        $servicePartida->surrender($idUser);
                    }
                    default:
                        notFound($serviceJSON);
                }
                break;
            case 'POST':
                switch ($ruta[1]) {
                    case 'jugar':
                        $servicePartida->uncoverCasilla($user->getId());
                        break;
                    default:{
                            notFound($serviceJSON);
                    }
                    break;
                }
                break;
            default:
                notSupported($serviceJSON);
        }
    }
} else {
    // en caso de que el usuario no tenga credenciales
    if (!empty($ruta[1]) && $ruta[1] == 'signup') {
        $serviceUsuario->createUser($decode["user"]);

        //en caso de que el usuario tenga credenciales 
        //pero no sepa su contraseña
    }elseif(!empty($ruta[1]) && $ruta[1] == 'password'){
        if(!empty($decode["getNewPassword"])){
            $email = $decode["getNewPassword"]["email"];
            $pass = $decode["getNewPassword"]["pass"];
            $serviceUsuario->newPassword($email, $pass);
        }
    } else{
        $code = 401;
        $msg = "ERROR USER CREDENTIALS";
        $serviceJSON->send($code, $msg);
    }
}


function notFound($serviceJSON)
{
    $code = 404;
    $msg = "ROUTE NOT FOUND";
    $serviceJSON->send($code, $msg);
}

function notSupported($serviceJSON)
{
    $code = 405;
    $msg = "METHOD NOT SUPPORTED YET";
    $serviceJSON->send($code, $msg);
}

function noParameters($serviceJSON){
    $code = 402;
    $msg = "PARAMETER REQUIRED";
    $serviceJSON->send($code, $msg);
}