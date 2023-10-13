<?php


require_once './Controller/Partida.php';
require_once './Controller/Usuario.php';

header("Content-Type:application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];

$content = file_get_contents('php://input');
$decode = json_decode($content, true);

$v = explode('/', $paths);

unset($v[0]);

$cod = 200;
$mesg = "todo bien";

$user = new Usuario(1, "badrhamidou@gmail.com", $decode['pass']);
$user->changePassword("123456");

switch ($requestMethod) {
    case 'GET': {

            $conexionUsuario = new ConexionUsuario();
            $checkPersona = $conexionUsuario->checkLogin($decode['email'], $decode['pass']);
            if ($checkPersona) {
                $getUser = $conexionUsuario->getUser($decode['email'], $decode['pass']);
                $user = new Usuario($getUser[0],  $getUser[1], $getUser[2]);
                
                // $partida = new Partida();
                // $partida->crearTablero($v[1]=10, $v[2]=2);

                $cod = $partida->darManotazo($posGolpeo);
            } else {
                $cod = 401;
                $mesg = "ERROR CREDENTIALS USER";
                echo json_encode(['cod' => $cod, 'mesg' => $mesg]);
            }
        }
        break;

    case 'POST': {
            // if (!empty($v[1])) {
            //     $p = new Partida();
            //     $idUser = $v[1];
            //     $p->getTableroInvisible($idUser);

            // } else {
            //     $cod = 406;
            //     $mesg = "ERROR ID USER";
            //     echo json_encode(['cod' => $cod, 'mesg' => $mesg]);
            // }
        }

        break;

    default: {
            $cod = 405;
            $mesg = "METHOD NOT SUPPORTED YET";
            echo json_encode(['cod' => $cod, 'mesg' => $mesg]);
        }
}


header("HTTP/1.1 $cod $mesg");
