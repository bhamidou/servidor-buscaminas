<?php

require __DIR__.'../Conexion.php';

class ConexionUsuario{

    public function updatePassword($email, $newPw){
        $con = new Conexion();
        $con->conectar();
        
        $consulta = "UPDATE  ". Constantes::$TABLE_usuario."  SET pass = ? WHERE email = ?  ";
        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->bind_param("ss", $email, $email, $newPw);

        $stmt->execute();

        $con->desconectar();

    }

    function createUser($email, $pass, $nombre){
        $con = new Conexion();
        $con->conectar();

        $consulta = "INSERT INTO ". Constantes::$TABLE_usuario." (email, pass, nombre) VALUES (?, ?, ?)";

        $stmt = Conexion::$conexion->prepare($consulta);


        $stmt->bind_param("sss", $email, $pass, $nombre);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $rtnUser =$resultados->fetch_array();

        $con->desconectar();
        
        return $rtnUser;
    }


    public function getUserById($id){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM ". Constantes::$TABLE_usuario." WHERE id = ?";

        $stmt = Conexion::$conexion->prepare($consulta);


        $stmt->bind_param("i", $id);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $rtnUser =$resultados->fetch_array();

        $con->desconectar();
        
        return $rtnUser;
    }

    public function getUserByEmailAndPass($email,$pass){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM ". Constantes::$TABLE_usuario." WHERE email = ? and pass = ?";

        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->bind_param("ss", $email, $pass);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $rtnUser =$resultados->fetch_array();

        $con->desconectar();
        
        return $rtnUser;

    }


}
