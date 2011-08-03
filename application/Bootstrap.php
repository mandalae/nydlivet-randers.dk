<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initAutoload()
	{
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default_',
            'basePath'  => dirname(__FILE__) . '/modules/default',
        ));
        return $autoloader;
    }

	/**
     * Frontcontroller
     */
	protected function _initFront(){
		$frontController = Zend_Controller_Front::getInstance();

		$response = new Zend_Controller_Response_Http;
		$response->setHeader('Cache-Control', 'max-age: 3600, must-revalidate');
		$response->setHeader('Expires', gmdate("D, d M Y H:i:s", time() + 3600) . ' GMT');
		$response->setHeader('Last-Modified', gmdate("D, d M Y H:i:s") . ' GMT');
		$frontController->setResponse($response);

		$frontController->setControllerDirectory(
			array(
				'default' => APPLICATION_PATH . '/modules/default/controllers',
				'admin' => APPLICATION_PATH . '/modules/admin/controllers',
				'imagedb' => APPLICATION_PATH . '/modules/imagedb/controllers'
			)
		);
		
		$router = $frontController->getRouter();
		$router->addRoute('logind',  
			new Zend_Controller_Router_Route('auth/login', array('controller' => 'auth', 'action' => 'login', 'module' => 'default'))
		);
		$router->addRoute('logout',  
			new Zend_Controller_Router_Route('auth/logout', array('controller' => 'auth', 'action' => 'logout', 'module' => 'default'))
		);
		$router->addRoute(
			'id', 
			new Zend_Controller_Router_Route('/hold/detaljer/:id', 
			array('id' => 0, 'controller' => 'hold', 'action' => 'view')),
			array('id' => '\d+')
		);

		Zend_Layout::startMvc(APPLICATION_PATH . '/layouts/scripts');

		$view = Zend_Layout::getMvcInstance()->getView();
		$view->addHelperPath(APPLICATION_PATH . '/modules/default/views/helpers');
		$view->setEncoding('UTF-8');
		$view->doctype('XHTML1_STRICT');
		$view->headMeta()->appendHttpEquiv('Content-type', 'text/html;charset=UTF-8')
				->appendName('keywords', 'nyd livet, livet, liv');
		$view->headTitle('Nyd livet')->setSeparator(' - '); 
		$view->headLink()->appendStylesheet('/css/blueprint/style_ie.css', 'screen,projection', 'lt IE 7')
						 ->appendStylesheet('/css/style.css', 'screen,projection')
						 ->appendStylesheet('/js/fullcalendar/fullcalendar.css');
		$view->headScript()->appendFile('/js/jquery-1.5.2.min.js')
						   ->appendFile('/js/jquery-ui-1.8.11.custom.min')
						   ->appendFile('/js/cufon-yui.js')
						   ->appendFile('/js/cufon-replace.js')
						   ->appendFile('/js/Bauhaus_Md_BT_400.font.js')
						   ->appendFile('/js/easyTooltip.js')
						   ->appendFile('/js/fullcalendar/fullcalendar.min.js')
						   ->appendFile('/js/site.js');
						   
	}
	
	/**
     * init registry
     */
    protected function _initRegistry()
    {
		$configuration = new Zend_Config_Ini(
			APPLICATION_PATH . '/configs/application.ini', 
			APPLICATION_ENV);
		
		$registry = Zend_Registry::getInstance();
		$registry->configuration = $configuration;
    }

}

