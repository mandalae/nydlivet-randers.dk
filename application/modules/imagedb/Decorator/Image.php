<?php
class ImageDB_Decorator_Image extends Zend_Form_Decorator_Abstract
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

		$view->headScript()->appendFile('/media/swfupload/swfupload.js')
							->appendFile('/media/swfupload/swfupload.cookies.js')
							->appendFile('/media/swfupload/swfupload.queue.js')
							->appendFile('/media/swfupload/handlers.js')
							->appendFile('/media/scripts/Image.js')
							->appendScript('var toolbar = false;
											$(document).ready(function(){
												initImageUpload();
											});');

        $image = '	<dt id="'.$name.'-label">
       				<label' . ($element->isRequired() ? ' class="required"' : '') . ' for="'.$name.'">'.$element->getLabel().'</label>
        			</dt>
        			<dd id="'.$name.'-element">
						<input type="hidden" value="'.$element->getValue().'" id="'.$name.'" name="'.$name.'" />
						<div style="display: inline-block; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 5px;">
							<span id="spanButtonPlaceholder"></span>
						</div>
						<div id="divFileProgressContainer"></div>
						<div id="thumbnails">';
		if ($element->getValue() > 0){
			$img = new Default_Model_Image();
			$thumb = $img->getHtmlThumbnail($element->getValue(), 100, 100, true);
			if ($thumb != ''){
				$image .= $thumb;
			}
		}
		$image .=' </div>
					</dd>';

        switch ($placement) {
            case self::PREPEND:
                return $image . $separator . $content;
            case self::APPEND:
            default:
                return $content . $separator . $image;
        }
    }
}
