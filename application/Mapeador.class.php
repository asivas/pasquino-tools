<?php

require_once('datos/adodb/adodb.inc.php');

class Mapeador {
	//private $dbConfig;
	private $db;

	static private $templatesDirname = "templates";

	var $errors;

	function setDB($host,$user,$pass,$db,$dbms='mysql',$port=null)
	{
		//$this->dbConfig = array('host'=>$host,'user'=>$user,'pass'=>$pass,'name'=>$db,'port'=$port);
		$this->db = &ADONewConnection($dbms); # eg 'mysql' or 'postgres'
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
        if(!empty($port))
        	$host = "$host:$port";
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

	/**
	 * Convierte los nombres separados por _ y espacio en las usadas para nombres de método con
	 * todas las palabras juntas diferenciadas por comenzar en mayusculas
	 * @param string $nombre
	 * @return string
	 */
	private function uam($nombre)
	{
		$palabrasNombre = split("[_ ]",$nombre);
		$palabrasNombre[0] = strtolower($palabrasNombre[0]);
		if(count($palabrasNombre)>1)
		{
			for($i=1;$i<count($palabrasNombre);$i++)
				$palabrasNombre[$i] = ucfirst($palabrasNombre[$i]);
		}
		return implode($palabrasNombre);
	}

	function _textoPropiedad($nombreProp,$campo=null)
	{
		return "	private \${$nombreProp};\n";
	}

	function _textoMetodos($nombreProp,$campo=null)
	{
		return  "
	public function get".ucfirst($nombreProp)."(){
		return \$this->{$nombreProp};
	}

	public function set".ucfirst($nombreProp)."(\$new".ucfirst($nombreProp)."){
		\$this->{$nombreProp} = \$new".ucfirst($nombreProp).";
	}";
	}

	function _textoXml($tag,$nombreProp,$campo=null)
	{
		$nombreCampo = $campo['Field'];
		return "		<{$tag} columna=\"{$nombreCampo}\" nombre=\"{$nombreProp}\" />\n";
	}

	function mapearTabla($tabla,$dirEntidades,$dirMappings,$dirDaos)
	{
		$nombreClase = ucfirst($this->uam($tabla));
		$filenameEntidad = "{$nombreClase}.class.php";
		$filenameXml = strtolower($nombreClase).".xml";
		$filenameDao = "Dao{$nombreClase}.class.php";

		$textoPropiedades = "";
		$textoMetodos = "";

		$textoClase = "<?php\nrequire_once \"SistemaFCE/entidad/Entidad.class.php\";\nclass {$nombreClase} extends Entidad {";
		$textoDao = "<?php\nrequire_once \"SistemaFCE/dao/DaoBase.class.php\";\nclass Dao{$nombreClase} extends DaoBase{\n}";
		$textoXml =
'<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE mapping PUBLIC "-//FCEunicen//DTD Mapping//ES" "http://apps.econ.unicen.edu.ar/public/dtd/mapping.dtd" >
<mapping path="'.basename($dirEntidades).'">
	<clase nombre="'.$nombreClase.'" tabla="'.$tabla.'">';
		$rs = $this->db->Execute("SHOW columns FROM {$tabla}");
		while($campo = $rs->FetchRow())
		{
			if($campo['Key']=='PRI')
				$ids[] = $campo;
			else
				$props[] = $campo;
		}
		if(is_array($ids))
			foreach ($ids as $campo)
			{
				$nombreCampo = $campo['Field'];
				$nombreProp = $this->uam($nombreCampo);
				$textoPropiedades .= $this->_textoPropiedad($nombreProp,$campo);
				$textoMetodos .= $this->_textoMetodos($nombreProp,$campo);
				$tag='id';
				$id[$nombreCampo] = $nombreProp;
				$textoXml .= $this->_textoXml($tag, $nombreProp,$campo);
			}
		if(is_array($props))
			foreach ($props as $campo)
			{
				$nombreCampo = $campo['Field'];
				$nombreProp = $this->uam($nombreCampo);
				$textoPropiedades .= $this->_textoPropiedad($nombreProp,$campo);
				$textoMetodos .= $this->_textoMetodos($nombreProp,$campo);
				$tag='propiedad';
				$textoXml .= $this->_textoXml($tag, $nombreProp,$campo);
			}
// 		if(count($id)>1)
// 		{
// 			foreach($id as $col => $prop)
// 			{
// 				$arrId .= "'{$col}'=>\$this->{$prop},";
// 			}
// 			$arrId = trim($arrId,',');
// 			$textoMetodos = "
// 	public function getId(){
// 		return array({$arrId});
// 	}

// 	".$textoMetodos;
// 		}
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
		if($fp)
		{	
			fwrite($fp, $textoClase);
			fclose($fp);
		}
		else 
			$this->errors[] = "No se pudo abrir {$dirEntidades}/{$filenameEntidad}";

		$fp = fopen($dirDaos.'/'.$filenameDao, 'w');
		if($fp) {
			fwrite($fp, $textoDao);
			fclose($fp);
		}
		else 
			$this->errors[] = "No se pudo abrir {$dirDaos}/{$filenameDao}";
		
		$fp = fopen($dirMappings.'/'.$filenameXml, 'w');
		if($fp) {
			fwrite($fp, $textoXml);
			fclose($fp);
		}
		else
			$this->errors[] = "No se pudo abrir {$dirMappings}/{$filenameXml}";
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

		$dirConf 		   = "{$dirOutput}/conf";
		$dirApplication    = "{$dirOutput}/application/";
		$dirPublic 		   = "{$dirOutput}/public/";

		$dirEntidades 	   = "{$dirApplication}/{$nombreDirEntidades}";
		$dirDaos 		   = "{$dirEntidades}/daos";
		$dirMappings 	   = "{$dirConf}/mappings";

		if(!is_dir($dirConf))			mkdir($dirConf);
		if(!is_dir($dirApplication))	mkdir($dirApplication);
		if(!is_dir($dirEntidades))		mkdir($dirEntidades);
		if(!is_dir($dirDaos))			mkdir($dirDaos);
		if(!is_dir($dirMappings))		mkdir($dirMappings);
		if(!is_dir($dirPublic))			mkdir($dirPublic);

		if(is_array($tablas))
		{
			foreach($tablas as $t=>$dummy)
			{
				$results[] = "mapeando {$t}";
				$this->mapearTabla($t,$dirEntidades,$dirMappings,$dirDaos);
			}
		}
		if(!file_exists("{$dirConf}/config.xml"))
			$this->crearConfig($tablas, $dirConf, $nombreDirEntidades);
		$this->crearIndex($dirPublic);
		return $results;
	}


	function crearConfig($tablas,$dirOutput)
	{
		$textoXmlConfig = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE sistema PUBLIC "-//FCEunicen//DTD Config//ES" "http://apps.econ.unicen.edu.ar/public/dtd/config.dtd" >
<sistema nombre="" version-pasquino="1.6" version="0.1">
	<date-formats>
		<date-format>%d/%m/%Y</date-format>
		<datetime-format>%d/%m/%Y %H:%M</datetime-format>
		<time-format>%H:%M</time-format>
	</date-formats>
	<mappings path="conf/mappings" >
';
		foreach($tablas as $t=>$dummy)
		{
			$nombreClase = ucfirst($this->uam($t));
			$textoXmlConfig .= '		<mapping archivo="'.$filenameXml = strtolower($nombreClase).'.xml" clase="'.$nombreClase.'" />
';
		}
	$textoXmlConfig .= '</mappings>

	<data-sources default="">
	</data-sources>
	<modulos default="" path="modulos">
	</modulos>
	<templates default="default" path="">
		<template dir="default" nombre="default">
		</template>
	</templates>
</sistema>';

	$fp = fopen($dirOutput.'/config.xml', 'w');
	fwrite($fp, $textoXmlConfig);
	fclose($fp);

	}

	function crearIndex($dirPublic) {
		$indexContent ="<?php
	require_once('visual/smarty3/libs/Smarty.class.php');
	require_once('SistemaFCE/util/Configuracion.class.php');

	SistemaFCE::initSistema();
	SistemaFCE::ejecutarSistema();";
		$fp = fopen("{$dirPublic}/index.php", 'w');
		fwrite($fp, $indexContent);
		fclose($fp);
	}
}
