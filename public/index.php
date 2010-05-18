<?php
require_once("../application/Mapeador.class.php");
require_once("../application/modulos/MapeadorMod.class.php");
require_once("../application/modulos/CreadorProyectosMod.class.php");
require_once("../application/modulos/ToolsMod.class.php");

if(isset($_REQUEST['mod']))
{
	$modClass = "{$_REQUEST['mod']}Mod";
	$mod = new $modClass();
}
else
	$mod = new ToolsMod();

$mod->ejecutar($_REQUEST);
