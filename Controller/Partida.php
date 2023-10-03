<?php

require_once 'Constantes.php';
require_once 'Conexion.php';

class Partida{

    private $tablero;

    public function getPartida(){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM PARTIDA";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);

        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);
        $con->desconectar();

        $rtn= [];

        while ($fila = mysqli_fetch_row($resultados)) {
               $rtn[] = $fila[0].",". $fila[1].",".$fila[2].",".$fila[3];
        }

        echo json_encode($rtn);
    }

    public function updatePartida($posicion){
        $con = new Conexion();
        $con->conectar();

        $con->desconectar();
    }
    
    public function insertPosPartida(){
        $con = new Conexion();
        $con->conectar();

        $con->desconectar();
    }

    public function getTableroInvisible($idUser){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM PARTIDA WHERE idUsuario = ? ";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);
        mysqli_stmt_bind_param($stmt, "s", $idUser);
        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);
        $con->desconectar();

        $rtn= [];

        while ($fila = mysqli_fetch_row($resultados)) {
               $rtn[] = $fila[3];
        }

        return json_encode($rtn);
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

    public function darManotazo($pos){
        /**
         * 0-> nada
         * 1 -> al lado
         * 2 -> le has
         */
        $code = 0;
        if( $this->tablero[$pos] == '*'){
            $code = 2;
        }else if($this->tablero[$pos-1] == '*' || $this->tablero[$pos+1] == '*'){
            $code = 1;
        }
        
        return $code;
    }

}