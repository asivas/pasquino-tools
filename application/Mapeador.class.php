<?php
require_once('visual/smarty/libs/Smarty.class.php');
require_once('datos/adodb/adodb.inc.php');	

class Mapeador {	
	private $smarty;
	//private $dbConfig;
	private $db;
	
	var $errors;
	
	function __construct()
	{
		$systemRoot = dirname(__FILE__);
		
		$this->smarty = new Smarty(); // Handler de smarty
        $this->smarty->template_dir = $systemRoot.'/templates/'; // configuro directorio de templates
        $this->smarty->compile_dir = $systemRoot.'/tmp/templates_c'; // configuro directorio de compilacion
        $this->smarty->cache_dir = $systemRoot.'/tmp/cache'; // configuro directorio de cache
        $this->smarty->config_dir = $systemRoot.'/templates/configs'; // configuro directorio de configuraciones
        $this->smarty->assign('relative_images',"images");
	}
	
	function setDB($host,$user,$pass,$db,$dbms='mysql',$port=null)
	{
		//$this->dbConfig = array('host'=>$host,'user'=>$user,'pass'=>$pass,'name'=>$db,'port'=$port);
		$this->db = &ADONewConnection($dbms); # eg 'mysql' or 'postgres'
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->db->NConnect($host, $user, $pass, $db);
	}
	
	function getListaTablas()
	{
		$this->db->SetFetchMode(ADODB_FETCH_NUM);
		$rs = $this->db->Execute("SHOW tables");
		$tablas = array();
		while($row = $rs->FetchRow())
		{
			$tablas[] = $row[0];
		}
		$this->db->SetFetchMode(ADODB_FETCH_ASOC);
		return $tablas;
	}
	private function uam($nombre) 
	{
		$palabrasNombre = split("_",$nombre);
		if(count($palabrasNombre)>1)
		{
			for($i=1;$i<count($palabrasNombre);$i++)
				$palabrasNombre[$i] = ucfirst($palabrasNombre[$i]);	
		}
		return implode($palabrasNombre);
	}
	
	function mapearTabla($tabla,$dirEntidades,$dirMappings,$dirDaos)
	{		
		$nombreClase = ucfirst($this->uam($tabla));
		$filenameEntidad = "{$nombreClase}.class.php";
		$filenameXml = strtolower($nombreClase).".xml";
		$filenameDao = "Dao{$nombreClase}.class.php";
		
		$textoPropiedades = "";
		$textoMetodos = "";
		
		$textoClase = "<?php\nclass {$nombreClase} {";
		$textoDao = "<?php\nrequire_once \"SistemaFCE/dao/DaoBase.class.php\";\nclass Dao{$nombreClase} extends BaseDao{\n}";
		$textoXml = 
'<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE mapping PUBLIC "-//FCEunicen//DTD Mapping//ES" "http://apps.econ.unicen.edu.ar/public/dtd/mapping.dtd" >
<mapping path="'.basename($dirEntidades).'">
	<clase nombre="'.$nombreClase.'" tabla="'.$tabla.'">';
		$rs = $this->db->Execute("SHOW columns FROM {$tabla}");
		while($campo = $rs->FetchRow())
		{
			$nombreCampo = $campo['Field'];
			$nombreProp = $this->uam($nombreCampo);
			$textoPropiedades .= "	private \${$nombreProp};\n";
			$textoMetodos .= "
	function get".ucfirst($nombreProp)."(){
		return \$this->{$nombreProp};
	}
	
	function set".ucfirst($nombreProp)."(\$new".ucfirst($nombreProp)."){
		\$this->{$nombreProp} = \$new".ucfirst($nombreProp).";
	}";			
			$tag='propiedad';
			if($campo['Key']=='PRI')
			{
				$tag='id';
				$id[$nombreCampo] = $nombreProp; 
			}
				
			$textoXml .= 
"		<{$tag} columna=\"{$nombreCampo}\" nombre=\"{$nombreProp}\" />\n";
			
		}
		if(count($id)>1)
		{
			foreach($id as $col => $prop)
			{
				$arrId .= "'{$col}'=>\$this->{$prop},";
			}
			$arrId = trim($arrId,',');
			$textoMetodos = "
	function getId(){
		return array({$arrId});
	}
	
	".$textoMetodos;
		}		
	$textoXml .= 
'	</clase>
</mapping>';
		$textoClase .= "\n/* propiedades */\n";
		$textoClase .= $textoPropiedades;
		$textoClase .= "\n/* metodos */\n";
		$textoClase .= $textoMetodos;
		$textoClase .= "
}";
		
		$fp = fopen($dirEntidades.'/'.$filenameEntidad, 'w');
		fwrite($fp, $textoClase);
		fclose($fp);
		$fp = fopen($dirDaos.'/'.$filenameDao, 'w');
		fwrite($fp, $textoDao);
		fclose($fp);
		$fp = fopen($dirMappings.'/'.$filenameXml, 'w');
		fwrite($fp, $textoXml);
		fclose($fp);
	}
	
	function mapearTablas($tablas,$dirOutput,$nombreDirEntidades='entidades')
	{
		//veo si existe o creo (si puedo) el dirOutput
		if(!is_dir($dirOutput))
			if(!mkdir($dirOutput,0777,true))
			{
				$this->errors[] = 'El directorio de salida no exite';
				return false;
			}
		$dirEntidades = $dirOutput."/{$nombreDirEntidades}";
		$dirMappings = $dirOutput."/mappings";
		$dirDaos = $dirEntidades."/daos";
		if(!is_dir($dirEntidades))
			mkdir($dirEntidades);
		if(!is_dir($dirMappings))
			mkdir($dirMappings);
		if(!is_dir($dirDaos))
			mkdir($dirDaos);
		
		if(is_array($tablas))
		{
			foreach($tablas as $t=>$dummy)
			{
				print "mapeando {$t}<br>";
				ob_flush();
				$this->mapearTabla($t,$dirEntidades,$dirMappings,$dirDaos);
			}
		}
	}
}
