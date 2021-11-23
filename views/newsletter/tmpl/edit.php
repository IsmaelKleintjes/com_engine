<?php defined('_JEXEC') or die('Restricted access');

echo $this->detail->layout(array(
    'items' => array(
        array(
            'label' => "Algemeen",
            'layout' => "general",
            'open' => true
        )
    )
));

