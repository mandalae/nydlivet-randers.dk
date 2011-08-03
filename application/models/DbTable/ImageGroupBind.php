<?php
class Default_Model_DbTable_ImageGroupBind extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'image_group_bind';
    protected $_primary = array('image_group', 'image_id');
}
