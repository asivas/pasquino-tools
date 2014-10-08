<?php
class DaoProyecto {
	
	function count($filtro=null) {
		$lista = $this->findBy($filtro);
		return count($lista);
	}
	
	function getCriterioBase() {
		return new Criterio();
	}
	
	function findBy($filtro=null,$order=null,$limitOffset=null,$limitCount=null,$group=null) {
		
		$dir = new DirectoryIterator(Configuracion::getSystemRootDir()."/output/");
		while($dir->valid())
		{
			if($dir->isFile() && stripos($dir->getFilename(), '.json'))
			{
				$proyFilename = Configuracion::getSystemRootDir()."/output/".$dir->getFilename(); 
				$p = new Proyecto();
				
				$fp= fopen($proyFilename, 'r');
				$strJsonProy = fread($fp, filesize($proyFilename));
				fclose($fp);
				$jsonProy = json_decode($strJsonProy);
				$p->setNombre($jsonProy->nombre);
				$p->setRuta($jsonProy->ruta);
				$p->setId($jsonProy->id);
					
				$lista[] = $p;
			}
			$dir->next();
		}
		
		return $lista;
	}
	
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

		return $proy;
	}
	
	function deletePorId($id) {
		$proy = $this->findById($id);
		return unlink(Configuracion::getSystemRootDir()."/output/".$proy->getNombre().".json");
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
		$fp = fopen(Configuracion::getSystemRootDir()."/output/".$elem->getNombre().".json",'w');
		fwrite($fp, json_encode($jsonProy));
		
		fclose($fp);
		
		return true;
	}
}