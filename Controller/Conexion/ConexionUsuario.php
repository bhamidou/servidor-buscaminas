<?php

require __DIR__.'../Conexion.php';

class ConexionUsuario{

    public function updatePassword($email, $newPw){
        $con = new Conexion();
        $con->conectar();
        
        $consulta = "UPDATE  ". Constantes::$TABLE_usuario."  SET pass = ? WHERE email = ?  ";
        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->bind_param("ss", $newPw, $email);

        $stmt->execute();

        $con->desconectar();

    }

    function createUser($email, $pass, $nombre,$role){
        $con = new Conexion();
        $con->conectar();

        $consulta = "INSERT INTO ". Constantes::$TABLE_usuario." (email, pass, nombre, rol, partidasJugadas, partidasGanadas ) VALUES (?, ?, ?, ?, 0, 0)";

        $stmt = Conexion::$conexion->prepare($consulta);


        $stmt->bind_param("ssss", $email, $pass, $nombre, $role);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $rtnUser =$resultados->fetch_array();

        $con->desconectar();
        
        return $rtnUser;
    }

    public function deleteUser($idUser){
        $con = new Conexion();
        $con->conectar();

        $consulta = "DELETE FROM ". Constantes::$TABLE_usuario." where ID = ?";

        $stmt = Conexion::$conexion->prepare($consulta);


        $stmt->bind_param("s", $idUser);

        $stmt->execute();

        $con->desconectar();
        
    }


    public function getUserById($id){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM ". Constantes::$TABLE_usuario." WHERE id = ?";

        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->bind_param("i", $id);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $arr =$resultados->fetch_array();

        $rtnUser = new Usuario;
        if(!empty($arr)){
            $rtnUser->setUser($arr);
        }

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

    public function getUsers(){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM USUARIO";
        // . Constantes::$TABLE_usuario;

        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->execute();
        $resultados = $stmt->get_result();
        while($v =$resultados->fetch_array()){
            $arr[] = $v;
        }

        $rtnUsers = [];

        foreach($arr as $key){
            $user = new Usuario();
            $user->setUser($key);
            array_push($rtnUsers, $user);
        }

        $con->desconectar();
        
        return $rtnUsers;
    }

    public function getEmail($email){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM ". Constantes::$TABLE_usuario." WHERE email = ?";

        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->bind_param("s", $email);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $email =$resultados->fetch_array();

        $con->desconectar();
        
        return $email;

    }
}
