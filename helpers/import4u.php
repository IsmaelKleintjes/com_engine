<?php

jimport('phpexcel.library.PHPExcel');


class Import4U
{
    private $objPHPExcel;
    private $log;

    public function __construct()
    {
        $this->objPHPExcel = $this->getFile();
        $this->log = array(
            'danger' => array(),
            'success' => array()
        );
    }

    public function getSheet($name)
    {
        $this->objPHPExcel->setActiveSheetIndexbyName($name);
        $data = $this->objPHPExcel->getActiveSheet()->toArray(null, true, false, false);

        return $this->generateData($data);
    }

    public function log($type, $sheet, $row, $sentence)
    {
        $this->log[$type][$sheet][$row] = $sentence;
    }

    public function getValues($objects, $using, $check)
    {
        return array_filter($objects, function($object) use ($using, $check) { return $object[$check] == $using; });
    }

    public function getLogHTML($data)
    {
        foreach($this->log['success'] as $key => $lines){
            $originalCount = count($data[$key]);

            for($i = 3; $i <= $originalCount + 2; $i++){
                if(!array_key_exists($i, $lines) && !array_key_exists($i, $this->log['warning'][$key])){
                    $this->log('danger', $key, $i, 'Onbekende data aangetroffen.');
                }
            }
        }

        $html = '';
        $html .= '<div class="alert alert-success span6">';
            foreach($this->log['success'] as $key => $lines){
                $html .= '<b>' . $key . '</b>:<br />';
                foreach($lines as $key2 => $line){
                    $html .= '<b>Regel ' . $key2 . ': </b>' . $line . '<br />';
                }
            }
        $html .= '</div>';
        $html .= '<div class="alert alert-error span6">';
            foreach($this->log['danger'] as $key => $lines){
                $html .= '<b>' . $key . '</b>:<br />';
                foreach($lines as $key2 => $line){
                    $html .= '<b>Regel ' . $key2 . ': </b>' . $line . '<br />';
                }
            }
        $html .= '</div>';

        return $html;
    }

    private function generateData($data)
    {
        $keys = $data[0];

        unset($data[0]);

        $lastKey = count($keys) - 1;

        foreach($keys as $key => $name){
            if(!strlen(trim($name))){
                $lastKey = $key;
                break;
            }
        }

        $newValues = array();

        foreach($data as $key => $object){
            $newValue = array();

            foreach($object as $objectKey => $child){
                $newValue[$keys[$objectKey]] = $child;
            }

            $newValues[] = $newValue;
        }

        return $newValues;
    }

    private function getFile()
    {
        $files = Input4U::getFiles('jform');
        $file = $files['file'];

        $path = 'files/import/';

        $filename = 'import_' . time() . '.xlsx';

        if(!JFile::upload($file['tmp_name'], JPATH_ROOT . '/' . $path . $filename)){
            return false;
        }

        $file = JPATH_ROOT . '/files/import/' . $filename;

        $objectReader = PHPExcel_IOFactory::createReader('Excel2007');

        $importModel = JModelLegacy::getInstance("Import", "EngineModel");
        $importModel->parentSave(array('id' => 0, 'filename' => $filename));

        JFactory::getSession()->set('importexcel.id', $importModel->getState('import.id'));

        return $objectReader->load($file);
    }
}