<?php

class Default_Form_Teaser extends Zend_Form {

	public function init(){

		$this->addPrefixPath('ImageDB_Form_Element', APPLICATION_PATH . '/modules/imagedb/Form/Element/', 'element');

		$this->setMethod('post');

		$this->addElement('imageDB', 'image0', array(
		            'required'   => true,
		            'label' 	 => 'Top billede (Det store)',
		            'class' 	=> 'imagedb_img topTeaser'
		        ));
		$this->addElement('imageDB', 'image1', array(
		            'required'   => true,
		            'label' 	 => 'Billede 1',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('imageDB', 'image2', array(
		            'required'   => true,
		            'label' 	 => 'Billede 2',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('imageDB', 'image3', array(
		            'required'   => true,
		            'label' 	 => 'Billede 3',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('imageDB', 'image4', array(
		            'required'   => true,
		            'label' 	 => 'Billede 4',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('imageDB', 'image5', array(
		            'required'   => true,
		            'label' 	 => 'Billede 5',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('imageDB', 'image6', array(
		            'required'   => true,
		            'label' 	 => 'Billede 6',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('imageDB', 'image7', array(
		            'required'   => true,
		            'label' 	 => 'Billede 7',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('imageDB', 'image8', array(
		            'required'   => true,
		            'label' 	 => 'Billede 8',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('imageDB', 'image9', array(
		            'required'   => true,
		            'label' 	 => 'Billede 9',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('imageDB', 'image10', array(
		            'required'   => true,
		            'label' 	 => 'Billede 10',
		            'class' 	=> 'imagedb_img teaser'
		        ));
		$this->addElement('submit', 'submit', array(
		            'ignore'   => true,
		            'label'    => 'Gem',
		            'class'		=> 'teaser_submit'
		        ));
	}

}