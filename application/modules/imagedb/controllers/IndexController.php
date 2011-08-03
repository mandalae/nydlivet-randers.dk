<?php

class Imagedb_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout('imagedb');
		if (!Zend_Auth::getInstance()->hasIdentity()){
			$this->_helper->redirector('index', 'admin');
			$this->view->close = true;
		}
		$this->view->headScript()->appendFile('/media/scripts/ImageDB.js')
								->appendFile('/media/scripts/jquery.cookie.js')
								->appendFile('/media/scripts/jquery-ui-min.js');
    }

    public function indexAction()
    {
    	$request = $this->getRequest();
    	$this->view->elementName = $request->getParam('name');
    		
		$image = new Default_Model_Image();
		$this->view->entries = $image->fetchAllActive();
		
		$commission = $request->getCookie('pb_commission');
		if (intval($commission) > 0){
			$this->view->commission = $commission;
			$group = new Default_Model_CommissionGroup();
			$group->find($commission);
			$images = $group->getImages();
			$chosen = array();
			foreach ($images as $image){
				$chosen[] = $image['id'];
			}
			$this->view->images = $chosen;
		} else {
			$this->view->commission = 0;
		}
		
		$gallery = $request->getCookie('pb_gallery');
		if (intval($gallery) > 0){
			$this->view->gallery = $gallery;
			$group = new Default_Model_ImageGroup();
			$group->find($gallery);
			$images = $group->getImages();
			$chosen = array();
			foreach ($images as $image){
				$chosen[] = $image['id'];
			}
			$this->view->images = $chosen;
		} else {
			$this->view->gallery = 0;
		}
		
    }
}

