<?php defined('_JEXEC') or die('Restricted access');


$array =
    array(
        'items' => array(
            array(
                'label' => "Algemeen",
                'layout' => "general",
                'open' => true
            ),
            array(
                'label' => "Teksten",
                'layout' => "texts"
            )
        )
    );
echo $this->detail->layout($array);

