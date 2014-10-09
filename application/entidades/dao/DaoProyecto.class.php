<?php
define('DIR_PROYECTOS',Configuracion::getSystemRootDir()."/proyectos/");
class DaoProyecto {
	
	function count($filtro=null) {
		$lista = $this->findBy($filtro);
		return count($lista);
	}
	
	function getCriterioBase() {
		return new Criterio();
	}
	
	/**
	 * 
	 * @param Criterio $filtro
	 * @param string $order
	 * @param integer $limitOffset
	 * @param integer $limitCount
	 * @param string $group
	 * @return mixed Proyecto
	 */
	function findBy($filtro=null,$order=null,$limitOffset=null,$limitCount=null,$group=null) {
		
		$dir = new DirectoryIterator(DIR_PROYECTOS);
		while($dir->valid())
		{
			if($dir->isFile() && stripos($dir->getFilename(), '.json'))
			{
				$proyFilename = DIR_PROYECTOS.$dir->getFilename(); 
				$p = new Proyecto();
				
				$fp= fopen($proyFilename, 'r');
				$strJsonProy = fread($fp, filesize($proyFilename));
				fclose($fp);
				$jsonProy = json_decode($strJsonProy);
				$p->setNombre($jsonProy->nombre);
				$p->setRuta($jsonProy->ruta);
				if(file_exists("{$jsonProy->ruta}/.project"))
				{
					$eclipseProy=simplexml_load_file("{$jsonProy->ruta}/.project");
					$p->setNombre((string)$eclipseProy->name);
				}
				$p->setId($jsonProy->id);
				$p->setDbConfig($jsonProy->dbConfig);
					
				$lista[] = $p;
			}
			$dir->next();
		}
		
		return $lista;
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return Proyecto
	 */
	function findById($id) {
		$todos = $this->findBy();
		foreach($todos as $proy) 
		{	
			if($proy->getId() == $id)
				return $proy;
		}
		return null;
	}
	
	function crearDesdeArreglo($req) {
		$proy = new Proyecto();
		$proy->setNombre($req['nombre']);
		$proy->setRuta($req['ruta']);
		$proy->setId($req['id']);
		$dbConfig = new DbConfig();
		
		$dbConfig->host = $req['db_host'];
		$dbConfig->motor = $req['db_ms'];
		$dbConfig->usuario = $req['db_user'];
		$dbConfig->password = $req['db_pass'];
		$dbConfig->nombreBase = $req['db_name'];
		$dbConfig->puerto = $req['db_port'];
		
		$proy->setDbConfig($dbConfig);

		return $proy;
	}
	
	function deletePorId($id) {
		$proy = $this->findById($id);
		return unlink(DIR_PROYECTOS.$proy->getNombre().".json");
	}
	
	/**
	 * 
	 * @param Proyecto $elem
	 * @return boolean
	 */
	function save($elem) {
		if(!file_exists($elem->getRuta()))
			mkdir($elem->getRuta());
		
		//TODO: actualizar config.xml 
		
		$jsonProy = new stdClass();
		$jsonProy->nombre = $elem->getNombre();
		$jsonProy->ruta = $elem->getRuta();
		$jsonProy->id = $elem->getId();
		
		$jsonProy->dbConfig = $elem->getDbConfig();
		
		if(empty($jsonProy->id))
		{
			$maxId = 0;
			$existentes = $this->findBy();
			foreach ($existentes as $proy)
			{
				$maxId = max($proy->getId(),$maxId);
			}
			$jsonProy->id = $maxId  +1;
		}
		$fp = fopen(DIR_PROYECTOS.$elem->getNombre().".json",'w');
		fwrite($fp, json_encode($jsonProy));
		
		fclose($fp);
		
		return true;
	}
}