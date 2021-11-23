<?php defined('_JEXEC') or die;

class language4UHelper
{
    protected static $component = 'engine';

    public function get($item, $column, $table, $ifEmptyGetDefault=true)
    {
        $active = self::getCurrent();

        if($active->lang_id == 1 || $item->id==0) // Default language
        {
            return $item->{$column};
        }
        else
        {
            $langItem = self::getItem(array(
                'object_id' => $item->id,
                'language_id' => $active->lang_id,
                'column' => $column,
                'table' => $table
            ));

            if(!empty($langItem->text) || $ifEmptyGetDefault==false) {
                return $langItem->text;
            } else {
                return $item->{$column};
            }
        }
    }

    public function getByLanguage($item, $column, $table, $langId)
    {
        if($langId == 1 || $item->id==0) // Default language
        {
            return $item->{$column};
        }
        else
        {
            $langItem = self::getItem(array(
                'object_id' => $item->id,
                'language_id' => $langId,
                'column' => $column,
                'table' => $table
            ));

            if(!empty($langItem->text)) {
                return $langItem->text;
            } else {
                return $item->{$column};
            }
        }
    }

    public function getByObjectId($objectId, $column, $table, $ifEmptyGetDefault=false)
    {
        $active = self::getCurrent();

        $item = self::getItemByObjectId(array(
            'object_id' => $objectId,
            'table' => $table
        ));

        if($active->lang_id == 1 || $objectId==0) // Default language
        {
            return $item->{$column};
        }
        else
        {

            $langItem = self::getItem(array(
                'object_id' => $item->id,
                'language_id' => $active->lang_id,
                'column' => $column,
                'table' => $table
            ));

            if(!empty($langItem->text) || $ifEmptyGetDefault==false) {
                return $langItem->text;
            } else {
                return $item->{$column};
            }
        }
    }

    public function getIdByLabel($label, $column, $table)
    {
        $active = self::getCurrent();
        $label = strtolower($label);
        $aLabel = explode("-",$label);
        $plainLabel = str_replace('-',' ',$label);

        if($active->lang_id == 1) // Default language
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from('#__'.self::$component.'_' . $table);
            $query->where("LOWER(".$db->qn($column).") = " . $db->q($plainLabel). " OR LOWER(".$db->qn($column).") = " . $db->q($label) );

            $db->setQuery( $query );
            $result = $db->loadResult();
        }
        else
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('object_id');
            $query->from('#__'.self::$component.'_language');
            $query->where("`column` = " . $db->q($column) );
            $query->where("`table` = " . $db->q($table) );
            $query->where("LOWER(`text`) = " . $db->q($plainLabel) . " OR LOWER(`text`) = " . $db->q($label) );

            $db->setQuery( $query );
            $result = $db->loadResult();
        }

        return $result;
    }

    static function getItemByObjectId($attr = array())
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__'.self::$component.'_'.$attr['table'].'');

        if(!empty($attr['object_id']))
        {
            $query->where("id = '".$db->escape((int)$attr['object_id'])."'");
        }

        $db->setQuery($query);
        return $db->loadObject();
    }

    static function getItem($attr = array())
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('l.*');
        $query->from('#__'.self::$component.'_language AS l');

        if(!empty($attr['object_id']))
        {
            $query->where("l.object_id = '".$db->escape((int)$attr['object_id'])."'");
        }

        if(!empty($attr['language_id']))
        {
            $query->where("l.language_id = '".$db->escape((int)$attr['language_id'])."'");
        }

        if(!empty($attr['column']))
        {
            $query->where("l.column = '".$db->escape($attr['column'])."'");
        }

        if(!empty($attr['table']))
        {
            $query->where("l.table = '".$db->escape($attr['table'])."'");
        }

        $db->setQuery($query);
        return $db->loadObject();
    }

    static function getItems($attr = array())
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('l.*');
        $query->from('#__'.self::$component.'_language AS l');

        if(!empty($attr['object_id']))
        {
            $query->where("`l.object_id` = '".$db->escape((int)$attr['object_id'])."'");
        }

        if(!empty($attr['language_id']))
        {
            $query->where("`l.language_id` = '".$db->escape((int)$attr['language_id'])."'");
        }

        if(!empty($attr['column']))
        {
            $query->where("`l.column` = '".$db->escape($attr['column'])."'");
        }

        if(!empty($attr['table']))
        {
            $query->where("`l.table` = '".$db->escape($attr['table'])."'");
        }

        $db->setQuery($query);
        return $db->loadObjectList();
    }

    static function getLanguages()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__languages');
        $query->where("published = 1");
        $query->order('ordering ASC');
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    static function getLanguage($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__languages');
        $query->where("lang_id = $id");
        $db->setQuery($query);
        return $db->loadObject();
    }

    static function getDefault()
    {
        $languages = self::getLanguages();
        $languageParams = JComponentHelper::getParams('com_languages');
        $keynumber = 0;

        foreach($languages as $i=>$language)
        {
            if($languageParams->get('site')==$language->lang_code)
            {
                $keynumber = $i;
            }
        }

        return $languages[$keynumber];
    }

    static function checkLanguageTag($languageTag)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__languages');
        $query->where("lang_code = '".$db->getEscaped($languageTag)."'");
        $db->setQuery($query);
        $item = $db->loadObject();
        if($item->lang_id>0)
        {
            return true;
        }else{
            return false;
        }
    }

    static function getCurrent()
    {
        $lang =& JFactory::getLanguage();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('*');
        $query->from('#__languages');
        $query->where("lang_code = '" . $lang->getTag() . "'");

        $db->setQuery($query);

        return $db->loadObject();
    }

    public function getImage($language)
    {
        return JHtml::_('image', 'mod_languages/' . $language->image . '.gif', $language->title_native, array('title' => $language->title_native), true);
    }

    public function getInput($table, $column, $languageId, $objectId=0, $type='text', $key=false, $class=false, $counter = array(), $options = array(), $placeholder = '')
    {
        $value = '';
        if($objectId>0)
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('l.*');
            $query->from('#__'.self::$component.'_language AS l');
            $query->where("l.table = '".$db->escape($table)."'");
            $query->where("l.column = '".$db->escape($column)."'");
            $query->where("l.object_id = '".$db->escape((int)$objectId)."'");
            $query->where("l.language_id = '".$db->escape((int)$languageId)."'");
            $db->setQuery($query);

            $item = $db->loadObject();
            $value = $item->text;
        }

        $html = '';

        switch($type)
        {
            case 'editor':
                $editor =& JFactory::getEditor();
                $params = array( 'smilies'=> '0' ,
                    'style'  => '1' ,
                    'layer'  => '0' ,
                    'table'  => '0' ,
                    'clear_entities'=>'0'
                );
                $html .= $editor->display('lang['.$languageId.']['.$column.']', $value, '200', '400', '20', '20', false, NULL, NULL, NULL, $params);
                break;
            case 'calendar':
                $script = "<script type='text/javascript'> jQuery.noConflict();
                            jQuery(document).ready(function(){
                                jQuery('#".$languageId.$column."').datepicker({ format: 'dd-mm-yyyy', weekStart: 1 });
                                jQuery('#".$languageId.$column."').click(function(e){ return false; });
                            });
                            </script> ";
                $field = '<input type="text" value="'.$value.'" name="lang['.$languageId.']['.$column.']" id="'.$languageId.$column.'" style="display: block !important;" class="inputbox">';
                $html .= $script . $field;
                break;
            case 'textarea':
                $html .= '<textarea name="lang['.$languageId.']['.$column.']" cols="70" rows="20" class="input-xxlarge '.$class.'">' . $value . '</textarea>';
                break;
            case 'sql':
            case 'select':
                $html .= '<select name="lang[' . $languageId . '][' . $column . ']" class="' . $class . '">';
                foreach($options as $option){
                    $selected = ($option->value == $value ? 'selected="selected"' : '');
                    $html .= '<option value="' . $option->value . '" ' . $selected . '>' . $option->text . '</option>';
                }
                $html .= '</select>';
                break;
            default:
                if(is_numeric($key)){
                    $html .= '<input type="'.$type.'" value="'.$value.'" id="" name="lang['.$languageId.'][' . $key . ']['.$column.']" class="'.$class.'" placeholder="' . $placeholder . '"/>';
                } else {
                    $html .= '<input type="'.$type.'" value="'.$value.'" id="lang_' . $languageId . '_' . $column . '" name="lang['.$languageId.']['.$column.']" class="'.$class.'" placeholder="' . $placeholder . '"/>';
                }
                break;
        }

        if($counter['counter'] == 1){
            $html .= '<div id="counter_' . $languageId . '_' . $column . '"></div>';
            $html .= "<script>
            var $ = jQuery.noConflict();

            $('#lang_" . $languageId . "_" . $column . "').simplyCountable({
                maxCount: " . $counter['max_count'] . ",
                strictMax: true,
                countDirection: 'down',
                counter: '#counter_" . $languageId . "_" . $column . "'
            });
            </script>";
        }

        return $html;
    }

    public function saveMultiple($objectId, $table, $column, $key)
    {
        $languages = JRequest::getVar('lang');
        $installedLanguages = self::getLanguages();

        self::deleteMultiple('attribute_value', 'value', 'object_id', $objectId);

        foreach($installedLanguages as $installedLanguage){
            if($languages[$installedLanguage->lang_id][$key]){
                $a = new stdClass();
                $a->object_id = $objectId;
                $a->language_id = $installedLanguage->lang_id;
                $a->text = $languages[$installedLanguage->lang_id][$key][$column];
                $a->column = $column;
                $a->table = $table;
                $a->id = 0;

                JFactory::getDBO()->insertObject("#__".self::$component."_language", $a);
            }
        }
    }

    public static function save($objectId, $table, $aliasColumns = false)
    {
        $db = JFactory::getDbo();
        $languageVars = JRequest::getVar('lang');

        if(count($languageVars)) {
            foreach($languageVars as $languageId=>$fields)
            {
                $columns = implode("', '", array_keys($fields));

                self::delete($objectId, $table, $columns, $languageId);

                $aliasFound = false;

                foreach($fields as $column=>$text)
                {
                    $object = new stdClass();
                    $object->object_id = $objectId;
                    $object->language_id = $languageId;
                    $object->table = $table;
                    $object->column = $column;

                    if($column == 'alias' && $aliasColumns !== false && !empty($text)){
                        $aliasFound = true;

                        $object->text = JFilterOutput::stringUrlSafe($text);
                    } elseif($column == 'alias' && $aliasColumns !== false) {
                        continue;
                    } else {
                        $object->text = $text;
                    }

                    $db->insertObject('#__'.self::$component.'_language', $object);

                }

                if($aliasColumns !== false && $aliasFound === false){
                    $object = new stdClass();
                    $object->object_id = $objectId;
                    $object->language_id = $languageId;
                    $object->table = $table;
                    $object->column = 'alias';

                    $text = '';

                    foreach($aliasColumns as $key => $aliasColumn){
                        if($key > 0){
                            $text .= '-';
                        }

                        $text .= $fields[$aliasColumn];
                    }

                    $object->text = JFilterOutput::stringUrlSafe($text);

                    $db->insertObject('#__'.self::$component.'_language', $object);
                }
            }
        }
    }

    public function delete($objectId, $table, $columns, $languageId)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__'.self::$component.'_language');
        $query->where("`language_id` = '".$db->escape($languageId)."'");
        $query->where("`table` = '".$db->escape($table)."'");
        $query->where("`object_id` = '".$db->escape((int)$objectId)."'");
        $query->where("`column` IN ('".$columns."')");
        $db->setQuery($query);
        $db->query();
    }

    public function deleteMultiple($table, $column, $where, $objectId)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->delete("#__".self::$component."_language");
        $query->where("`table` = '$table'");
        $query->where("`column` = '$column'");
        $query->where("object_id IN (SELECT id FROM #__".self::$component."_$table WHERE $where = '$objectId')");

        $db->setQuery($query);
        $db->execute();
    }

    public function addTranslatedValues($item, $columns, $table)
    {
        $db = JFactory::getDbo();
        $active = self::getCurrent();
        $query = $db->getQuery(true);
        $query->select('l.*');
        $query->from('#__'.self::$component.'_language AS l');
        $query->where("l.column IN('".implode("','", $columns)."')");
        $query->where('l.object_id = '.(int)$item->id);
        $query->where('l.language_id = '.(int)$active->lang_id);
        $query->where("l.table = '".$db->escape($table)."'");
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if(count($rows)>0){
            foreach($rows as $row){
                if($row->text!=''){
                    $item->{$row->column} = $row->text;
                }
            }
        }

        return $item;
    }

    public static function generateTable($items, $id, $ordering = true, $arrayTitle, $columnTitle, $tableTitle, $disableLanguage = false, $lang = true )
    {
        $languages = self::getLanguages();

        $html = '';

        $html .= '<link rel="stylesheet" href="' . Juri::root() . 'administrator/components/com_engine/assets/css/jquery-ui.sortable.min.css" type="text/css" />';

        $html .= '<div class="clearfix">';
        $html .= '<h4 class="pull-left">'.JText::_('COM_ENGINE_LANGUAGEHELPER_OPTIONS').'</h4>';
        if ($disableLanguage) {
            $html .= '<button type="button" id="btn-language-' . $columnTitle . '" class="btn-link btn-small pull-right">' . ($lang ? JText::_('COM_ENGINE_LANGUAGEHELPER_DISABLE') : JText::_('COM_ENGINE_LANGUAGEHELPER_ENABLE')) . '</button>';
        }
        $html .= '</div>';

        $html .= '<table class="table table-striped" id="' . $id . '">';
        $html .= '<tbody>';
        foreach ($items as $key => $item) {
            $html .= '<tr>';
            if ($ordering) {
                $html .= '<td><i class="icon-menu" style="cursor: move;"></i></td>';
            }
            foreach ($languages as $key2 => $language) {
                $html .= '<td>';
                $html .= '<div class="input-prepend" style="' . (!$lang && $key2 > 0 ? 'display:none' : 'display:block') . '">';
                $html .= '<span class="add-on ' . ($key2 == 0 ? 'language-addon-' . $columnTitle : '') . ' style="' .  (!$lang && $key2 > 0 ?  'display:none' : 'display:block') . '">' . self::getImage($language) . '</span>';
                if ($key2 == 0) {
                    $html .= '<input type="text" name="' . $arrayTitle . '[' . $key . '][' . $columnTitle . ']" id="' . $arrayTitle . '_' . $key . '_' . $columnTitle . '" class="inputbox validate" value="' . $item->{$columnTitle} . '"/>';
                } else {
                    $html .= self::getInput($tableTitle, $columnTitle, $language->lang_id, $item->id, 'text', $key, 'language-input-' . $columnTitle);
                }
                $html .= '</div>';
                $html .= '</td>';
            }

            $html .= '<td>';
            $html .= '<button type="button" class="btn btn-small btn-danger btn-' . $columnTitle . '-remove"><span class="icon-trash"></span></button>';
            $html .= '<input type="hidden" name="' . $arrayTitle . '[' . $key . '][id]" id="values_' . $key . '_id" value="' . $item->id . '"/>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        $html .= '<button type="button" class="btn btn-small btn-success btn-' . $columnTitle . '-add">'.JText::_('COM_ENGINE_LANGUAGEHELPER_NEW_VALUE').'</button>';

        $html .= '<input type="hidden" name="' . $arrayTitle . '_language" value="' . ($lang ? 1 : 0) . '"/>';

        $html .= '<script src="' . Juri::root() . 'administrator/components/com_engine/assets/js/jquery-ui.sortable.min.js" type="text/javascript"></script>';

        $html .= "
<script type=\"text/javascript\">
    var i_$columnTitle = " . count($items) . ";

    jQuery(document).ready(function(){
        jQuery('table#$id > tbody').sortable({ cursor: 'move' });
    });

    jQuery(document).on('click', '.btn-$columnTitle-remove', function(){
        if(!confirm('Weet u het zeker?')){
            return;
        }

        $(this).closest('tr').remove();
    });

    jQuery(document).on('click', '.btn-$columnTitle-add', function(){
        add" . ucfirst($columnTitle) . "Row();
    });

    jQuery(document).on('click', '#btn-language-$columnTitle', function(){
        var texts = [
            '".JText::_('COM_ENGINE_LANGUAGEHELPER_DISABLE')."',
            '".JText::_('COM_ENGINE_LANGUAGEHELPER_ENABLE')."'
        ];

        var hiddenInput = jQuery('input[name=\"{$arrayTitle}_language\"]');
        var val = hiddenInput.val();

        jQuery(this).text(texts[val]);

        var newVal = (val == 1 ? 0 : 1);

        hiddenInput.val(newVal);

        jQuery('.language-input-$columnTitle').closest('.input-prepend').toggle();
        jQuery('.language-addon-$columnTitle').toggle();
    });

    function add" . ucfirst($columnTitle) . "Row()
    {
        var tableBody = jQuery('table#$id > tbody');

        var html = '';

        html += '<tr>';
            html += '<td><i class=\"icon-menu\" style=\"cursor: move;\"></i></td>';";

        foreach ($languages as $key => $language):
            $html .= "html += '<td>';
                html += '<div class=\"input-prepend\">';
                    html += '<span class=\"add-on " . ($key == 0 ? 'language-addon-value' : '') . "\">" . self::getImage($language) . "</span>';";
            if ($key == 0):
                $html .= "html += '<input type=\"text\" name=\"" . $arrayTitle . "[' + i_$columnTitle +  '][$columnTitle]\" id=\"" . $arrayTitle . "' + i_$columnTitle + '_$columnTitle\" class=\"inputbox validate\"/>';";
            else:
                $html .= "html += '<input type=\"text\" name=\"lang[{$language->lang_id}][' + i_$columnTitle + '][$columnTitle]\" id=\"" . $arrayTitle . "_{$language->lang_id}_' + i_$columnTitle + '_$columnTitle\" class=\"inputbox validate language-input-$columnTitle\" />';";
            endif;
            $html .= "html += '</div>';
            html += '</td>';";
        endforeach;
        $html .= "html += '<td>';
                html += '<button type=\"button\" class=\"btn btn-small btn-danger btn-$columnTitle-remove\"><span class=\"icon-trash\"></span></button>';
                html += '<input type=\"hidden\" name=\"" . $arrayTitle . "[' + i_$columnTitle + '][id]\" id=\"" . $arrayTitle . "_' + i_$columnTitle + '_id\"/>';
            html += '</td>';
        html += '</tr>';

        tableBody.append(html);

        i_$columnTitle++;
    }
</script>";

        return $html;
    }
}