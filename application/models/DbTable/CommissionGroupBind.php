<?php
class Default_Model_DbTable_CommissionGroupBind extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'commission_group_bind';
    protected $_primary = array('commission_group', 'image_id');
}
