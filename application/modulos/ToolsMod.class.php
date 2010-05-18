<?php
require_once(dirname(__FILE__)."/BaseToolsMod.class.php");
class ToolsMod extends BaseToolsMod {
	
	function getAccionPredeterminada()
	{
		return "listarHerramientas";
	}
	
	function accionListarHerramientas()
	{		
		$this->smarty->display('listaHerramientas.tpl');
	}
}