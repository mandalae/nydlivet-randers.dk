<?php

class Default_Form_Contact extends Zend_Form {
	
	public function init(){
		
		$this->setMethod('post');
		
		$this->setAttrib('id', 'contacts-form');
		
		$this->addElement('text', 'subject', array(
		            'label'      => 'Emne:',
		            'required'   => true,
		            'filters'    => array('StringTrim')
		        ));
		$this->addElement('textarea', 'text', array(
		            'label'      => 'Tekst:',
		            'required'   => true,
		            'filters'    => array('StringTrim')
		        ));
		
		$this->addElement('submit', 'submit', array(
		            'ignore'   => true,
		            'label'    => 'Send',
		        ));
		
		
		
	}
	
}