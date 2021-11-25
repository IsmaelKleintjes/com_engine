<?php defined('_JEXEC') or die('Restricted access');

$array =
    array(
        'items' => array(
            array(
                'label' => "Details",
                'layout' => "general",
                'open' => true
            ),
        )
    );
echo $this->detail->layout($array);

