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

//para comprobar el tipo de usuario
$isAdmin = false;
$isUser = false;

if ($checkPersona) {
    $getUser = $serviceUsuario->getUser($decode['email'], $decode['pass']);
    $user = new Usuario();
    $user->setUser($getUser);
    
    if ($user->getRole() == 1) {
        $isAdmin = true;
    } elseif ($user->getRole() == 0) {
        $isUser = true;
    }
}

if ($isAdmin) {
    switch ($requestMethod) {
            /**
         * GET: /jugar
         * GET: /ranking
         * GET: /admin/users
         * GET, PUT, DELETE: /admin/user/{id}
         * POST: /admin/user
         */
        case 'GET': {
                //switch listar, buscar y solicitar una nueva contraseña a un usuario, y jugar 
                switch ($ruta[1]) {
                    case 'jugar':
                        $servicePartida->uncoverCasilla();
                        break;
                    case 'ranking':{
                        $servicePartida->getRanking();
                    }
                    break;
                    case 'admin':{
                        switch ($ruta[2]) {
                            case 'users':
                                $serviceUsuario->getUsers();
                                break;
                            case 'user':{
                                $serviceUsuario->getUserById($ruta[3]);
                            }
                            break;
                            default:
                            $cod = 404;
                            $mesg = "ROUTE NOT FOUND";
                            $serviceJSON->send($code,$mesg);
                                break;
                        }
                    }
                    break;
                    default:
                        
                        break;
                }
            }
            break;

        case 'POST': {
                // crear usuario
            }

            break;

        case 'PUT': {
                // cambiar los datos de un usuario
            }
            break;
        case 'DELETE': {
                // eliminar un usuario
            }
            break;
        default: {
                $cod = 405;
                $mesg = "METHOD NOT SUPPORTED YET";
                $serviceJSON->send($code,$mesg);
            }
            break;
    }
} elseif ($isUser) {
    switch ($requestMethod) {
            /**
         * GET: /newpass
         * GET: /ranking
         * GET: /jugar/size/numFlags
         * POST: /jugar
         */
        case 'GET': {
                switch ($ruta[1]) {
                    case 'newpass':{
                        $serviceUsuario->newPassword($user->getEmail());
                    }
                        break;
                    case 'ranking':{
                        $servicePartida->getRanking();
                    }
                        break;
                    case 'jugar':{
                        $servicePartida->createPartida($user->getId());
                    }
                    break;
                    case 'jugar'.!empty($ruta[2]).!empty($ruta[3]):{
                        $servicePartida->createPartida($user->getId());
                    }
                    break;
                    default:
                        $code = 401;
                        $mesg = "UNAUTHORIZED ROUTE";
                        $serviceJSON->send($code,$mesg, $mesg);
                        break;
                }
            }
            break;

        case 'POST': {
                switch ($ruta) {
                    case 'jugar':
                        $servicePartida->uncoverCasilla($decode["position"]);

                        break;
                    case 'size':


                        break;
                    default:

                        break;
                }
            }

            break;

        default: {
                $cod = 405;
                $mesg = "METHOD NOT SUPPORTED YET";
                $serviceJSON->send($cod, $mesg);
            }
    }
} else {

    $cod = 401;
    $mesg = "ERROR USER CREDENTIALS";

    $serviceJSON->send($cod, $mesg);
}
