<?php defined('_JEXEC') or die('Restricted access');

class EngineTableNewsletter extends JTable
{
    function __construct(&$db)
    {
        parent::__construct('#__eng_newsletter', 'id', $db);
    }
}