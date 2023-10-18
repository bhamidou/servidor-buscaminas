<?php

require_once './Controller/Conexion/ConexionPartida.php';
require_once __DIR__ . '../../../Constantes.php';
require_once './Controller/Service/ServiceJSON.php';
require_once __DIR__.'/ServiceUser.php';

class ServicePartida
{

    public function getRanking()
    {
        $partida = new ConexionPartida();

        $rank = $partida->getRanking();

        $rtnRank["nombre"] = $rank[0];
        $rtnRank["patidasGanadas"] = $rank[1];

        $cod = 200;
        $mesg = "OK";

        $serviceJSON = new ServiceJSON();
        $serviceJSON->send($cod, $mesg, $rtnRank );
    }

    public function uncoverCasilla($idUser, $posicion)
    {
        $partida = new ConexionPartida();
        $serviceJSON = new ServiceJSON();
        $conexionUsuario = new ServiceUser();

        $checkLastResultado = $partida->getLastPartida($idUser);
        if ($checkLastResultado->getResultado() == 0) {

            $tab = $partida->getPartidaByUserId($idUser);
            $tablero = $tab->getTFinal();
            $board = explode(",", $tablero);
            $invisible = explode(",", $tab->getTVacio());
            $countBoard = $this->countUncovered($board);
            $countInvisible = $this->countUncovered($invisible);

            if ($countInvisible != $countBoard) {
                $additionalMsg = "BOX SUCCESSFULLY UNCOVERED";

                $normalizePosicion = $posicion - 1;
                if($this->checkRange($normalizePosicion, count($board), 0)){
                $numFlags = 0;
                $cod = 200;
                $msg = "OK";

                if ($board[$normalizePosicion] > 0) {
                    $cod = 404;
                    $msg = "FOUND THE FLAG, YOU LOST";

                    $additionalMsg = implode(",", $board);

                    $partida->updatePartidaRendirse($idUser);
                    $numFlags = strval($board[$normalizePosicion]);

                } elseif($normalizePosicion < count($board)-1 && $normalizePosicion > 0 && $board[$normalizePosicion + 1] > 2 && $board[$normalizePosicion - 1]>2) {
                    $additionalMsg = "BE CAREFUL WITH THE RIGHT AND LEFT";
                    $numFlags = 2;
                } elseif ($normalizePosicion < count($board)-1 && $board[$normalizePosicion + 1] >2) {
                    $additionalMsg = "BE CAREFUL WITH THE RIGHT";
                    $numFlags = 1;

                } elseif ($normalizePosicion> 0 && $board[$normalizePosicion - 1]>2) {
                    $additionalMsg = "BE CAREFUL WITH THE LEFT";
                    $numFlags = 1;

                } else {
                    $additionalMsg = "BOX SUCCESSFULLY UNCOVERED";
                }

                
                $invisible[$normalizePosicion] = $numFlags;

                $rtnInvisible = implode(",", $invisible);
                $board[$normalizePosicion] = $numFlags;
                $rtnBoard = implode(",", $board);


                $partida->setPosicionJugando($idUser, $rtnInvisible);
                $partida->setPosicionResuelto($idUser, $rtnBoard);

                $serviceJSON->send($cod, $msg, [$additionalMsg,  $rtnInvisible]);
                }else{
                    $cod = 400;
                    $mesg = "POSITION OUT OF RANGE";
                    $serviceJSON->send($cod, $mesg);
                }
            } else {
                $partida->setWin($idUser);
                $conexionUsuario->setCountGanadaPartida($idUser);
                $cod = 200;
                $mesg = "YOU HAVE MARKED ALL THE FLAGS, YOU HAVE WON";
                $serviceJSON->send($cod, $mesg);
            }
        } else {
            $cod = 404;
            $mesg = "NOT FOUND BOARD FOR PLAYING";
            $serviceJSON->send($cod, $mesg);
        }
    }

    public function createPartida($idUser, $size = null, $numFlags = null)
    {

        $partida = new ConexionPartida();
        $serviceJSON = new ServiceJSON();
        $conexionUsuario = new ServiceUser();

        /**
         * check para poder crear una partida si no la tiene el usuario
         * o si ya  ha finalizado su última partida
         */

        $checkLastResultado = $partida->getLastPartida($idUser);

        if ($checkLastResultado->getResultado() != 0) {
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
            $conexionUsuario->setCountJugadaPartida($idUser);
            $cod = 201;
            $mesg = "BOARD CREATED";
            $serviceJSON->send($cod, $mesg, ["board" => $tablero["jugando"]]);
        } else {
            $cod = 401;
            $mesg = "BOARD NOT CREATED";
            $extra = "YOU ALREADY HAVE A BOARD CREATED";
            $serviceJSON->send($cod, $mesg, ["alert" => $extra, "board" => $checkLastResultado->getTVacio()]);
        }
    }

    public function surrender($idUser)
    {
        $partida = new ConexionPartida();
        $serviceJSON = new ServiceJSON();
        $statusPartida = $partida->getLastPartida($idUser);
        $resultadoPartida = $statusPartida->getResultado();

        $additionalMsg = null;
        if ($resultadoPartida == 0) {
            $code = 201;
            $msg = "OK";

            $partida->updatePartidaRendirse($idUser);
            $tablero = $partida->getTableroResuelto($idUser);
            $additionalMsg = $tablero["resuelto"];
        } elseif ($resultadoPartida == 1 || $resultadoPartida  == -1) {
            $code = 400;
            $msg = "THE LAST BOARD IS ALREADY FINISHED";
        }

        $serviceJSON->send($code, $msg, $additionalMsg);
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
            if ($value == 1 || $value == 2 || $value == 0) {
                $rtnNumFlags++;
            }
        }
        return $rtnNumFlags;
    }

    

    public function checkRange($normalizePosicion, $max,$min){

        return $normalizePosicion>=$min && $normalizePosicion<=$max;
        
    }
}
