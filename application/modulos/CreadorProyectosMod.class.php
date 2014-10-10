<?php
require_once(dirname(__FILE__)."/BaseToolsMod.class.php");
require_once 'entidades/Proyecto.class.php';
require_once 'entidades/dao/DaoProyecto.class.php';
require_once 'modulos/ProyectoForm.class.php';
require_once "Mapeador.class.php";

class CreadorProyectosMod extends BaseToolsMod
{
	protected $formMappings;
	protected $mapeador;

	function __construct() {
		parent::__construct();
		$this->mapeador = new Mapeador();
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

	protected function getListaAccionesTpl() {
		return 'proyectos/listaAcciones.tpl';
	}


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

	/**
	 *
	 * @param Proyecto $proyecto
	 * @param array $tablas
	 */
	protected function crearFormMappings($proyecto,$tablas)
	{
		$this->formMappings = new BaseForm();

		$this->formMappings->addElement('hidden','mod','creadorProyectos');
		$this->formMappings->addElement('hidden','accion','mapear');

		foreach($tablas as $t)
		{
			$this->formMappings->addElement('checkbox',"tablas[{$t}]",$t,$t);
		}
		$this->formMappings->addElement('submit','mapear','Mapear');
		$this->formMappings->addElement('hidden','idProyecto',$proyecto->getId());
	}

	function accionMapear($req=null)
	{
		$proyecto = $this->mainDao->findById($req['idProyecto']);
		$dbConfig = $proyecto->getDbConfig();
		$this->mapeador->setDB($dbConfig->host,$dbConfig->usuario,$dbConfig->password,$dbConfig->nombreBase,$dbConfig->motor,$dbConfig->puerto);

		$tablas = $this->mapeador->getListaTablas();
		$this->crearFormMappings($proyecto,$tablas);


		if(isset($_POST['mapear']))
		{
			$results = $this->mapeador->mapearTablas($_POST['tablas'],$proyecto->getRuta());
			$this->setTplVar('resultadosMapeo', $results);
			if(!empty($this->mapeador->errors))
			{
				print "<div style='color:red'>";
				foreach($this->mapeador->errors as $e)
				{
					print "{$e}<br>";
				}
				print "</div>";
			}
			}
			$this->renderForm('maperForm',$this->formMappings);
			$this->mostrar('form.tpl');
			//$this->form->Display();
	}


	protected function info($req) {
		$proyecto = $this->mainDao->findById($req['idProyecto']);
		$this->setTplVar('proyecto', $proyecto);
		$this->mostrar("proyectos/info.tpl");
	}



}