<?php
require_once(dirname(__FILE__)."/BaseToolsMod.class.php");
class CreadorProyectosMod extends BaseToolsMod
{
	function getAccionPredeterminada()
	{
		return "alta";
	}
	
	function accionAlta()
	{		
		print "En construcción";
	}
}