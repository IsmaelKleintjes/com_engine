<?php defined('_JEXEC') or die('Restricted access');

$array =
    array(
        'items' => array(
            array(
                'label' => "Algemeen",
                'layout' => "general",
                'open' => true
            ),
        ),
        'languages' => false
    );

echo $this->detail->layout($array);

