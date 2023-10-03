<?php


require_once './Controller/Partida.php';
require_once './Controller/Persona.php';

header("Content-Type:application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];

$content = file_get_contents('php://input');
$decode = json_decode($content, true);

$v = explode('/', $paths);

unset($v[0]);

$cod = 200;
$mesg = "todo bien";


switch ($requestMethod) {
    case 'GET': {
            $persona = new Persona();
            
            $checkPersona = $persona->checkLogin($decode['email'],$decode['pass']);

            if ($checkPersona) {
                $partida = new Partida();

                if(!empty($v[1] && !empty($v[2]))){
                    
                    $partida->crearTablero($v[1],$v[2]);
                    
                    if(!empty($decode['pos']))
                    $posGolpeo = $decode['pos'];
                    else   $posGolpeo= 0;

                    $cod = $partida->darManotazo($posGolpeo);
                    
                    switch ($cod) {
                        case 0:
                        case 1:
                            $partida->getTableroInvisible($idUser);
                            break;
                            
                        case 2:
                            $partida->getTabl($idUser);
                            break;
                    }
                }

            } else {
                $cod = 206;
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

    case 'DELETE' :{
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

    case 'UPDATE': {
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
