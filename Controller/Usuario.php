<?php

// require_once __DIR__.'/Conexion/Conexion.php';
require_once __DIR__ . '/Conexion/ConexionUsuario.php';
require_once __DIR__. '/Mail/Mail.php';

class Usuario
{
    public $id;
    public $email;
    public $pass;
    public $nombre;
    public $partidasJugadas;
    public $partidasGanadas;
    public $role;


    public function __toString()
    {
        return 'id: '.$this->id.'email: '.$this->email.'pass: ' . $this->pass. 'nombre: ' . $this->nombre. 'partidasJugadas: ' . $this->partidasJugadas. 'partidasGanadas: ' . $this->partidasGanadas;
    }

    
    public function setArrUser($arrValues){
        $this->setId($arrValues["ID"]);
        $this->setEmail($arrValues["email"]);
        $this->setPass($arrValues["pass"]);
        $this->setnombre($arrValues["nombre"]);
        $this->setPartidasJugadas($arrValues["partidasJugadas"]);
        $this->setPartidasGanadas($arrValues["partidasGanadas"]);
        $this->setRole($arrValues["rol"]);
    }

    public function setUser($arrValues){
        $this->setEmail($arrValues["email"]);
        $this->setPass($arrValues["pass"]);
        $this->setnombre($arrValues["nombre"]);
        $this->setRole($arrValues["rol"]);
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

    public function getRole() {
		return $this->role;
	}

	public function setRole($value) {
		$this->role = $value;
	}
    public function getPartidasJugadas() {
		return $this->partidasJugadas;
	}

	public function setPartidasJugadas($value) {
		$this->partidasJugadas = $value;
	}

	public function getPartidasGanadas() {
		return $this->partidasGanadas;
	}

	public function setPartidasGanadas($value) {
		$this->partidasGanadas = $value;
	}
}
