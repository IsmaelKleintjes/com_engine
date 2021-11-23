<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$array =
    array(
        'items' => array(
            array(
                'label' => "Algemeen",
                'layout' => "general",
                'open' => true
            ),
            array(
                'label' => "Style",
                'layout' => "style",
                'open' => false
            ),
            array(
                'label' => "Template",
                'layout' => "template",
                'open' => false
            ),
        )
    );
echo $this->detail->layout($array);
