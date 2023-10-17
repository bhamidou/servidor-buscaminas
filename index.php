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
    $user->setUser($getUser);
} else {
    $code = 401;
    $msg = "ERROR USER CREDENTIALS";
    return $serviceJSON->send($code, $msg);
}

if ($user->getRole() == 1) {
    switch ($requestMethod) {
        case 'GET':
            if ($ruta[1] == 'admin') {
                if (!empty($ruta[2])){
                    switch ($ruta[2]) {
                        case 'users':
                            return $serviceUsuario->getUsers();
                            break;
                            case 'user':
                                return $serviceUsuario->getUserById($ruta[3]);
                                break;
                            }
                }else{
                    notFound($serviceJSON);
                }

                break;
            }

            break;
        case 'POST':
            switch ($ruta[1]) {
                case 'user':
                    $email = $decode["arrUser"]["email"];
                    $newUser = new Usuario();
                    $newUser->setUser($decode["arrUser"]);
                    $serviceUsuario->createUser($newUser);
                    // no terminado
                    return null;
                    break;
            }
            break;
        case 'PUT':
            // Cambiar los datos de un usuario
            break;
        case 'DELETE':
            // Eliminar un usuario
            break;
    }
}

if($user->getRole() >= 0){
switch ($requestMethod) {
    case 'GET':
        switch ($ruta[1]) {
            case 'jugar':
                $servicePartida->createPartida($user->getId());
                break;
            case 'ranking':
                $servicePartida->getRanking();
                break;
            default:
                notFound($serviceJSON);
        }
        break;
    case 'POST':
        switch ($ruta[1]) {
            case 'jugar':
                return $servicePartida->uncoverCasilla($user->getId());
                break;
            default:
                return notFound($serviceJSON);
        }
        break;
    default:
        return notSuported($serviceJSON);
    }
}

function notFound($serviceJSON)
{
    $code = 404;
    $msg = "ROUTE NOT FOUND";
    $serviceJSON->send($code, $msg);
}

function notSuported($serviceJSON)
{
    $code = 405;
    $msg = "METHOD NOT SUPPORTED YET";
    $serviceJSON->send($code, $msg);
}
