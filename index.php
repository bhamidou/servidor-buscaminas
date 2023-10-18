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
if (!empty($decode['email']) && !empty($decode['pass'])){

    $checkPersona = $serviceUsuario->login($decode['email'], $decode['pass']);
}else{
    $checkPersona = false;
}

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
                                    noParameters($serviceJSON);
                                }
                                break;
                        }
                    } else {
                        notFound($serviceJSON);
                    }

                    break;
                case 'POST':{
                    //ruta /admin/user
                    if (!empty($ruta[2]) && $ruta[2] == 'user') {
                        if(!empty($decode["user"])){
                            
                            $newUser = new Usuario();
                            $newUser->setUser($decode["user"]);
                            $serviceUsuario->createUser($newUser);

                        }elseif(!empty($decode["getNewPassword"])){
                            $email = $decode["getNewPassword"]["email"];
                            $pass = $decode["getNewPassword"]["pass"];
                            $serviceUsuario->newPassword($email, $pass);
                        }else {
                            noParameters($serviceJSON);
                        }
                    } else {
                        noParameters($serviceJSON);
                    }
                    
                    
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
    //caulquier otra ruta que no sea admin, luego dentro comprobaré las rutas
    if (!empty($ruta[1])) {
        switch ($requestMethod) {
            case 'GET':
                switch ($ruta[1]) {
                    case 'jugar':{
                        if(!empty($ruta[2]) && !empty($ruta[3])){
                            $servicePartida->createPartida($user->getId(),$ruta[2], $ruta[3]);
                        }else{
                            $servicePartida->createPartida($user->getId());
                        }
                    }
                        break;
                    case 'ranking':{
                        $servicePartida->getRanking();
                    }
                        break;
                    case 'surrender':{
                        $servicePartida->surrender($user->getId());
                    }
                    break;
                    default:
                        notFound($serviceJSON);
                        break;
                }
                break;
            case 'POST':
                if($ruta[1] == 'jugar') {
                    $servicePartida->uncoverCasilla($user->getId(), $decode["pos"]);
                    }
                else{
                    notFound($serviceJSON);
                }
                break;
                default:{
                    notSupported($serviceJSON);
                }
                break;
        }
    }
    // en caso de que el usuario no tenga credenciales
} elseif(!empty($ruta[1]) && $ruta[1] == 'signup') {
      
        $user = new Usuario();
        $user->setUser($decode["user"]);
        $serviceUsuario->createUser($user);

        //en caso de que el usuario tenga credenciales
        //pero no sepa su contraseña
    }elseif(!empty($ruta[1]) && $ruta[1] == 'password'){
        
        if(!empty($decode["getNewPassword"])){
            $email = $decode["getNewPassword"]["email"];
            $pass = $decode["getNewPassword"]["pass"];
            $serviceUsuario->newPassword($email, $pass);
        }else{
            noParameters($serviceJSON);
        }

    } else{
        notFound($serviceJSON);
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