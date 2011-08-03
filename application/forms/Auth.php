<?php

class Default_Form_Auth extends Zend_Form {
	
	public function init(){
		
		$this->setMethod('post');
		
		$this->addElement('text', 'username', array(
		            'label'      => 'Brugernavn:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 20))
		            )
		        ));
		$this->addElement('password', 'password', array(
		            'label'      => 'Password:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 20))
		            )
		        ));
		
		$this->addElement('submit', 'submit', array(
		            'ignore'   => true,
		            'label'    => 'Log ind',
		        ));
		
		
		
	}
	
}