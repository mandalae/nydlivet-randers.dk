<?php
class Default_Model_CommissionGroup
{
	protected $_name;
    protected $_id;
    protected $_mapper;
    protected $_active;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Commission Group property - ' . $method);
        }
        $this->$method($value);
    }

    public function __get($name) 
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Commission Group property - ' . $method);
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    public function setActive($active){
    	$this->_active = $active;
    	return $this;
    }
    
    public function getActive(){
    	return $this->_active;
    }
    
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }
    
    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_CommissionGroupMapper());
        }
        return $this->_mapper;
    }

    public function save()
    {
        return $this->getMapper()->save($this);
    }

    public function find($id, $toArray = false)
    {
    	if (!$toArray){
	        $this->getMapper()->find($id, $this, $toArray);
	        return $this;
    	} else {
    		return $this->getMapper()->find($id, $this, $toArray);
    	}
    }
    
    public function findBySeo($seo, $toArray = false)
    {
    	if (!$toArray){
	        $this->getMapper()->findBySeo($seo, $this, $toArray);
	        return $this;
    	} else {
    		return $this->getMapper()->findBySeo($seo, $this, $toArray);
    	}
    }

    public function fetchAll($toArray = false, $active = true)
    {
        return $this->getMapper()->fetchAll($toArray, $active);
    }

    public function getImages($offset = 0, $limit = 0)
    {
	    return $this->getMapper()->getImages($offset, $limit, $this);
    }
    
    public function addImage($id, $sortorder){
    	return $this->getMapper()->addImage($id, $sortorder, $this);
    }
    
    public function removeImage($id){
    	return $this->getMapper()->removeImage($id, $this);
    }
    
    public function reorderImages($order){
    	return $this->getMapper()->reorderImages($order, $this);
    }
    
    public function activate(){
    	$this->getMapper()->activate($this);
    }

}
