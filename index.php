<?php


require_once './Controller/Partida.php';
require_once './Controller/Usuario.php';
require_once './Controller/Service/ServiceUser.php';
require_once './Controller/Service/ServicePartida.php';

header('Content-Type: application/json; charset=utf-8');

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];

$content = file_get_contents('php://input');
$decode = json_decode($content, true);

$ruta = explode('/', $paths);

unset($v[0]);

$cod = 200;
$mesg = "todo bien";

$isAdmin = false;
$isUser = false;

$servicePartida = new ServicePartida();
$serviceUsuario = new ServiceUser();
$checkPersona = $serviceUsuario->login($decode['email'], $decode['pass']);

if ($checkPersona) {
    $getUser = $serviceUsuario->getUser($decode['email'], $decode['pass']);
    $user = new Usuario();
    $user->setUser($getUser);

    if ($user->getRole() == 1) {
        $isUser = true;
    } elseif ($user->getRole() == 0) {
        $isAdmin = true;
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
                echo json_encode(['cod' => $cod, 'mesg' => $mesg]);
            }
            break;
    }
} elseif ($isUser) {
    switch ($requestMethod) {
            /**
         * GET: /newpass
         * GET: /ranking
         * POST: /jugar
         * POST: /jugar/size/numFlags
         */
        case 'GET': {
                switch ($ruta) {
                    case 'newpass':
                        $serviceUsuario->newPassword($user->getEmail());
                        $code = 202;
                        $mesg = "UPDATED PASSWORD";
                        break;
                    case 'ranking':
                        $servicePartida->getRanking();
                        break;
                    default:
                        $code = 401;
                        break;
                }
            }
            break;

        case 'POST': {
                //
            }

            break;

        default: {
                $cod = 405;
                $mesg = "METHOD NOT SUPPORTED YET";
                echo json_encode(['cod' => $cod, 'mesg' => $mesg]);
            }
    }
} else {

    $cod = 401;
    $mesg = "ERROR USER CREDENTIALS";
    echo json_encode(['cod' => $cod, 'mesg' => $mesg]);
}
header("HTTP/1.1 $cod $mesg");
