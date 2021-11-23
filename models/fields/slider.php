<?php defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

class JFormFieldSlider extends JFormFieldList
{

    protected $type = 'Slider';

    protected function getOptions()
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__engine_slider'));

        $db->setQuery((string) $query);
        $messages = $db->loadObjectList();
        $options  = array();

        if ($messages)
        {
            foreach ($messages as $message)
            {
                $options[] = JHtml::_('select.option', $message->id, $message->title);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}