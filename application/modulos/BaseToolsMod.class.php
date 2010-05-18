<?php
require_once("SistemaFCE/modulo/BaseForm.class.php");
require_once('visual/smarty/libs/Smarty.class.php');
abstract class BaseToolsMod {
	var $form;
	protected $smarty;
	
	function __construct()
	{
		$this->form = new BaseForm('Tools');
		$systemRoot = dirname(dirname(dirname(__FILE__)));
		
		$this->smarty = new Smarty(); // Handler de smarty
        $this->smarty->template_dir = $systemRoot.'/templates/'; // configuro directorio de templates
        $this->smarty->compile_dir = $systemRoot.'/tmp/templates_c'; // configuro directorio de compilacion
        $this->smarty->cache_dir = $systemRoot.'/tmp/cache'; // configuro directorio de cache
        $this->smarty->config_dir = $systemRoot.'/templates/configs'; // configuro directorio de configuraciones
        $this->smarty->assign('relative_images',"images");
	
	}
	
	abstract function getAccionPredeterminada();
	
	function ejecutar($req)
	{
		if(empty($req["accion"])) $req["accion"] = $this->getAccionPredeterminada();
    	
    	$accion = $req["accion"];
                
        $this->smarty->assign('accion',$accion);
        
        $metodoAccion = "accion".ucfirst($accion);
        
        if(!method_exists($this,$metodoAccion) && $accion != $this->getAccionPredeterminada())
        {
            $req['accion'] = $this->getAccionPredeterminada();
            $this->ejecutar($req);
            return;
        } 
        
        $this->$metodoAccion($req);
	}
}