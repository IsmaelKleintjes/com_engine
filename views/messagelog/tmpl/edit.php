<?php defined('_JEXEC') or die('Restricted access');

echo $this->detail->layout(array(
    'items' => array(
        array(
            'label' => JText::_('Algemeen'),
            'layout' => "general",
            'open' => true
        ),
    )
));
