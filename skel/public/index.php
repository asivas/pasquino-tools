<?php
/**
 * 
 * @author lucas.vidaguren
 * @since 26/09/2008
 */
require_once("SistemaFCE/util/Configuracion.class.php"); 
//require_once('../clases/daos/DaoUsuario.class.php');
Configuracion::initSistema(dirname(dirname(__FILE__)),array('clases','clases/daos'));


Configuracion::ejecutarSistema();
