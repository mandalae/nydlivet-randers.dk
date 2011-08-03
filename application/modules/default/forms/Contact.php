<?php

class Default_Form_Contact extends Zend_Form {
	
	public function init(){
		
		$this->setMethod('post');
		
		$this->addElement('text', 'subject', array(
		            'label'      => 'Emne:',
		            'required'   => true,
		            'filters'    => array('StringTrim')
		        ));
		
		$this->addElement('text', 'fromEmail', array(
		            'label'      => 'Din email:',
		            'required'   => true,
		            'filters'    => array('StringTrim')
		        ));
		
		$this->addElement('text', 'from', array(
		            'label'      => 'Dit navn:',
		            'required'   => true,
		            'filters'    => array('StringTrim')
		        ));
		
		$this->addElement('textarea', 'text', array(
		            'label'      => 'Tekst:',
		            'required'   => true,
		            'filters'    => array('StringTrim')
		        ));
		
		$this->addElement('submit', 'contact-submit', array(
		            'ignore'   => true,
		            'label'    => 'Send',
		        ));
	}
	
}