<?php

// require_once __DIR__.'/Conexion/Conexion.php';
require_once __DIR__.'/Conexion/ConexionUsuario.php';

class Usuario{


    private $id;
    private $email;
    private $pass;
    private $nombre;
    private $tablero;
    private $estadoPartida;

    public function __construct($id,$email, $pass) {
		$this->id = $id;
        $this->email= $email;
		$this->pass = $pass;
	}

    public function darManotazo($pos){
        /**
         * 0-> nada
         * 1 -> al lado
         * 2 -> le has dado
         */
        $code = 0;
        if( $this->tablero[$pos] == '*'){
            $code = 2;
        }elseif($this->tablero[$pos-1] == '*' || $this->tablero[$pos+1] == '*'){
            $code = 1;
        }
        
        return $code;
    }
    
    public function crearTablero($size, $numMinas){
        
        $rtnVec = array_fill(0,$size,'-');

        while($numMinas>=0){
            $randPos = rand(0,($size-1)) ;
            $rtnVec[$randPos] = '*';
            $numMinas--;
        }

        $this->tablero =$rtnVec;
    }

    public function changePassword($newPw){
        $conexionUsuario = new ConexionUsuario();

        $conexionUsuario->updatePassword(1, $newPw);
    }
    
}


