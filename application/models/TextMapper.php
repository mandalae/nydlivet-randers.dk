<?php
class Default_Model_TextMapper
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
            $this->setDbTable('Default_Model_DbTable_Text');
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

	public function generateSeo(Default_Model_Text $text, $string){
		$select = $this->getDbTable()->select()
					->from(array('t' => 'text'), array('*'))
					->where('t.seo = ?', array($this->seo($string)))
					->where('t.id != ?', array($text->getId()));
		$result = $this->getDbTable()->fetchAll($select);
		if (count($result) == 0){
			return $this->seo($string);
		} else {
			$newstring = $string.'1';
			return $this->generateSeo($newstring);
		}
	}

    public function save(Default_Model_Text $text)
    {
    	
    
        $data = array(
            'headline'   => $text->getHeadline(),
            'text' => $text->getText(),
            'seo' => $this->generateSeo($text, $text->getHeadline()),
            'image_id' => $text->getImageId()
        );

        $id = $text->getId();
		$db = $this->getDbTable();
        if (intval($id) == 0) {
            unset($data['id']);
            $id = $db->insert($data);
        } else {
            $db->update($data, array('id = ?' => $id));
        }
        return $id;
    }

    public function find($id, Default_Model_Text $text, $toArray = false)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
       	if ($toArray){
       		$resultArr = $result->toArray();
       		$resultArr[0]['imageId'] = $resultArr[0]['image_id'];
       		unset($resultArr[0]['image_id']);
       		return $resultArr;
       	}
        $row = $result->current();
        $text->setId($row->id)
                  ->setHeadline($row->headline)
                  ->setText($row->text)
                  ->setSeo($row->seo)
                  ->setImageId($row->image_id)
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
	            $entry = new Default_Model_Text();
	            $entry->setId($row->id)
                  ->setHeadline($row->headline)
                  ->setText($row->text)
                  ->setSeo($row->seo)
                  ->setImageId($row->image_id)
                  ->setMapper($this);
	            $entries[] = $entry;
	        }
	        return $entries;
        }
    }

    public function fetchAllActive($toArray = false){
    	$select = $this->getDbTable()->select()
    								->from(array('t' => 'text'), array('*'))
    								->order('t.id ASC');
    	$resultSet = $this->getDbTable()->fetchAll($select);
    	if ($toArray){
    		return $resultSet->toArray();
    	} else {
	    	$entries   = array();
	        foreach ($resultSet as $row) {
	            $entry = new Default_Model_Teaser();
	            $entry->setId($row->id)
	                  ->setHeadline($row->headline)
	                  ->setText($row->text)
	                  ->setSeo($row->seo)
	                  ->setImageId($row->image_id)
	                  ->setMapper($this);
	            $entries[] = $entry;
	        }
	        return $entries;
	    }
    }
    
    public function findBySeo($seo, Default_Model_Text $text){
    	$select = $this->getDbTable()->select()
    				->from(array('t' => 'text'), array('*'))
    				->where('t.seo = ?', array($seo));
    	$result = $this->getDbTable()->fetchAll($select);
    	if (0 == count($result)) {
            return;
        }
    	$row = $result->current();
        $text->setId($row->id)
                  ->setHeadline($row->headline)
                  ->setText($row->text)
                  ->setImageId($row->image_id)
                  ->setSeo($row->seo);
    }
}
