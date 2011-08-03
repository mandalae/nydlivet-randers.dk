<?php
class ImageDB_Decorator_ImageDB extends Zend_Form_Decorator_Abstract
{
    /**
     * Default placement: append
     * @var string
     */
    protected $_placement = 'APPEND';

    /**
     * Get attributes to pass to image helper
     * 
     * @return array
     */
    public function getAttribs()
    {
        $attribs = $this->getOptions();

        if (null !== ($element = $this->getElement())) {
            $attribs['alt'] = $element->getLabel();
            $attribs = array_merge($attribs, $element->getAttribs());
        }

        return $attribs;
    }

    /**
     * Render a form image
     * 
     * @param  string $content 
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $placement     = $this->getPlacement();
        $separator     = $this->getSeparator();
        $name          = $element->getFullyQualifiedName();
        $attribs       = $this->getAttribs();
        $attribs['id'] = $element->getId();
        
        $imagedb = new Default_Model_Image();

		$view->headScript()->appendFile('/media/scripts/ImageDB.js');
		$image = '<div class="'.$attribs['class'].'">';
        $image .= '	<dt id="'.$name.'-label">
       				<label' . ($element->isRequired() ? ' class="required"' : '') . ' for="'.$name.'">'.$element->getLabel().'</label>
        			</dt>
        			<dd id="'.$name.'-element">
						<input type="hidden" value="'.$element->getValue().'" id="'.$name.'" name="'.$name.'" />';
		$image .= '<img src="';
		if ($element->getValue() > 0){
			$image .= 	$imagedb->getThumbnail($element->getValue(), 135, 135, true);
		}
		$image .= '" width="135" height="135" id="img_'.$name.'"/>';
		$image .= '<a href="/imagedb/?name='.$name.'" class="imagedb">Vælg nyt billede</a>';
		$image .= '</dd>';
		$image .= '</div>';

        switch ($placement) {
            case self::PREPEND:
                return $image . $separator . $content;
            case self::APPEND:
            default:
                return $content . $separator . $image;
        }
    }
}
