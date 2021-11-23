<?php
class Array4U
{
    public static function get($filePath, $tableName)
    {
        require_once JPATH_SITE . '/' . $filePath . '.php';

        return $$tableName;
    }
}