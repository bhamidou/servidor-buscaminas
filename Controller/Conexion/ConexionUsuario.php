<?php

require __DIR__.'../Conexion.php';

class ConexionUsuario{

    public function updatePassword($id, $newPw){
        $con = new Conexion();
        $con->conectar();

        $consulta = "UPDATE USUARIO SET pass = ? WHERE id = ?  ";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);

        $hashPw = md5($newPw);

        mysqli_stmt_bind_param($stmt, "si", $hashPw, $id);

        mysqli_stmt_execute($stmt);

        $con->desconectar();

    }

    function createUser($email, $pass, $nombre){
        $con = new Conexion();
        $con->conectar();

        $consulta = "INSERT INTO USUARIO (email, pass, nombre) VALUES (?, ?, ?)";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);

        $hashPw = md5($pass);

        mysqli_stmt_bind_param($stmt, "sss", $email, $hashPw, $nombre);

        mysqli_stmt_execute($stmt);

        $con->desconectar();
    }


    // public function getUser(){

    // }

    public function getUserByEmailAndPass($email,$pass){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM USUARIO WHERE email = ? AND pass = ? ";

        $stmt = Conexion::$conexion->prepare($consulta);

        $parsePw = md5($pass);

        $stmt->bind_param("ss", $email, $parsePw);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $rtnUser =$resultados->fetch_array();

        $con->desconectar();
        
        return $rtnUser;

        // $con = new Conexion();
        // $con->conectar();

        // $consulta = "SELECT * FROM USUARIO WHERE email = ? AND pass = ? ";

        // $stmt = mysqli_prepare(Conexion::$conexion, $consulta);

        // $parsePw = md5($pass);

        // mysqli_stmt_bind_param($stmt, "ss", $email, $parsePw);

        // mysqli_stmt_execute($stmt);
        // $resultados = mysqli_stmt_get_result($stmt);

        // $rtn =  mysqli_fetch_row($resultados);
        

        // $con->desconectar();
        
        // return $rtn;

    }


}
