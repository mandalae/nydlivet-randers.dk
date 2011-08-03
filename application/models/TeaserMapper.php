<?php
class Default_Model_TeaserMapper
{
    protected $_dbTable;
	
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Default_Model_DbTable_Teaser');
        }
        return $this->_dbTable;
    }

    public function save(Default_Model_Teaser $teaser)
    {
        $data = array(
            'position'   => $teaser->getPosition(),
            'image_id' => $teaser->getImageId()
        );

        $id = $teaser->getId();
		$db = $this->getDbTable();
        if (intval($id) == 0) {
            unset($data['id']);
            $id = $db->insert($data);
        } else {
            $db->update($data, array('id = ?' => $id));
        }
        return $id;
    }

    public function find($id, Default_Model_Teaser $teaser, $toArray = false)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
       	if ($toArray){
       		return $result->toArray();
       	}
        $row = $result->current();
        $teaser->setId($row->id)
                  ->setImageId($row->image_id)
                  ->setPosition($row->position)
                  ->setMapper($this);
    }

    public function fetchAll($toArray = false)
    {
        $resultSet = $this->getDbTable()->fetchAll();
        if ($toArray){
        	return $resultSet->toArray();
        } else {
	        $entries   = array();
	        foreach ($resultSet as $row) {
	            $entry = new Default_Model_Teaser();
	            $entry->setId($row->id)
                  ->setImageId($row->image_id)
                  ->setPosition($row->position)
                  ->setMapper($this);
	            $entries[] = $entry;
	        }
	        return $entries;
        }
    }

    public function fetchAllActive($toArray = false){
    	$select = $this->getDbTable()->select()
    								->from(array('t' => 'teaser'), array('*'))
    								->order('t.id ASC');
    	$resultSet = $this->getDbTable()->fetchAll($select);
    	if ($toArray){
    		return $resultSet->toArray();
    	} else {
	    	$entries   = array();
	        foreach ($resultSet as $row) {
	            $entry = new Default_Model_Teaser();
	            $entry->setId($row->id)
	                  ->setImageId($row->image_id)
	                  ->setPosition($row->position)
	                  ->setMapper($this);
	            $entries[] = $entry;
	        }
	        return $entries;
	    }
    }
}
