<?php

class Partida {
    public $id;
    public $idUsuario;
    public $tVacio;
    public $tFinal;
    public $resultado;
    
	// public function __toString()
	// {
	// 	return 'id: '.$this->$id.''..''..''..''...;
	// }

	public function setPartida($arrValues){
        $this->setIdUsuario($arrValues["idUsuario"]);
        $this->setTVacio($arrValues["jugando"]);
        $this->setTFinal($arrValues["resuelto"]);
        $this->setResultado($arrValues["resultado"]);
    }

	public function setPartida2($arrValues){
		$this->setIdUsuario($arrValues["idUsuario"]);
        $this->setTVacio($arrValues["tVacio"]);
        $this->setTFinal($arrValues["tFinal"]);
        $this->setResultado($arrValues["resultado"]);
	}
	
	public function getId() {
		return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getIdUsuario() {
		return $this->idUsuario;
	}

	public function setIdUsuario($value) {
		$this->idUsuario = $value;
	}

	public function getTVacio() {
		return $this->tVacio;
	}

	public function setTVacio($value) {
		$this->tVacio = $value;
	}

	public function getTFinal() {
		return $this->tFinal;
	}

	public function setTFinal($value) {
		$this->tFinal = $value;
	}

	public function getResultado() {
		return $this->resultado;
	}

	public function setResultado($value) {
		$this->resultado = $value;
	}
}
