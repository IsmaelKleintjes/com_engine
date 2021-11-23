<?php defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldFeature extends JFormFieldList
{
    public $type = 'Feature';

    protected function getOptions()
    {
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("id, title, value");
        $query->from($db->quoteName("#__eng_feature"));
        $query->where($db->quoteName("cat_id")." = ". (int) $this->element['cat_id']);
        $query->where($db->quoteName("published")." = 1");
        $query->order($db->quoteName("title"));

        $db->setQuery($query);

        $items = $db->loadObjectList();

        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($this->element['getValue']) {
                    $options[] = JHtml::_('select.option', $item->value, $item->title);
                } else {
                    $options[] = JHtml::_('select.option', $item->id, $item->title);
                }
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}