<?php defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldCrudcat extends JFormFieldList
{
    public $type = 'Crudcat';

    protected function getOptions()
    {
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("id, name");
        $query->from($db->quoteName("#__engine_category"));
        $query->where($db->quoteName("crud")." = " . $db->quote( $this->element['crud'] ));
        $query->where($db->quoteName("published")." = 1");
        $query->order($db->quoteName("name"));

        $db->setQuery($query);

        $items = $db->loadObjectList();

        if (!empty($items))
        {
            foreach ($items as $item)
            {
                $options[] = JHtml::_('select.option', $item->id, $item->name);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}