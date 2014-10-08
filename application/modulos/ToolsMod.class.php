<?php
require_once 'BaseToolsMod.class.php';
class ToolsMod extends BaseToolsMod {
	
	protected function assignSmartyTplVars() {
		parent::assignSmartyTplVars();
		
		$this->setTplVar('tituloModulo','Herramientas de pasquino');
		$this->setTplVar('descripcionModulo','Administración de proyectos y otras herramientas útiles');
	}
	
	function getAccionPredeterminada()
	{
		return "listarHerramientas";
	}
	
	function accionListarHerramientas()
	{		
		$this->mostrar('listaHerramientas.tpl');
	}
}