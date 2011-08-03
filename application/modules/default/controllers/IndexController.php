<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->active = 'frontpage';
    }

    public function indexAction()
    {
    	$news = new Default_Model_News();
    	
    	$this->view->news = $news->getAllActive();
    }


}

