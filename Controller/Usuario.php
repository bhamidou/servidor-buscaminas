<?php

// require_once __DIR__.'/Conexion/Conexion.php';
require_once __DIR__ . '/Conexion/ConexionUsuario.php';
require_once __DIR__. '/Mail/Mail.php';

class Usuario
{
    private $id;
    private $email;
    private $pass;
    private $nombre;
    private $tablero;
    private $estadoPartida;
    private $role;

    public function __toString()
    {
        return 'id: '.$this->id.'email: '.$this->email.'pass: ' . $this->pass. 'nombre: ' . $this->nombre. 'tablero: ' . $this->tablero. 'estadoPartida: ' . $this->estadoPartida;
    }

    public function darManotazo($pos)
    {
        /**
         * 0-> nada
         * 1 -> al lado
         * 2 -> le has dado
         */
        $code = 0;
        if ($this->tablero[$pos] == '*') {
            $code = 2;
        } elseif ($this->tablero[$pos - 1] == '*' || $this->tablero[$pos + 1] == '*') {
            $code = 1;
        }

        return $code;
    }

    public function crearTablero($size, $numMinas)
    {

        $rtnVec = array_fill(0, $size, '-');

        while ($numMinas >= 0) {
            $randPos = rand(0, ($size - 1));
            $rtnVec[$randPos] = '*';
            $numMinas--;
        }

        $this->tablero = $rtnVec;
    }

    public function changePassword($newPw)
    {
        $conexionUsuario = new ConexionUsuario();

        $conexionUsuario->updatePassword(1, $newPw);

        $mail = new Mail();
        $mail->sendmail($this->email, $this->nombre, "Solicitud de nueva contraseña", $newPw);
        
    }

    public function setUser($arrValues){

        $this->setId($arrValues[1]);
        $this->setEmail($arrValues[2]);
        $this->setPass($arrValues[3]);
        $this->setnombre($arrValues[4]);
        $this->setEstadoPartida($arrValues[5]);
        $this->setTablero($arrValues[6]);
        $this->setRole($arrValues[7]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($value)
    {
        $this->pass = $value;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($value)
    {
        $this->nombre = $value;
    }

    public function getEstadoPartida()
    {
        return $this->estadoPartida;
    }

    public function setEstadoPartida($value)
    {
        $this->estadoPartida = $value;
    }
    public function getTablero() {
		return $this->tablero;
	}

	public function setTablero($value) {
		$this->tablero = $value;
	}
    public function getRole() {
		return $this->role;
	}

	public function setRole($value) {
		$this->role = $value;
	}
}
