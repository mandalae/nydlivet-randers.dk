<?php

class Imagedb_CommissionController extends Zend_Controller_Action
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
    	$model = new Default_Model_CommissionGroup();
    	$this->view->entries = $model->fetchAll(false, false);
    	
    	$request = $this->getRequest();
    	$this->view->chosen = $request->getCookie('pb_gallery');
    	
    }
    
    public function formAction(){
    	$request = $this->getRequest();
		$form = new Default_Form_CommissionGroup();
		
		if ($request->isPost()){
			if ($form->isValid($request->getPost())){
				$model = new Default_Model_CommissionGroup($form->getValues());
				$model->save();
				return $this->_helper->redirector('index');
			}
		}  else {
			if ($request->getParam('id') > 0){
				$group = new Default_Model_CommissionGroup();
				$data = $group->find($request->getParam('id'), true);
				$form->populate($data[0]);
				$group->find($request->getParam('id'));
				$this->view->images = $group->getImages();
				$this->view->gallery = $request->getParam('id');
			}
		}

		$this->view->form = $form;
    }
    
    public function addimageAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$request = $this->getRequest();
		$model = new Default_Model_CommissionGroup();
		$model->find($request->getParam('gallery'));
		$model->addImage($request->getParam('id'), $request->getParam('sortorder'));
    }
    
    public function removeimageAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$request = $this->getRequest();
		$model = new Default_Model_CommissionGroup();
		$model->find($request->getParam('gallery'));
		$model->removeImage($request->getParam('id'));
    }
    
    public function sortimagesAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$request = $this->getRequest();
		$model = new Default_Model_CommissionGroup();
		$model->find($request->getParam('gallery'));
		$model->reorderImages($request->getParam('order'));
    }
    
    public function activateAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$request = $this->getRequest();
    	$model = new Default_Model_CommissionGroup();
    	$model->find($request->getParam('gallery'));
    	$model->activate();
    }
}

