<?php
class Default_Model_Content
{
	protected $_paginator 	= null;
    private $_attr = array();
    private $_id = 0;
    private $_intfields = array();
    private $_tinyInts = array();
    private $_defaults = array();
    private $_dateFields = array();
    
    public function __construct($id = null){
    	$fields = $this->setup();
    	if (!is_null($id) && is_numeric($id)){
	    	$this->_id = $id;
	    	$this->load();
	    }
     	unset($this->_attr[$this->_primaryKey]);
    }
    
    public function load($id = 0){
    	if ($id === 0){
    		$id = $this->getId();
    	} else {
    		$this->_id = $id;
    	}
    	$rowset = $this->_dbTable->find($id);
    	if (count($rowset) > 0){
	    	$current = $rowset->current();
	    	$this->bindValues($current->toArray());
	    } else {
	    	$this->_id = 0;
	    }
    }

    public function unsetAll()
    {
		$this->_id = 0;
		$this->_paginator = null;
		$this->_attr = array();
		$this->_intfields = array();
		$this->_tinyInts = array();
		$this->_defaults = array();
		$this->_dateFields = array();
    	$fields = $this->setup();
     	unset($this->_attr[$this->_primaryKey]);
    }

    public function bindValues($values){
    	if (is_array($values)){
			foreach ($values as $key => $val){
				if (array_key_exists($key, $this->_attr)){
					$this->_attr[trim($key)] = $val;
					if (in_array($key, $this->_dateFields)){
						// Make sure to give us dates as a zend date object
						if (is_numeric($val)){
							$zDate = new Zend_Date($val, Zend_Date::TIMESTAMP);
						} else {
							$zDate = new Zend_Date($val, Zend_Date::ISO_8601);
						}
						if (!is_null($val)){
							$this->_attr[trim($key)] = $zDate;
						}
					}
				}
				if (in_array($key, $this->_intfields)){
					$this->_attr[$key] = str_replace('.', ',', $val);
				}
			}
	    	if (isset($values[$this->_primaryKey])){
				$this->_id = $values[$this->_primaryKey];
			}
		}
    }
    
    public function getPaginator(){
    	return $this->_paginator;
    }
    
    private function setup(){
	    $cols = $this->_dbTable->info();
	    foreach($cols['metadata'] as $col){
	    	if (in_array($col['DATA_TYPE'], array('tinyint', 'int', 'double', 'float')) && $col['COLUMN_NAME'] != $this->_primaryKey){
	    		$this->_intfields[] = $col['COLUMN_NAME'];
	    	}
	    	if (in_array($col['DATA_TYPE'], array('datetime', 'timestamp'))){
	    		$this->_dateFields[] = $col['COLUMN_NAME'];
	    		if (is_numeric($col['DEFAULT'])){
	    			$this->_attr[trim($col['COLUMN_NAME'])] = new Zend_Date($col['DEFAULT'], Zend_Date::TIMESTAMP);
	    		} else if ($col['DEFAULT'] != null) {
	    		    $this->_attr[trim($col['COLUMN_NAME'])] = new Zend_Date($col['DEFAULT'], Zend_Date::ISO_8601);
	    		} else {
	    			// handle null value time fields
	    			$this->_attr[trim($col['COLUMN_NAME'])] = null;
	    		}
	    	} else {
	    		$this->_attr[trim($col['COLUMN_NAME'])] = $col['DEFAULT'];
			}
			$this->_defaults[trim($col['COLUMN_NAME'])] = $col['DEFAULT'];
			
	    }
    }
    
    public function save(){
    	$this->_save();
    }
    
    protected function _save(){
    	// Replace all commas with punctuations, in intfields, before saving
    	foreach ($this->_intfields as $int){ 
	    	$this->_attr[$int] = str_replace(',', '.', $this->_attr[$int]);
    	}
    	foreach ($this->_attr as $key => $value){
    		
    		// Make sure all fields default to default db value if they are empty
    		if (!isset($value) || is_null($value) || strlen($value) == 0){
    			$this->_attr[$key] = $this->_defaults[$key];
    		}
    		if (!is_null($value)){
	    		// Let's make date fields the right format for mysql
	    		if (in_array($key, $this->_dateFields)){
	    			if (is_object($value) && get_class($value) == 'Zend_Date'){
		    			$this->_attr[$key] = $value->get(Zend_Date::ISO_8601);
		    		} else {
		    			// Fallback if something goes awry
		    			if (is_numeric($value)){
		    				$zDate = new Zend_Date($value, Zend_Date::TIMESTAMP);
		    			} else {
		    				$zDate = new Zend_Date($value, Zend_Date::ISO_8601);
		    			}
		    			$this->_attr[trim($key)] = $zDate->get(Zend_Date::ISO_8601);
		    		}
	    		}
	    	}
    	}

    	if ($this->isNew()){
    		$this->_dbTable->insert($this->_attr);
    		$id = $this->loadLastId();
    	} else {
			$where = $this->_dbTable->getAdapter()->quoteInto($this->_primaryKey . ' = ?', $this->getId());
    		$this->_dbTable->getAdapter()->update($this->_name, $this->_attr, $where);
    	}
    }

    public function loadLastId(){
    	$id = $this->_dbTable->getAdapter()->lastInsertId();
    	$this->load($id);
    }
    
    public function getId(){
    	return $this->_id;
    }

    public function setId($id = 0){
    	$this->_id = $id;
    }
    
    public function isNew(){
    	return !$this->getId();
    }
    
    public function delete(){
    	if (!$this->isNew()){
    		$this->_dbTable->find($this->getId())->current()->delete();
    	}
    }
    
    public function getItem($key){
    	return $this->_attr[$key];
    }
    
    public function setKey($key, $value){
    	$this->_attr[$key] = $value;
    }
    
    public function toArray($form = false){
    	foreach ($this->_attr as $key => $value){
    		if (!is_null($value) && in_array($key, $this->_dateFields)){
    			if ($form){
	    			$this->_attr[$key] = $value->toString('d-M-Y H:m:s');
	    		} else {
	    			$this->_attr[$key] = $value->get(Zend_Date::TIMESTAMP);
	    		}
    		}
    	}
    
    	return array_merge($this->_attr, array($this->_primaryKey => $this->getId()));
    }
    
    public function getIntFields(){
    	return $this->_intfields;
    }
    
    public function loadByType($key, $value){
    	$select = $this->_dbTable->select()->where($key . ' = ?', $value);
    	$data = $this->_dbTable->fetchRow($select);
    	if (count($data) > 0){
    		$row = $data->toArray();
    		$this->load($row[$this->_primaryKey]);
    		return true;
    	}
    	return false;
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

		$link = preg_replace('/(\W){1,}/', $spaceCharacter, $link);
		$link = preg_replace('/[^a-zA-Z0-9_-]+/i', '', $link);

		$link = trim($link, '-');

		// if any characters we did not anticipate are left
		$link = htmlentities($link);

		return $link;
	}
	
	/**
     * Generate free seo string based on name and ID
     *
     * @param string $name
     * @param integer $id
     * @return string seo string
     * @access public
     */
    public function generateSEO($name, $id = 0)
    {
		$seo = $this->seo($name);
		// check in db to see if seo exists
		$select = $this->_dbTable->select()->where('seo = ?', $seo);
		if ($id > 0){
			$select->where($this->_primaryKey . ' != ?', $id);
		}
		$rows = $this->_dbTable->fetchAll($select);
		if (count($rows) > 0){
			$seo .= 1;
			return $this->generateSEO($seo, $id);
		}
		return $seo;
    }
    
    public function formatHtmlTableContent($paginator, $buttons = array())
    {    
		$data = $paginator->getCurrentItems();		
		
    	$tableData = array();
    	foreach ($data as $key => $item){
    		$links = array();
    		foreach ($buttons as $k => $button)
    		{
    			$show = true;
    			
    			// parse rules if any, and check to see if link should be listed?
    			if (isset($button['rule'])) 
    			{
    				$show = false;
    				$rule = '';
					foreach ($button['rule'] as $k => $r)
					{
						$rule .= "\$item[\$button['rule'][$k]['field']]" . " " . 
							$r['exp'] . " " . "\$button['rule'][$k]['value']" . 
							(isset($r['type']) ? ' ' . $r['type'] . ' ' : '');
					}
					if (eval('return (' . $rule . ');')) {
						$show = true;
					}
    			}
    			
    			// configure url
    			if (isset($button['custom']) && is_string($button['custom'])) {
    				$url = $button['url'].$item[$button['custom']];
    			}
    			else if (isset($button['custom']) && is_array($button['custom'])) {
    				$url = $button['url'];
    				foreach ($button['custom'] as $k2 => $value)
    				{    					
    					$url .= '/' . $value . '/' . $item[$k2];
    				}
    			}
    			else {
    				$url = $button['url'].''.$this->_primaryKey.'/'.$item[$this->_primaryKey];
    			}
    			
    			// add link
    			if ($show) {
					$links[] = '<a href="'.$url.'" title="'.$this->translate($button['name']).'" class="table_button'.(isset($button['class'])?' ' . $button['class'] : '').'">'.(isset($button['img']) ? '<img src="'.$button['img'].'" alt="'.$this->translate($button['name']).'" height="16" width="16" border="0" />': $this->translate($button['name'])).'</a>';
    			}
    		}
    		$tableData[$key] = array();
    		$tableData[$key]['options'] = array(
    			'value' => $links,
    			'type' => 'options',
    			'class' => 'options'
    		);
    		foreach ($item as $k => $i){
    	    	$tableData[$key][$k] = array('value' => $i, 'type' => 'text', 'class' => $k);
    	    }
    	    
    	}
    	
    	return $tableData;
    	
    }
    
    // Translate keys
    public function translate($key)
    {
    	$registry = Zend_Registry::getInstance();
    	$translate = $registry->Zend_Translate;
    	return $translate->translate($key);
    }
}