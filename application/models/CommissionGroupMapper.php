<?php
class Default_Model_CommissionGroupMapper
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
            $this->setDbTable('Default_Model_DbTable_CommissionGroup');
        }
        return $this->_dbTable;
    }
    
    public function seo($link) {
		$spaceCharacter = '-';

		// Make all links lowercase
		$link = mb_strtolower($link, 'UTF-8');

		$trans = array(
			"æ" => "ae", "ø" => "oe", "å" => "aa",
			"á" => "a",	"é" => "e",	"ë" => "e",	"ñ" => "n",
			"ö" => "o",	"ü" => "u",	"ß" => "s", "à" => "a",
			"á" => "a", "â" => "a", "ã" => "a", "ä" => "a",
			"ç" => "c", "è" => "e", "é" => "e", "ê" => "e",
			"ë" => "e", "ì" => "i", "í" => "i", "î" => "i",
			"ï" => "i", "ð" => "o", "ñ" => "n", "ò" => "o",
			"ó" => "o", "ô" => "o", "õ" => "o", "ö" => "o",
			"ù" => "u", "ú" => "u", "û" => "u", "ü" => "u",
			"ý" => "y", "ÿ" => "y", "²" => "2", "&" => "og");

		$link = strtr($link, $trans);

		// info about regex http://www.robertbolton.com/seo-friendly-urls-with-php
		$link = preg_replace('/(\W){1,}/', $spaceCharacter, $link);
		$link = preg_replace('/[^a-zA-Z0-9_-]+/i', '', $link);

		$link = trim($link, '-');

		// if any characters we did not anticipate are left
		$link = htmlentities($link);

		return $link;
	}

	public function generateSeo(Default_Model_CommissionGroup $group, $string){
		$select = $this->getDbTable()->select()
					->from(array('ig' => 'commission_group'), array('*'))
					->where('ig.seo = ?', array($this->seo($string)))
					->where('ig.id != ?', array($group->getId()));
		$result = $this->getDbTable()->fetchAll($select);
		if (count($result) == 0){
			return $this->seo($string);
		} else {
			$newstring = $string.'1';
			return $this->generateSeo($newstring);
		}
	}

    public function save(Default_Model_CommissionGroup $group)
    {
        $data = array(
            'name'   => $group->getName(),
            'seo' 	=> $this->generateSeo($group, $group->getName())
        );

        $id = $group->getId();
		$db = $this->getDbTable();
        if (intval($id) == 0) {
            unset($data['id']);
            $id = $db->insert($data);
        } else {
            $db->update($data, array('id = ?' => $id));
        }
        return $id;
    }

    public function find($id, Default_Model_CommissionGroup $group, $toArray = false)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
       	if ($toArray){
       		return $result->toArray();
       	}
        $row = $result->current();
        $group->setId($row->id)
                  ->setName($row->name);
    }
    
    public function findBySeo($seo, Default_Model_CommissionGroup $group, $toArray = false)
    {
        $select = $this->getDbTable()->select()
        					->from(array('ig' => 'commission_group'), array('*'))
        					->where('seo = ?', $seo);
        $result = $this->getDbTable()->fetchAll($select);
        if (0 == count($result)) {
            return;
        }
       	if ($toArray){
       		return $result->toArray();
       	}
        $row = $result->current();
        $group->setId($row->id)
                  ->setName($row->name)
                  ->setActive($row->active);
    }

    public function fetchAll($toArray = false, $active = true)
    {	
    	if ($active){
	    	$select = $this->getDbTable()->select()->where('active > 0')->order('active DESC');
	    } else {
	    	$select = $this->getDbTable()->select()->order('active DESC');
	    }
        $resultSet = $this->getDbTable()->fetchAll($select);
        if ($toArray){
        	return $resultSet->toArray();
        } else {
	        $entries   = array();
	        foreach ($resultSet as $row) {
	            $entry = new Default_Model_CommissionGroup();
	            $entry->setId($row->id)
	                  ->setName($row->name)
	                  ->setActive($row->active)
	                  ->setMapper($this);
	            $entries[] = $entry;
	        }
	        return $entries;
        }
    }
    
    public function getImages($offset = 0, $limit = 0, Default_Model_CommissionGroup $group){
    	$db = $this->getDbTable();
    	$select = $db->select();
		$select->setIntegrityCheck(false)
				->from(array('i' => 'image'), array('i.id', 'i.headline'))
				->join(array('igb' => 'commission_group_bind'), 'i.id = igb.image_id', 'igb.image_id')
				->join(array('ig' => 'commission_group'), 'igb.commission_group = ig.id', 'ig.name')
				->where('ig.id = ?', $group->getId())
				->order('igb.sortorder ASC');
    	if ($limit > 0){
    		$select->limit($limit, $offset);
    	}
    	$rows = $db->fetchAll($select);
    	return $rows->toArray();
    }
    
    public function addImage($id, $sortorder, Default_Model_CommissionGroup $group){
    	$this->setDbTable('Default_Model_DbTable_CommissionGroupBind');
    	$db = $this->getDbTable();
    	$data = array(
    					'image_id' => $id,
    					'commission_group' => $group->getId(),
    					'sortorder' => $sortorder
    				);
    	$db->insert($data);
    }
    
    public function removeImage($id, Default_Model_CommissionGroup $group){
    	$this->setDbTable('Default_Model_DbTable_CommissionGroupBind');
    	$db = $this->getDbTable();
    	$db->delete(array('commission_group = ' . $group->getId(), 'image_id = ' . $id));
    }
    
    public function reorderImages($order, Default_Model_CommissionGroup $group){
    	$orders = explode(';', $order);
    	foreach ($orders as $orderitem){
    		if (strlen($orderitem) > 0){
    			$item = explode(':', $orderitem);
    			$this->setDbTable('Default_Model_DbTable_CommissionGroupBind');
		    	$db = $this->getDbTable();
		    	$data = array(
		    					'sortorder' => $item[1]
		    				); 
		    	$where = array();
		    	$where[] = "image_id = " . intval($item[0]);
		    	$where[] = "commission_group = ".$group->getId();
		    	$db->update($data, $where);
		    }
    	}
    }
    
    public function activate(Default_Model_CommissionGroup $group){
    	$row = $this->getDbTable()->fetchRow($this->getDbTable()->select()->where('id = ?', $group->getId()));
    	if ($row->active > 0){
    		$row->active = 0;
    	} else {
	    	$row->active = time();
	    }
    	$row->save();
    }
}
