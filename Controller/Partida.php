<?php

class Partida {
    private $id;
    private $idUsuario;
    private $tVacio;
    private $tFinal;
    private $resultado;
    
	public function __construct($id, $idUsuario) {

		$this->id = $id;
		$this->idUsuario = $idUsuario;

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

	public function getResultad() {
		return $this->resultado;
	}

	public function setResultad($value) {
		$this->resultado = $value;
	}
}
