<?php
require_once("../application/Mapeador.class.php");
require_once("SistemaFCE/modulo/BaseForm.class.php");

function initForm(&$form)
{
	$form->addElement('text','db_ms','Motor (ej: mysql,postgres)');
	$form->addElement('text','db_host','Host');
	$form->addElement('text','db_user','Usuario');
	$form->addElement('password','db_pass','Password');
	$form->addElement('text','db_name','Nombre Base');
	$form->addElement('text','db_port','Puerto');
	
	$form->addElement('text','dir_output','Directorio para crear (clases y xmls)',array('size'=>'150'));
	
	$form->addElement('submit','paso1','Siguiente');
}

function crearChecksTablas(&$form,$tablas)
{	
	foreach($tablas as $t)
	{
		$form->addElement('checkbox',"tablas[{$t}]",$t,$t);
	}
	$form->removeAttribute('paso1');
	$form->addElement('submit','mapear','Mapear');
}

$mapeador = new Mapeador();
if(isset($_POST['paso1']))
	$mapeador->setDB($_POST['db_host'],$_POST['db_user'],$_POST['db_pass'],$_POST['db_name'],$_POST['db_ms'],$_POST['db_port']);
$form = new BaseForm('mapeoFCE');

initForm($form);

$form->setDefaults(array('dir_output'=>dirname(__FILE__).'/output/','db_ms'=>'mysql'));

if(isset($_POST['paso1']))
{
	$tablas = $mapeador->getListaTablas();
	crearChecksTablas($form,$tablas);
}

if(isset($_POST['mapear']))
{
	
	//TODO: c|rear los archivos
	$mapeador->mapearTablas($_POST['tablas'],$_POST['dir_output']);
	if(!empty($mapeador->errors))
	{
		print "<div style='color:red'>";
		foreach($mapeador->errors as $e)
		{
			print "{$e}<br>";
		}
		print "</div>";
	}
}

$form->Display();