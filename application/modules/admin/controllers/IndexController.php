<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout('admin');
		if (Zend_Auth::getInstance()->hasIdentity()){
			$this->view->logout = true;
		}
    }

    public function indexAction()
    {
		$request = $this->getRequest();
		$form    = new Default_Form_Auth();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {

				$values = $form->getValues();
				$adapter = Zend_Db_Table::getDefaultAdapter();
				$auth    = Zend_Auth::getInstance();
				$authAdapter = new Zend_Auth_Adapter_DbTable(
						$adapter,
						'users',
						'username',
						'password'
					);
				$authAdapter->setIdentity($values['username'])
							->setCredential(hash('sha256', $values['password']));
				$result = $auth->authenticate($authAdapter);

				$this->_helper->redirector('index');
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
        $this->_helper->redirector('index');
    }


}

