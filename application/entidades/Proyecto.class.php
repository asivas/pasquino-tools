<?php
require_once 'SistemaFCE/entidad/Entidad.class.php';

class DbConfig {
	
	public $motor;
	public $host;
	public $usuario;
	public $password;
	public $nombreBase;
	public $puerto;
	
}

class Proyecto extends Entidad {
	private $nombre;
	private $id;
	private $ruta;
	
	private $dbConfig;
	
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
		return str_replace(DIRECTORY_SEPARATOR, '/', $this->ruta);
	}
	
	function setRuta($newRuta) {
		$this->ruta = $newRuta;
	}
	
	/**
	 * 
	 * @param DbConfig $newDbConfig
	 */
	function setDbConfig($newDbConfig) {
		$this->dbConfig = $newDbConfig;
	}
	
	/**
	 * @return DbConfig
	 */
	function getDbConfig() {
		return $this->dbConfig;
	}
	
}