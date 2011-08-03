<?php
class Default_Model_Teaser
{
	protected $_position;
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

    public function getId()
    {
        return $this->_id;
    }

    public function setImageId($imageId)
    {
        $this->_image_id = $imageId;
        return $this;
    }

    public function getImageId()
    {
        return $this->_image_id;
    }
    
    public function setPosition($position)
    {
        $this->_position = $position;
        return $this;
    }

    public function getPosition()
    {
        return $this->_position;
    }
    
    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_TeaserMapper());
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

}
