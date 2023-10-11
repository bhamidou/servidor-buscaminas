<?php

require __DIR__.'/Controller/Conexion.php';
class ConexionPartida{
    public function getPartidas(){
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

    public function getPartida($id){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM PARTIDA WHERE id = ?";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);
        mysqli_stmt_bind_param($stmt, "i", $id);
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





}