<?php
require_once(dirname(__FILE__)."/BaseToolsMod.class.php");
require_once 'entidades/Proyecto.class.php';
require_once 'entidades/dao/DaoProyecto.class.php';
require_once 'modulos/ProyectoForm.class.php';

class CreadorProyectosMod extends BaseToolsMod
{
	protected $formMappings;

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
	
	protected function crearFormMappings($tablas)
	{
		$this->formMappings = new BaseForm();
		
		$this->formMappings->addElement('hidden','mod','creadorProy');
		$this->formMappings->addElement('hidden','accion','mapear');
		
		foreach($tablas as $t)
		{
			$this->formMappings->addElement('checkbox',"tablas[{$t}]",$t,$t);
		}
		$this->formMappings->addElement('submit','mapear','Mapear');
	}
	
	function accionMapear($req=null)
	{
		
		$proyecto = (new DaoProyecto())->findById($req['idProyecto']);
		$dbConfig = $proyecto->getDbConfig();		
		$this->mapeador->setDB($dbConfig->host,$dbConfig->usuario,$dbConfig->password,$dbConfig->nombreBase,$dbConfig->motor,$dbConfig->puerto);
	
		$this->initForm();
		$this->form->setDefaults(array('dir_output'=>dirname(dirname(dirname(__FILE__))).'/output/','db_ms'=>'mysql'));
	
		if(isset($_POST['paso1']))
		{
			$tablas = $this->mapeador->getListaTablas();
			$this->crearChecksTablas($tablas);
		}
	
		if(isset($_POST['mapear']))
		{
			$results = $this->mapeador->mapearTablas($_POST['tablas'],$_POST['dir_output']);
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
			$this->renderForm('maperForm',$this->form);
			$this->mostrar('form.tpl');
			//$this->form->Display();
	}
	
	

}