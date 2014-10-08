<?php
class ProyectoForm extends BaseForm {
	protected function addElements() {
		$this->addElement('text','nombre','Nombre');
		$this->addElement('text','ruta','Ruta (directorio del proyecto)');
		
		$this->addElement('hidden','id');
		$this->addElement('submit','guardar','Guardar');
		$this->addElement('hidden','mod','CreadorProyectos');
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
		$def['ruta'] = $elem->getRuta();
		$this->setHidden('id', $elem->getId());
		return $def;	
	}
}