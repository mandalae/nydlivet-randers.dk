<?php

class Imagedb_UploadController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout('imagedb');
		if (!Zend_Auth::getInstance()->hasIdentity()){
			$this->view->close = true;
		}
		$this->view->headScript()->appendFile('/media/scripts/ImageDB.js')
								->appendFile('/media/scripts/jquery.cookie.js')
								->appendFile('/media/scripts/jquery-ui-min.js');
    }

    public function indexAction()
    {
		$form = new Default_Form_ImageUpload();
		
		$this->view->form = $form;
    }
    
    public function uploadAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$file = $_FILES['Filedata'];
		if (is_uploaded_file($file['tmp_name'])){
			$image = new Default_Model_Image();
			echo $image->upload($file);
		}
    }
}

