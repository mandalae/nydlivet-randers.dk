<?php

class Admin_TextController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout('admin');
		if (Zend_Auth::getInstance()->hasIdentity()){
			$this->view->logout = true;
		} else {
			return $this->_helper->redirector('index', 'admin');
		}
    }
    
    public function indexAction()
    {
		$text = new Default_Model_Text();
		$this->view->entries = $text->fetchAll();
    }

    public function formAction()
    {
    	$request = $this->getRequest();
		$form = new Default_Form_Text();
		
		if ($request->isPost()){
			if ($form->isValid($request->getPost())){
				$model = new Default_Model_Text($form->getValues());
				$model->save();
				return $this->_helper->redirector('index');
			}
		}  else {
			if ($request->getParam('id') > 0){
				$text = new Default_Model_Text();
				$data = $text->find($request->getParam('id'), true);
				$form->populate($data[0]);
			}
		}

		$this->view->form = $form;	
    }
    
    public function deleteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$request = $this->getRequest();
		
		if ($request->getParam('id') > 0){
			$model = new Default_Model_Text();
			$model->find($request->getParam('id'));
			$model->delete();
			return $this->_helper->redirector('index');
		}
	}

}

