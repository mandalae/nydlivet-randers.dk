<?php

class KontaktController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->active = 'contact';
        $this->view->headTitle()->prepend('Kontakt');
    }

    public function indexAction()
    {
    	$request = $this->getRequest();
    	$form = new Default_Form_Contact();
    	$form->setAttrib('id', 'contacts-form');
    	
    	if ($request->isPost()){
    		$data = $request->getParams();
    		if ($form->isValid($data)){
    			$from = isset($data['from']) ? $data['from'] : '';
    			
    			$mail = new Zend_Mail();
    			$mail->addTo('c@rpediem.com');
    			$mail->setBodyText($data['text']);
    			$mail->setSubject($data['subject']);
    			$mail->setFrom($data['fromEmail'], $from);
    			$mail->send();
    			
    			$this->_helper->redirector('index', 'kontakt');
    			
    		} else {
    			$form->populate($data);
    		}
    	}

    	$this->view->form = $form;
    	
    }


}

