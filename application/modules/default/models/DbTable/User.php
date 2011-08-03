<?php

class Default_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

	/*protected $_referenceMap    = array(
        'Mail' => array(
            'columns'           => array('mail_id'),
            'refTableClass'     => 'Application_Model_DbTable_Mails',
            'refColumns'        => array('mail_id')
        )
    );*/

}