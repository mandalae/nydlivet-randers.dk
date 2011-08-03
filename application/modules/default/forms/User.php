<?php

class Default_Form_User extends Zend_Form {
	
	public function init(){
		
		$this->setMethod('post');
		
		$this->addElement('text', 'email', array(
		            'label'      => 'Email:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 255)),
		                array('validator' => 'EmailAddress'),
		                array('validator' => 'Db_NoRecordExists', 'options' => array(
		                                										'table' => 'users', 
		                                										'field' => 'email'
		                	                									))
		            )
		        ));
		$this->addElement('password', 'password', array(
		            'label'      => 'Adgangskode:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 255)),
		                array('validator' => 'Identical')
		            )
		        ));
		        
		$this->addElement('password', 'pass2', array(
		            'label'      => 'Adgangskode igen:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 255))
		            )
		        ));
		        
		$this->addElement('text', 'firstname', array(
		            'label'      => 'Fornavn:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 255))
		            )
		        ));
		
		$this->addElement('text', 'surname', array(
		            'label'      => 'Efternavn:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 255))
		            )
		        ));
		        
		$this->addElement('text', 'address1', array(
		            'label'      => 'Adresse:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 255))
		            )
		        ));
		
		$this->addElement('text', 'address2', array(
		            'label'      => 'Adresse:',
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 255))
		            )
		        ));
		
		$this->addElement('text', 'postalcode', array(
		            'label'      => 'Postnummer:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 4))
		            )
		        ));
		
		$this->addElement('text', 'city', array(
		            'label'      => 'By:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 255))
		            )
		        ));
		
		$this->addElement('text', 'phone', array(
		            'label'      => 'Telefon:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 10))
		            )
		        ));
		
		$this->addElement('text', 'birthdate', array(
		            'label'      => 'Fødselsdato:',
		            'required'   => true,
		            'filters'    => array('StringTrim'),
		            'class'		 => 'datepicker',
		            'validators' => array(
		                array('validator' => 'StringLength', 'options' => array(0, 255))
		            )
		        ));
		
		$this->addElement('submit', 'contact-submit', array(
		            'ignore'   => true,
		            'label'    => 'Send',
		        ));
	}
	
	public function isValid($data)
	{
	    $passTwice = $this->getElement('password');
	    $passTwice->getValidator('Identical')->setToken($data['pass2']);
	    return parent::isValid($data);
	}
	
}