<?php

class ImageDB_Form_Element_Image extends Zend_Form_Element{

	public function init()
    {
        $this->addPrefixPath('ImageDB_Decorator', APPLICATION_PATH . '/modules/imagedb/Decorator/', 'decorator')
        ->addDecorator('Image');
    }

}