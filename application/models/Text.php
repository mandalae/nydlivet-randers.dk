<?php
class Default_Model_Text
{
	protected $_headline;
    protected $_text;
    protected $_seo;
    protected $_image_id;
    protected $_id;
    protected $_mapper;

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
            throw new Exception('Invalid Image property - ' . $method);
        }
        $this->$method($value);
    }

    public function __get($name) 
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Image property - ' . $method);
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
    
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }

    public function getImageId()
    {
        return $this->_image_id;
    }
    
    public function setImageId($image_id)
    {
        $this->_image_id = (int) $image_id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setHeadline($headline)
    {
        $this->_headline = $headline;
        return $this;
    }

    public function getHeadline()
    {
        return $this->_headline;
    }
    
    public function setText($text)
    {
        $this->_text = $text;
        return $this;
    }

    public function getText()
    {
        return $this->_text;
    }
    
    public function setSeo($seo)
    {
        $this->_seo = $seo;
        return $this;
    }

    public function getSeo()
    {
        return $this->_seo;
    }
    
    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_TextMapper());
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

    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
    }

    public function fetchAllActive($toArray = false)
    {
	    return $this->getMapper()->fetchAllActive($toArray);
    }
    
    public function findBySeo($seo){
    	return $this->getMapper()->findBySeo($seo, $this);
    }

}
