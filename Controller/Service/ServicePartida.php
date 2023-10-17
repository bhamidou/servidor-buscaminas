<?php

require_once './Controller/Conexion/ConexionPartida.php';
require_once __DIR__.'../../../Constantes.php';
require_once './Controller/Service/ServiceJSON.php';

class ServicePartida {

    public function getRanking(){
        $partida = new ConexionPartida();

        $rank = $partida->getRanking();
        
        $cod = 200;
        $mesg = "OK";
        
        $serviceJSON = new ServiceJSON();
        $serviceJSON->send($cod, $mesg, $rank);
    }

    public function uncoverCasilla($idUser, $size=null, $position=null){
        $partida = new ConexionPartida();

        $tablero= $this->getTableroInvisible($idUser);
        $cod = 200;
        $mesg = "OK";
        $serviceJSON = new ServiceJSON();
        $serviceJSON->send($cod, $mesg);
    }

    public function createPartida($idUser, $size=null, $numFlags = null){
        
        $partida = new ConexionPartida();
        $serviceJSON = new ServiceJSON();
        
        /**
         * check para poder crear una partida si no la tiene el usuario
         * o si ya  ha finalizado su última partida
         */

         $checkLastResultado = $partida->getLastPartida($idUser);
            if($checkLastResultado["resultado"] != 0){
                if(empty($size) && empty($numFlags)){
                    $size = Constantes::$Casilla_size;
                    $numFlags = Constantes::$Casillas_flags;
                }
                
                //array con todas las flags
                $tab1 = FactoryPartida::createTablero($size, $numFlags);
                
                //array oculto
                $tab2 = array_fill(0, $size, "*");
                
                $partida->insertNewPartida($idUser, $tab1, $tab2);
                
                $tablero= $this->getTableroInvisible($idUser);
                
                $cod = 201;
                $mesg = "BOARD CREATED";
                $serviceJSON->send($cod, $mesg, $tablero);

            }else{
                $cod = 401;
                $mesg = "BOARD NOT CREATED";
                $extra = "YOU ALREADY HAVE A BOARD CREATED";
                $serviceJSON->send($cod, $mesg, $extra);
            }
        
    }

    public function getTableroInvisible($idUser){
        $partida = new ConexionPartida();
        return $partida->getTableroInvisible($idUser);
    }
}