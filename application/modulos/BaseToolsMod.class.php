<?php
require_once("SistemaFCE/modulo/BaseForm.class.php");
require_once 'SistemaFCE/modulo/BaseAdminMod.class.php';
abstract class BaseToolsMod extends BaseAdminMod {
	var $form;
	
	/**
	 * Reescribo la constructora para evitar todo lo que tiene que ver con Sesion y DB
	 */
	function __construct()
	{
		
		$tilePathName = 'Admin';
        $this->_skinConfig = Configuracion::getTemplateConfigByDir($skinName);

		//@deprecated ya no se usa el calendario js, se prefiere el uso de jQuery
        $this->_calendar = new FCEcalendar('/js/jscalendar/', "es", "../../skins/".$this->_skinConfig['dir']."/css/cal", false);
		
		//si se puede cargo el usuario
		$this->getUsuario();

        $this->_dateFormat = Configuracion::getDateFormat();
        $this->_dateTimeFormat = Configuracion::getDateTimeFormat();
        $this->_timeFormat = Configuracion::getTimeFormat();

        $this->pasquinoPath = Configuracion::getPasquinoPath();

        $this->initSmarty();

        $this->_orderListado = $_SESSION[get_class($this)]['sort'];
        $this->_sentidoOrderListado = $_SESSION[get_class($this)]['sortSentido'];

        if(method_exists($this->smarty,'getTemplateVars'))
        	$this->_tilePath = $this->smarty->getTemplateVars('pQnDefaultTpl');
        else
        	$this->_tilePath = Configuracion::getDefaultTplPath($skinName);//'decorators/default.tpl';
        //seteo el path de donde está pasquino

        if (Configuracion::getLoggerClass()!= null)
        	$this->logger= Log::factory(Configuracion::getLoggerClass());	
        
        $tConf = Configuracion::getTemplateConfigByNombre($skinName);
        $this->_tilePath = Configuracion::findTplPath($tConf,$tilePathName);
        
        if(!isset($this->_tilePath) && method_exists($this->smarty,'getTemplateVars')) //smarty3 y el tilePath está vacio
        	$this->_tilePath = $this->smarty->getTemplateVars('pQn'.$tilePathName.'Tpl');
        
        $this->initListColumns();
	}
	
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
	
	protected function initSmarty() {
		$systemRoot = Configuracion::getSystemRootDir();
		
		$config = Configuracion::getConfigXML();
		$templates = $config->templates;
		$skinsDirname = (string)$config->templates['path'];
		
		if(empty($skinsDirname))
			$skinsDirname = "skins";
		
		$this->smarty = new Smarty(); // Handler de smarty
		$this->smarty->template_dir = "{$systemRoot}/{$skinsDirname}/{$this->_skinConfig['dir']}"; // configuro directorio de templates
		$this->smarty->compile_dir = "{$systemRoot}/tmp/templates_c"; // configuro directorio de compilacion
		$this->smarty->cache_dir = "{$systemRoot}/tmp/{$skinsDirname}/cache"; // configuro directorio de cache
		$this->smarty->config_dir = "{$systemRoot}/{$skinsDirname}/configs"; // configuro directorio de configuraciones
		
		$publicSkinDir = $this->_skinConfig['wwwdir'];
		if(empty($publicSkinDir))
			$publicSkinDir = $this->_skinConfig['dir'];
		$this->smarty->assign('skin',$publicSkinDir);
		$this->smarty->assign('relative_images',"{$skinsDirname}/{$publicSkinDir}/images");
		$this->smarty->assign('version',Configuracion::getVersion());
		$this->smarty->assign('skinPath',$systemRoot."/{$skinsDirname}/".$this->_skinConfig['dir']);
		$this->smarty->assign('appName',Configuracion::getAppName());
		$this->smarty->assign('cal_files',$this->_calendar->get_load_files_code());
		
		$this->smarty->assign('dir_images',"{$skinsDirname}/{$publicSkinDir}/images");
		$this->smarty->assign('dir_js',"{$skinsDirname}/{$publicSkinDir}/js");
		
		$this->assingSmartyMenu();
		
		$this->smarty->assign('dateFormat',$this->_dateFormat);
		$this->smarty->assign('timeFormat',$this->_timeFormat);
		$this->smarty->assign('dateTimeFormat',$this->_dateTimeFormat);
		
		$this->assignSmartyTplVars();
		
		$this->smarty->assign('facade',new smartyFacade($this));
		
		$this->setTplVar("ckeditorVersion", '4.4.1');
	}
	
	protected function getUsuario() {
		return null;
	}
}