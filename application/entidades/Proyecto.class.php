<?php
class Proyecto {
	private $nombre;
	
	function getNombre() {
		return $this->nombre;
	}
	
	function setNombre($newNombre) {
		$this->nombre = $newNombre;
	}
}