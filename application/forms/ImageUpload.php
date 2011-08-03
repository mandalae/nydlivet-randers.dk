<?php

class Default_Form_ImageUpload extends Zend_Form {

	public function init(){

		$this->addPrefixPath('ImageDB_Form_Element', APPLICATION_PATH . '/modules/imagedb/Form/Element/', 'element');

		$this->setMethod('post');

		$this->addElement('image', 'imageCropId', array(
		            'required'   => true,
		            'label' 	 => 'Billede'
		        ));
	}

}