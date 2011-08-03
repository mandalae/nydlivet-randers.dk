<?php
class Default_Model_ImageMapper
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
            $this->setDbTable('Default_Model_DbTable_Image');
        }
        return $this->_dbTable;
    }

    public function save(Default_Model_Image $image)
    {
        $data = array(
            'headline'   => $image->getHeadline(),
            'text' => $image->getText(),
            'ext' => $image->getExt(),
            'width' => $image->getWidth(),
            'height' => $image->getHeight() 
        );

        $id = $image->getId();
		$db = $this->getDbTable();
        if (intval($id) == 0) {
            unset($data['id']);
            $id = $db->insert($data);
        } else {
            $db->update($data, array('id = ?' => $id));
        }
        return $id;
    }

    public function find($id, Default_Model_Image $image, $toArray = false)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
       	if ($toArray){
       		return $result->toArray();
       	}
        $row = $result->current();
        $image->setId($row->id)
                  ->setHeadline($row->headline)
                  ->setText($row->text)
                  ->setExt($row->ext)
                  ->setWidth($row->width)
                  ->setHeight($row->height)
                  ->setDeleted($row->deleted);
    }

    public function fetchAll($toArray = false)
    {
        $resultSet = $this->getDbTable()->fetchAll();
        if ($toArray){
        	return $resultSet->toArray();
        } else {
	        $entries   = array();
	        foreach ($resultSet as $row) {
	            $entry = new Default_Model_Image();
	            $entry->setId($row->id)
	                  ->setHeadline($row->headline)
	                  ->setText($row->text)
	                  ->setExt($row->ext)
	                  ->setWidth($row->width)
	                  ->setHeight($row->height)
	                  ->setDeleted($row->deleted)
	                  ->setMapper($this);
	            $entries[] = $entry;
	        }
	        return $entries;
        }
    }

    public function fetchAllActive(){
    	$select = $this->getDbTable()->select()
    								->from(array('i' => 'image'), array('*'))
    								->where('i.deleted = 0')
    								->order('i.id DESC');
    	$resultSet = $this->getDbTable()->fetchAll($select);
    	$entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Default_Model_Image();
            $entry->setId($row->id)
                  ->setHeadline($row->headline)
                  ->setText($row->text)
                  ->setExt($row->ext)
                  ->setWidth($row->width)
                  ->setHeight($row->height)
                  ->setDeleted($row->deleted)
                  ->setMapper($this);
            $entries[] = $entry; 
        }
        return $entries;
    }
}
