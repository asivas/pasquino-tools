<?php
require_once(dirname(__FILE__)."/BaseToolsMod.class.php");
require_once 'entidades/Proyecto.class.php';
require_once 'entidades/dao/DaoProyecto.class.php';
require_once 'modulos/ProyectoForm.class.php';

class CreadorProyectosMod extends BaseToolsMod
{

	function __construct() {
		parent::__construct();

		$this->_tplLista = $this->smarty->getTemplateVars('pQnListaTpl');
		$this->_tplForm = $this->smarty->getTemplateVars('pQnFormTpl');
		$this->mainDao = new DaoProyecto();

		$this->setJsModulo('project');
		$this->addCssFile('css/project.css');
	}

	protected function crearForm() {
		$this->_form = new ProyectoForm();
	}

	protected function initListColumns() {
		$this->addPropertyColumnToList('nombre', "Nombre");
		$this->addColumnAcciones();
	}

	/**
	 * Carga el pedazo de html de los botones de accion para la administración de una entidad
	 * @param Entidad $entidad la entidad sobre la cual se harán las posibles acciones
	 * @param string $listaAccionesTpl la ruta del tpl de la lista de acciones, por defecto se pide a getListaAccionesTpl
	 * @return Ambigous <string, void, boolean, string, mixed>
	 */
	function getGridAccionesItem($entidad,$listaAccionesTpl=null)
	{
		if(!isset($listaAccionesTpl))
			$listaAccionesTpl = $this->getListaAccionesTpl();
		$lowerModName = strtolower( str_replace("Mod", "", get_class($this)) );
		$this->smarty->assign('entidad',$entidad);
		$this->smarty->assign('modName',$lowerModName);
		return $this->smarty->fetch($listaAccionesTpl);
	}
// 	function alta($req)
// 	{
// 		$proyectoDir = Configuracion::getSystemRootDir()."/output/".$req['nombre'];
// 		$d = dir($proyectoDir);
// 		$d->
// 	}

	/**
	 * Obtiene el id del item que se desea buscar/generar a partir del req
	 * @param array $req
	 */
	protected function getItemId($req) {
		return $req['id'];
	}

	protected function assignSmartyTplVars() {
		parent::assignSmartyTplVars();

		$this->setTplVar('tituloModulo','Proyectos');
		$this->setTplVar('descripcionModulo','Administración de proyectos pQn');
	}




}