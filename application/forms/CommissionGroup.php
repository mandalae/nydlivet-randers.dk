<?php

class Default_Form_CommissionGroup extends Zend_Form {
	
	public function init(){
		
		$this->setMethod('post');
		
		$this->addElement('hidden', 'id', array(
		            'filters'    => array('StringTrim')
		        ));
		
		$this->addElement('text', 'name', array(
		            'label'      => 'Overskrift:',
		            'required'   => true,
		            'filters'    => array('StringTrim')
		        ));
		
		$this->addElement('submit', 'submit', array(
		            'ignore'   => true,
		            'label'    => 'Gem',
		        ));
		
		
		
	}
	
}