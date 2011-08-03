<?php

class Admin_TeaserController extends Zend_Controller_Action
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
    	$request = $this->getRequest();
		$form = new Default_Form_Teaser();
		
		if ($request->isPost()){
			$params = $request->getParams();
			for ($i=1;$i<12;$i++){
				$image_id = $params['image' . (intval($i)-1)];
				$model = new Default_Model_Teaser();
				$model->setId($i);
				$model->setImageId($image_id);
				$model->setPosition((intval($i)-1));
				$model->save();
			}
			
		} 
		$model = new Default_Model_Teaser();
		$teasers = $model->fetchAllActive(true);
		$pop = array();
		foreach ($teasers as $teaser){
			$pop['image' . $teaser['position']] = $teaser['image_id'];
		}
		$form->populate($pop);
		$this->view->form = $form;	
    }


}

