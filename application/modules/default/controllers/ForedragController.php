<?php

class ForedragController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->active = 'courses';
        $this->view->headTitle()->prepend('Foredrag');
    }

    public function indexAction()
    {
    	$lecture = new Default_Model_Lecture();
    	
    	$this->view->lectures = Zend_Json::encode($lecture->getAllActive());
    }


}

