<?php
require_once(dirname(__FILE__)."/BaseToolsMod.class.php");
require_once 'entidades/Proyecto.class.php';
class CreadorProyectosMod extends BaseToolsMod
{	
// 	function alta($req)
// 	{		
// 		$proyectoDir = Configuracion::getSystemRootDir()."/output/".$req['nombre'];
// 		$d = dir($proyectoDir);
// 		$d->
// 	}
	
	
	/**
	 * Obtiene la lista de elementos para mostrar en el listado, dados un filtro y las variables de request
	 * @param Criterio $filtro el criterio de filtro del listado
	 * @param array $req variables de request preprocesadas
	 * @param integer $limitCount limite de elementos que se mostrarán por página
	 */
	protected function getListElements($filtro,$req,$limitCount=null) {
		$dir = new DirectoryIterator(Configuracion::getSystemRootDir()."/output/");
		while($dir->valid())
		{
			if($dir->isDir() && ($dir->getFilename()!='.' && $dir->getFilename()!='..' && $dir->getFilename()!='CVS'))
			{
				$p = new Proyecto();
				$p->setNombre($dir->getFilename());
				 
				$lista[] = $p;
			}
			$dir->next();
		}			
		return $lista;	
	}
	
	protected function lista($req) {
		$filtro = $this->getFiltro($req);
		$limitCount = $this->getPaginationLimitCount($req);
		$aObjs = $this->getListElements($filtro, $req, $limitCount);
		
		if($req['display']=='xls')
			$this->descargarListaExcel($aObjs, $req);
		
		$nombreClase="";
		if(count($aObjs)>0)
		{
			$aObj = current($aObjs);
			$nombreClase = get_class($aObj);
		}
		$this->smarty->assign('lista'.$nombreClase,$aObjs);
		$this->smarty->assign('listaColumnas',$this->getColumnsList($req));
		$this->smarty->assign('laLista',$aObjs);
		$this->smarty->assign('claseEntidad',$nombreClase);
		$this->smarty->assign('modName',strtolower( str_replace("Mod", "", get_class($this)) ));
		
		$this->smarty->assign('paginationCurrentPage',isset($req['pag'])?$req['pag']:1);
		$this->smarty->assign('paginationLimitCount',$limitCount);
		$this->smarty->assign('paginationFiltro',$filtro);
		
		$this->mostrar('proyectos/lista.tpl',$req['display']);
	}

}