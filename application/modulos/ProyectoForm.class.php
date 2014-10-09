<?php
class ProyectoForm extends BaseForm {
	protected function addElements() {
		$this->addElement('text','nombre','Nombre');
		$this->addElement('text','ruta','Ruta (directorio del proyecto)');
		
		$this->addElement('hidden','id');
		$this->addElement('submit','guardar','Guardar');
		$this->addElement('hidden','mod','CreadorProyectos');
		
		
		$this->addElement('text','db_ms','Motor (ej: mysqli,postgres)');
		$this->addElement('text','db_host','Host');
		$this->addElement('text','db_user','Usuario');
		$this->addElement('password','db_pass','Password');
		$this->addElement('text','db_name','Nombre Base');
		$this->addElement('text','db_port','Puerto (vacio usa el por defecto del motor)');
		
		parent::addElements();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BaseForm::getDefaultsArray()
	 * @param Proyecto $elem
	 */
	protected function getDefaultsArray($elem) {
		$def = parent::getDefaultsArray($elem);
		$def['nombre'] = $elem->getNombre();
		$this->getElement('nombre')->freeze();
		$def['ruta'] = $elem->getRuta();
		$def['db_ms'] = 'mysqli';
		if($elem->getDbConfig()!=null)
		{
			$def['db_ms'] = $elem->getDbConfig()->motor;
			$def['db_host'] = $elem->getDbConfig()->host;
			$def['db_user'] = $elem->getDbConfig()->usuario;
			$def['db_pass'] = $elem->getDbConfig()->password;
			$def['db_name'] = $elem->getDbConfig()->nombreBase;
			$def['db_port'] = $elem->getDbConfig()->puerto;
		}
		else
		$this->setHidden('id', $elem->getId());
		return $def;	
	}
}