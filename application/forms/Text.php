<?php

class Default_Form_Text extends Zend_Form {
	
	public function init(){
		
		$this->addPrefixPath('ImageDB_Form_Element', APPLICATION_PATH . '/modules/imagedb/Form/Element/', 'element');
		
		$this->setMethod('post');
		
		$this->addElement('hidden', 'id', array(
		            'filters'    => array('StringTrim')
		        ));
		
		$this->addElement('text', 'headline', array(
		            'label'      => 'Overskrift:',
		            'required'   => true,
		            'filters'    => array('StringTrim')
		        ));
		$this->addElement('textarea', 'text', array(
		            'label'      => 'Text:',
		            'required'   => true,
		            'filters'    => array('StringTrim')
		        ));
		        
		$this->addElement('imageDB', 'imageId', array(
		            'required'   => true,
		            'label' 	 => 'Billede',
		            'class' 	=> 'imagedb_img'
		        ));
		
		$this->addElement('submit', 'submit', array(
		            'ignore'   => true,
		            'label'    => 'Gem',
		        ));
		
		
		
	}
	
}