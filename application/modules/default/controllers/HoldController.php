<?php

class HoldController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->active = 'classes';
        $this->view->headTitle()->prepend('Hold');
    }

    public function indexAction()
    {
    	$class = new Default_Model_Class();
    	
    	$this->view->json = Zend_Json::encode($class->getAllActive());
    }

	public function viewAction()
	{
		$params = $this->getRequest()->getParams();
		
		$class = new Default_Model_Class($params['id']);
		
		$course = new Default_Model_Course($class->getItem('course_id'));

		$this->view->class = $class->toArray();
		$this->view->course = $course->toArray();
	}

}

