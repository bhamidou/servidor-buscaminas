<?php

require __DIR__.'../Conexion.php';

class ConexionUsuario{
    public function checkLogin($email,$pass){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM USUARIO WHERE email = ? AND pass = ? ";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);

        $parsePw = md5($pass);

        mysqli_stmt_bind_param($stmt, "ss", $email, $parsePw);

        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);

        $rtn = [];

        while ($fila = mysqli_fetch_row($resultados)) {
            $rtn[] = [$fila[1],$fila[2]];
        }
        
        $check = false;
        if($email == !empty($rtn[0][0]) && $pass == !empty($rtn[0][1])){
            $check = true;
        }

        $con->desconectar();
        
        return $check;
    }

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

    public function getUser($email,$pass){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM USUARIO WHERE email = ? AND pass = ? ";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);

        $parsePw = md5($pass);

        mysqli_stmt_bind_param($stmt, "ss", $email, $parsePw);

        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);

        $rtnUser = mysqli_fetch_row($resultados);

        $con->desconectar();
        
        return $rtnUser;
    }


}
