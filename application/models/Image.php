<?php
class Default_Model_Image
{
	protected $_headline;
    protected $_text;
    protected $_deleted;
    protected $_ext;
    protected $_width;
    protected $_height;
    protected $_id;
    protected $_mapper;
    protected $_im = null;
	protected $_conf = null;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
        $this->_im = new Imagick();
		$this->_conf = Zend_Registry::get('configuration')->gfx;
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

    public function setText($text)
    {
        $this->_text = (string) $text;
        return $this;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function setHeadline($headline)
    {
        $this->_headline = (string) $headline;
        return $this;
    }

    public function getHeadline()
    {
        return $this->_headline;
    }

    public function setExt($ext)
    {
        $this->_ext = $ext;
        return $this;
    }

    public function getExt()
    {
        return $this->_ext;
    }

    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setDeleted($ts)
    {
        $this->_deleted = $ts;
        return $this;
    }

    public function getDeleted()
    {
        return $this->_deleted;
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

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_ImageMapper());
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

    public function fetchAllActive()
    {
    	return $this->getMapper()->fetchAllActive();
    }

    public function upload()
    {
    	$upload = new Zend_File_Transfer_Adapter_Http();
		$upload->addValidator('Size', false, array('1B', '40MB'));
		$upload->addValidator('Extension',
		                      false,
		                      array('extension1' => 'jpg,gif,png,tif,jpeg,tiff',
		                            'case' => false));
	
		if (!$upload->isValid()) {
		    print_r($upload->getMessages());
		    die();
		}
		try {
		    $upload->receive();
			if (!file_exists((string)$upload->getFileName())) {
				die('file not found');
			}
		
			// get image info
			$info = $this->identifyImage($upload->getFileName());
			$t = strtolower(trim(substr($info['format'], 0, strpos($info['format'], '(') -1)));
			switch ($t) {
				case 'tiff':
					$type = 'tif';
					break;
				
				case 'jpeg':
					$type = 'jpg';
					break;
				
				default:
					$type = $t;
					break;
			}
		
			// create image in db
			$data = array(
				'width' => $info['geometry']['width'],
				'height' => $info['geometry']['height'],
				'ext' => $type,
				'headline' => basename($upload->getFileName(), $type),
				'deleted' => 0
			);
			$image = new Default_Model_Image($data);
			$image_id = $image->save();
			
			// move file
			$old = $upload->getFileName();
			$new = $this->generateOrigFilename($image_id, $type);
			rename($old, $new);
			
			$this->getThumbnail($image_id, 50, 75, true);
			$this->getThumbnail($image_id, 434, 330, true);
			$thumb = $this->getThumbnail($image_id, 135, 135, true);
			
			echo $thumb;

		} catch (Zend_File_Transfer_Exception $e) {
		    $e->getMessage();
		}
    }
    
    // {{{ getCropThumbnail()

	/**
	 * Generate cropped thumbnail and return image cache path
	 */
	public function getThumbnail($id, $width, $height, $crop = false)
	{
		if ($id){
			$this->find($id);
			$origfile = $this->generateOrigFilename($id, $this->getExt());
			$cachefile = $this->generateCacheFilename($id, $width, $height, $crop);
	
			if (!file_exists($origfile)) {
				// TODO: add error handling and error image
				return '';
			} elseif (!file_exists($this->_conf->cache->path.'/'.$cachefile)) {
				$this->_im->clear();
				$this->_im->readImage($origfile);
				$info = $this->_im->identifyImage($origfile);
	
				if ($info['colorSpace'] == 'CMYK') {
					$profiles = $this->_im->getImageProfiles('*', false); // get profiles
					$has_icc_profile = (array_search('icc', $profiles) !== false); // we're interested if ICC profile(s) exist
	
					if ($has_icc_profile === false) {
						// image does not have CMYK ICC profile, we add one
						$icc_cmyk = file_get_contents($this->_conf->icc->path.'/USWebCoatedSWOP.icc');
						$this->_im->profileImage('icc', $icc_cmyk);
					}
	
					// Then we need to add RGB profile
					$icc_rgb = file_get_contents($this->_conf->icc->path.'/AdobeRGB1998.icc');
					$this->_im->profileImage('icc', $icc_rgb);
				}
	
				$this->_im->setImageColorSpace(Imagick::COLORSPACE_RGB);
				if ($width == 0){
					$width = $this->calculate('width', $height, $info['geometry']);
				}
				if ($height == 0){
					$height = $this->calculate('height', $width, $info['geometry']);
				}
				
				if ($width > $info['geometry']['width']){
					$width = $info['geometry']['width'];
				}
				if ($height > $info['geometry']['height']){
					$height = $info['geometry']['height'];
				}
				
				
				if ($crop) {
					$this->_im->cropThumbnailImage($width, $height);
				} else {
					$this->_im->thumbnailImage($width, $height);
				}
	
				$this->_im->setImageFormat("jpg");
				$this->_im->setCompression(Imagick::COMPRESSION_JPEG);
				$this->_im->setCompressionQuality(80);
				$this->_im->writeImage($this->_conf->cache->path.'/'.$cachefile);
				$this->_im->clear();
			}		
			
			return $this->_conf->cache->url.'/'.$cachefile;
		} else {
			return '';
		}
	}

    // }}}
    
    public function calculate($what, $from, $info){
    	$data = 0;
    	switch ( $what ){
    		case 'width':
    			$data = ($info['width'] / $info['height']) * $from;
    		  	break;
    		case 'width':
    			$data = ($info['height'] / $info['width']) * $from;
    		  	break;
    	}
    	return $data;
    }
    
    // {{{ generateOrigFilename()

	/**
	 * Generate original filename based on id and type
	 */
	public function generateOrigFilename($id, $type)
	{		
		$dir = $this->getIdDir($id, true, true);
		
		return $dir.$id.'.'.$type;
	}

    // }}}

    // {{{ generateCacheFilename()

	/**
	 * Generate cache filename
	 */
	public function generateCacheFilename($id, $width, $height, $crop)
	{		
		$dir = $this->getIdDir($id);
		return $dir.$id.'-'.$width.'x'.$height.($crop ? '-crop' : '').'.jpg';
	}

    // }}}
    
    // {{{ identifyImage()

	/**
	 * Get image properties from file
	 */
	public function identifyImage($file)
	{
		$this->_im->readImage($file);
		return $this->_im->identifyImage();
	}

    // }}}

    // {{{ getIdDir()

	/**
	 * Generate dir for files
	 */
	public function getIdDir($id, $full = false, $orig = false)
	{
		$dir = chunk_split($id, 1, '/');
		if ($orig) {
			$fulldir = $this->_conf->upload->path.'/'.$dir;
		} else {
			$fulldir = $this->_conf->cache->path.'/'.$dir;
		}
		
		if (!file_exists($fulldir)) {
			mkdir($fulldir, 0777, true);
		}
		
		return ($full) ? $fulldir : $dir;
	}

    // }}}
    
    // {{{ getHtmlThumbnail()

	/**
	 * Get thumbnail image html tag
	 */
	public function getHtmlThumbnail($id, $width, $height, $alt = '', $crop = true)
	{
		$file = $this->getThumbnail($id, $width, $height, $crop);
		if (strlen(trim($file)) > 0){
			$html = '<img src="'.$file.'" alt="'.$alt.'" ' . ($width > 0 ? 'width="'.$width.'"' : '') .' ' . ($height > 0 ? 'height="'.$height.'"' : '') .'/>';
		} else {
			$html = '';
		}
		
		return $html;
	}

    // }}}
}
