<?php
require_once 'BaseToolsMod.class.php';
class ToolsMod extends BaseToolsMod {
	
	function getAccionPredeterminada()
	{
		return "listarHerramientas";
	}
	
	function accionListarHerramientas()
	{		
		$this->mostrar('listaHerramientas.tpl');
	}
}