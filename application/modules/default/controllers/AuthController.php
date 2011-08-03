<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
		if (Zend_Auth::getInstance()->hasIdentity()){
			$this->view->logout = true;
		}
		$this->view->active = 'login';
		$this->view->headTitle()->prepend('Log ind');
    }

    public function loginAction()
    {
		$request = $this->getRequest();
		$form    = new Default_Form_Auth();
		$form->setAttrib('id', 'contacts-form');
		
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {

				$values = $form->getValues();
				$adapter = Zend_Db_Table::getDefaultAdapter();
				$auth    = Zend_Auth::getInstance();
				$authAdapter = new Zend_Auth_Adapter_DbTable(
						$adapter,
						'users',
						'email',
						'password'
					);
				$authAdapter->setIdentity($values['username'])
							->setCredential(hash('sha256', $values['password']));
				$result = $auth->authenticate($authAdapter);

				$this->_helper->redirector('index', 'index');
            }
        }
		if (!Zend_Auth::getInstance()->hasIdentity()){
        	$this->view->form = $form;
		}
    }
    
    public function logoutAction(){
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
    	Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index', 'index');
    }


}

