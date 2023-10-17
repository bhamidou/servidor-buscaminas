<?php

require __DIR__.'../../../Factories/FactoryPartida.php';
class ConexionPartida
{
    public function getPartidas()
    {
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM PARTIDA";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);

        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);
        $con->desconectar();

        $rtn = [];

        while ($fila = mysqli_fetch_row($resultados)) {
            $rtn[] = $fila[0] . "," . $fila[1] . "," . $fila[2] . "," . $fila[3];
        }

        echo json_encode($rtn);
    }

    public function getPartida($id)
    {
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM PARTIDA WHERE id = ?";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $resultados = mysqli_stmt_get_result($stmt);
        $con->desconectar();

        $rtn = [];

        while ($fila = mysqli_fetch_row($resultados)) {
            $rtn[] = $fila[0] . "," . $fila[1] . "," . $fila[2] . "," . $fila[3];
        }

        echo json_encode($rtn);
    }
    
    public function updatePartidaRendirse($idUser)
    {
        $con = new Conexion();
        $con->conectar();
        
        $consulta = "UPDATE  ". Constantes::$TABLE_partida."  SET resultado = -1 WHERE idUsuario = ?  ";
        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->bind_param("i", $idUser);

        $stmt->execute();

        $con->desconectar();
    }

    public function insertPosPartida()
    {
        $con = new Conexion();
        $con->conectar();

        $con->desconectar();
    }

    public function getTableroInvisible($idUser)
    {
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM " . Constantes::$TABLE_partida . " WHERE idUsuario = ? and resultado = 0";

        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->bind_param("i", $idUser);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $rtnUser=$resultados->fetch_array();


        $con->desconectar();

        return $rtnUser;
    }

    public function getTableroJugando($idUser)
    {
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM " . Constantes::$TABLE_partida . " WHERE idUsuario = ? and resultado = 0";

        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->bind_param("i", $idUser);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $rtnUser=$resultados->fetch_array();


        $con->desconectar();

        return $rtnUser;
    }

    public function getRanking()
    {
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT USUARIO.nombre, count(resuelto) as ganadas FROM PARTIDA inner join USUARIO ON PARTIDA.idUsuario = USUARIO.ID where resuelto=1 GROUP by idUsuario order by ganadas";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);
        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);
        $con->desconectar();

        $rtn = mysqli_fetch_row($resultados);

        return json_encode($rtn);
    }


    public function insertNewPartida($idUser, $tab1, $tab2)
    {
        
        $tabResuelto= implode(",", $tab1);
        $tabJugando= implode(",", $tab2);

        $con = new Conexion();
        $con->conectar();

        $consulta = "INSERT INTO " . Constantes::$TABLE_partida . " (idUsuario, jugando ,resuelto, resultado) VALUES (?, ?, ?, 0)";

        $stmt = Conexion::$conexion->prepare($consulta);


        $stmt->bind_param("iss", $idUser, $tabJugando, $tabResuelto);

        $stmt->execute();
        $con->desconectar();
    }

    public function getLastPartida($idUser){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT resultado FROM ". Constantes::$TABLE_partida." where idUsuario = ? order by ID desc";

        $stmt = Conexion::$conexion->prepare($consulta);

        $stmt->bind_param("i", $idUser);

        $stmt->execute();
        $resultados = $stmt->get_result();

        $rtnResultado = $resultados->fetch_array();

        $con->desconectar();
        
        return $rtnResultado;
    }
}
