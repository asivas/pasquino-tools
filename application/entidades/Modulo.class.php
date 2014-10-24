<?php
require_once 'SistemaFCE/entidad/Entidad.class.php';

class Modulo extends Entidad {
	private $nombre;
	private $archivoPrincipal;
	private $archivoFormPrincipal;
	private $menuPrincipal;
	private $acciones;

	public function getNombre() {
		return $this->nombre;
	}
	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}
	public function getArchivoPrincipal() {
		return $this->archivoPrincipal;
	}
	public function setArchivoPrincipal($archivoPrincipal) {
		$this->archivoPrincipal = $archivoPrincipal;
	}
	public function getArchivoFormPrincipal() {
		return $this->archivoFormPrincipal;
	}
	public function setArchivoFormPrincipal($archivoFormPrincipal) {
		$this->archivoFormPrincipal = $archivoFormPrincipal;
	}
	public function getMenuPrincipal() {
		return $this->menuPrincipal;
	}
	public function setMenuPrincipal($menuPrincipal) {
		$this->menuPrincipal = $menuPrincipal;
	}
	public function getAcciones() {
		return $this->acciones;
	}
	public function setAcciones($acciones) {
		$this->acciones = $acciones;
	}



}