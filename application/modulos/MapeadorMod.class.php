<?php
require_once 'BaseToolsMod.class.php';
require_once "Mapeador.class.php";

class MapeadorMod extends BaseToolsMod {
	var $mapeador;

	function __construct()
	{
		parent::__construct();
		$this->mapeador = new Mapeador();
		$this->form = new BaseForm('mapeoFCE');
		$this->setJsModulo('project');
		$this->addCssFile('css/project.css');
	}

	function initForm()
	{
		$this->form->addElement('text','db_ms','Motor (ej: mysql,postgres)');
		$this->form->addElement('text','db_host','Host');
		$this->form->addElement('text','db_user','Usuario');
		$this->form->addElement('password','db_pass','Password');
		$this->form->addElement('text','db_name','Nombre Base');
		$this->form->addElement('text','db_port','Puerto');

		$this->form->addElement('hidden','mod','mapeador');

		$this->form->addElement('text','dir_output','Directorio para crear (clases y xmls)',array('size'=>'150'));

		$this->form->addElement('submit','paso1','Siguiente');
	}

	function crearChecksTablas($tablas)
	{
		foreach($tablas as $t)
		{
			$this->form->addElement('checkbox',"tablas[{$t}]",$t,$t);
		}
		$this->form->removeAttribute('paso1');
		$this->form->addElement('submit','mapear','Mapear');
	}

	function accionMapear($req=null)
	{
		if(!empty($_POST))
			$this->mapeador->setDB($_POST['db_host'],$_POST['db_user'],$_POST['db_pass'],$_POST['db_name'],$_POST['db_ms'],$_POST['db_port']);

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