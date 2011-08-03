<?php

class Default_Model_Class extends Default_Model_Content {
	
	protected $_dbTable;
    protected $_name = 'classes';
    protected $_primaryKey = 'id';

	public function __construct($id = null)
	{
		$this->_dbTable = new Default_Model_DbTable_Class;
		
		parent::__construct($id);
	}
	
	public function getAllActive($page = 1, $limit = 100, $sort_field = 'mt.active', $sort_direction = 'desc')
	{
		if (!$page || $page == 0){
			$page = 1;
		}
		if (!$limit || $limit == 0){
			$limit = 100;
		}
		
		$select = $this->_dbTable->select()->setIntegrityCheck(false)
								->from(array("mt" => $this->_name), array('mt.*'))
								->joinLeft(array('jt' => 'courses'), 'mt.course_id = jt.id', array('jt.name', 'jt.description', 'jt.price'))
								->where('mt.active > 0')
								->order($sort_field . ' ' . $sort_direction);

		$paginatorSelect = new Zend_Paginator_Adapter_DbTableSelect($select);
        $paginator = new Zend_Paginator($paginatorSelect);
        $paginator->setPageRange(10);
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($page);
		
        return $paginator;
	}
	
}