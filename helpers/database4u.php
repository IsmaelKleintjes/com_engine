<?php defined('_JEXEC') or die;

class Database4U {

    public function importForm($model)
    {
        $columns = self::getColumns($model);

        if(!self::checkTableExist($model->getName())) {
            self::createTable($model->getName(), $columns);
        } else {

            $tableColumns = self::getTableColumns($model->getName());

            foreach($columns as $column) {
                if(!in_array($column->name, $tableColumns)) {
                    self::createTableColumn($model->getName(), $column);
                }
            }
        }

        return true;
    }

    public function getColumns($model)
    {
        $form = $model->getForm(array(), false);
        $fieldsets = $form->getFieldsets();
        $aColumns = array();

        foreach($fieldsets as $fieldset) {
            if($fieldset->name != 'hidden') {
                foreach($form->getFieldset($fieldset->name) as $field) {
                    $oColumn = new stdClass();
                    $oColumn->type = $field->type;
                    $oColumn->name = $field->fieldname;

                    $aColumns[] = $oColumn;
                }
            }
        }

        return $aColumns;
    }

    public function getTableColumns($tableName)
    {
        $db = JFactory::getDbo();
        $db->setQuery("SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = N'ENGINE_engine_".$tableName."'");
        $result = $db->loadColumn();

        return $result;
    }

    public function createTableColumn($tableName, $column)
    {
        $db = JFactory::getDBO();
        $query = "ALTER TABLE #__engine_".$tableName." ADD ".$column->name." ".self::getColumnType($column->type);
        $db->setQuery($query);
        $db->query();

        return true;
    }

    public function checkTableExist($name)
    {
        $db = JFactory::getDbo();
        $db->setQuery("SHOW TABLES LIKE '%engine_".$name."%'");
        $result = $db->loadResult();

        return $result;
    }

    public function createTable($name, $columns)
    {
        $sQuery = self::buildTable($columns);

        $db = JFactory::getDBO();
        $query = "CREATE TABLE IF NOT EXISTS `#__engine_".$name."` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      ".$sQuery."
                      `published` tinyint(2) NOT NULL DEFAULT '1',
                      `created` datetime NOT NULL,
                      `created_by` int(11) NOT NULL,
                      `modified` datetime NOT NULL,
                      `modified_by` int(11) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
        $db->setQuery($query);
        $db->query();

        return true;
    }

    public function buildTable($columns)
    {
        $query = '';

        foreach($columns as $column) {
            $query .= '`'.$column->name.'` '.self::getColumnType($column->type).','.PHP_EOL;
        }

        return $query;
    }

    public function getColumnType($fieldType)
    {

        switch ($fieldType) {
            case "Text":
                $columnType = "varchar(255) NOT NULL";
                break;
            case "Textarea":
                $columnType = "text NOT NULL";
                break;
            case "List":
                $columnType = "int(11) NOT NULL";
                break;
            default:
                $columnType = "int(11) NOT NULL";
        }

        return $columnType;
    }

}
