<?php

require_once './Controller/Conexion/ConexionPartida.php';
require_once __DIR__ . '../../../Constantes.php';
require_once './Controller/Service/ServiceJSON.php';

class ServicePartida
{

    public function getRanking()
    {
        $partida = new ConexionPartida();

        $rank = $partida->getRanking();

        $cod = 200;
        $mesg = "OK";

        $serviceJSON = new ServiceJSON();
        $serviceJSON->send($cod, $mesg, $rank);
    }

    public function uncoverCasilla($idUser, $posicion)
    {
        $partida = new ConexionPartida();
        $serviceJSON = new ServiceJSON();

        $checkLastResultado = $partida->getLastPartida($idUser);
        if ($checkLastResultado["resultado"] == 0) {
            
            $tab = $partida->getPartidaByUserId($idUser);
            $tablero = $tab->getTFinal();
            $board = explode(",", $tablero);
            $invisible = explode(",", $tab->getTVacio());
            

            if ($this->countUncovered($board) !=  $this->countUncovered($invisible)) {

                $additionalMsg = "BOX SUCCESSFULLY UNCOVERED";
                $normalizePosicion = $posicion - 1;
                $numFlags = 0;

                if ($board[$normalizePosicion] > 0) {
                    $cod = 404;
                    $mesg = "FOUND THE FLAG, YOU LOST";
                    $serviceJSON->send($cod, $mesg, $board);
                    $partida->updatePartidaRendirse($idUser);
                    $numFlags = strval($board[$normalizePosicion]);
                } elseif ($board[$normalizePosicion + 1] > 0 && $board[$normalizePosicion - 1]) {
                    $cod = 200;
                    $mesg = "BE CAREFUL WITH THE RIGHT AND LEFT";
                    $serviceJSON->send($cod, $mesg, $additionalMsg);
                    $numFlags = 2;
                } elseif ($board[$normalizePosicion + 1] > 0) {
                    $cod = 200;
                    $mesg = "BE CAREFUL WITH THE RIGHT";
                    $serviceJSON->send($cod, $mesg, $additionalMsg);
                    $numFlags = 1;
                } elseif ($board[$normalizePosicion - 1]) {

                    $cod = 200;
                    $mesg = "BE CAREFUL WITH THE LEFT";
                    $serviceJSON->send($cod, $mesg, $additionalMsg);
                    $numFlags = 1;
                } else {
                    $cod = 200;
                    $mesg = "BOX SUCCESSFULLY UNCOVERED";
                    $serviceJSON->send($cod, $mesg);
                }

                $invisible[$normalizePosicion] = strlen($numFlags);
                print_r($invisible);

                $rtnInvisible = implode(",", $invisible);
                $board[$normalizePosicion] = $numFlags;
                $partida->setPosicion($idUser, $rtnInvisible);
            } else {
                $cod = 200;
                $mesg = "YOU HAVE MARKED ALL THE FLAGS, YOU HAVE WON";
                $serviceJSON->send($cod, $mesg);
            }
        } else {
            $cod = 404;
            $mesg = "NOT FOUND BOARD FOR PLAYING";
            $serviceJSON = new ServiceJSON();
            $serviceJSON->send($cod, $mesg);
        }
    }

    public function createPartida($idUser, $size = null, $numFlags = null)
    {

        $partida = new ConexionPartida();
        $serviceJSON = new ServiceJSON();

        /**
         * check para poder crear una partida si no la tiene el usuario
         * o si ya  ha finalizado su última partida
         */

        $checkLastResultado = $partida->getLastPartida($idUser);

        if ($checkLastResultado == null || $checkLastResultado["resultado"] != 0) {
            if (empty($size) && empty($numFlags)) {
                $size = Constantes::$Casilla_size;
                $numFlags = Constantes::$Casillas_flags;
            }

            //array con todas las flags
            $tab1 = FactoryPartida::createTablero($size, $numFlags);

            //array oculto
            $tab2 = array_fill(0, $size, "*");

            $partida->insertNewPartida($idUser, $tab1, $tab2);

            $tablero = $this->getTableroInvisible($idUser);

            $cod = 201;
            $mesg = "BOARD CREATED";
            $serviceJSON->send($cod, $mesg, $tablero["jugando"]);
        } else {
            $cod = 401;
            $mesg = "BOARD NOT CREATED";
            $extra = "YOU ALREADY HAVE A BOARD CREATED";
            $serviceJSON->send($cod, $mesg, $extra);
        }
    }

    public function surrender($idUser)
    {
        $partida = new ConexionPartida();
        $serviceJSON = new ServiceJSON();

        $partida->updatePartidaRendirse($idUser);
        $tablero = $partida->getTableroResuelto($idUser);

        $cod = 201;
        $mesg = "OK";
        $serviceJSON->send($cod, $mesg, $tablero["resuelto"]);
    }
    public function getTableroInvisible($idUser)
    {
        $partida = new ConexionPartida();
        return $partida->getTableroInvisible($idUser);
    }

    public function countUncovered($tableroFinal)
    {
        $rtnNumFlags = 0;
        foreach ($tableroFinal as $key => $value) {
            if ($value == 1) {
                $rtnNumFlags++;
            }
        }
        return $rtnNumFlags;
    }

    public function countFlags($tableroFinal)
    {
        $rtnNumFlags = 0;
        foreach ($tableroFinal as $key => $value) {
            if ($value == 0) {
                $rtnNumFlags++;
            }
        }
        return $rtnNumFlags;
    }
}
