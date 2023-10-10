<?php

require __DIR__.'../Conexion.php';

class ConexionUsuario{
    public function checkLogin($email,$pass){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM USUARIO WHERE email = ? AND pass = ? ";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);

        mysqli_stmt_bind_param($stmt, "ss", $email, $pass);

        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);

        $rtn = [];
        $i = 0;
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

    
}
