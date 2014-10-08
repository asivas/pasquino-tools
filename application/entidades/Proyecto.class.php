<?php
require_once 'SistemaFCE/entidad/Entidad.class.php';
class Proyecto extends Entidad {
	private $nombre;
	private $id;
	private $ruta;
	
	function getId() {
		return $this->id;
	}
	
	function setId($newId) {
		$this->id = $newId;
	}
	
	function getNombre() {
		return $this->nombre;
	}
	
	function setNombre($newNombre) {
		$this->nombre = $newNombre;
	}
	
	function toString() {
		return $this->nombre;
	}
	
	function getRuta() {
		return $this->ruta;
	}
	
	function setRuta($newRuta) {
		$this->ruta = $newRuta;
	}
	
	
}