<?php


require_once './Controller/Partida.php';

header("Content-Type:application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];

$v = explode('/', $paths);

unset($v[0]);

$cod = 200;
$mesg = "todo bien";

switch ($requestMethod) {
    case 'GET': {
            if (!empty($v[1])) {
                $p = new Partida();
                $idUser = $v[1];
                $p->getTableroInvisible($idUser);
            } else {
                $cod = 406;
                $mesg = "ERROR ID USER";
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
