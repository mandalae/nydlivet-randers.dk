<?php

class BrugerController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->active = 'users';
        $this->view->headTitle()->prepend('Bruger');
    }

    public function indexAction()
    {
    	/*$class = new Default_Model_Class();
    	
    	$this->view->classes = $class->getAllActive();*/
    }
    
    public function opretAction()
    {
    	$request = $this->getRequest();
		$form    = new Default_Form_User();
		$form->setAttrib('id', 'contacts-form');
		
        if ($this->getRequest()->isPost()) {
        	$values = $request->getPost();
            if ($form->isValid($values)) {
				
				$values['password'] = hash('sha256', $values['password']);
				$user = new Default_Model_User();
				$user->bindValues($values);
				$user->save();
            }
        }
		if (!Zend_Auth::getInstance()->hasIdentity()){
        	$this->view->form = $form;
		}
    }


}

