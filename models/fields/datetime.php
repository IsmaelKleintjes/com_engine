<?php defined('JPATH_PLATFORM') or die;

class JFormFieldDateTime extends JFormField
{
	protected $type = 'datetime';

	protected function getInput()
	{
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::base() . 'components/com_engine/assets/js/bootstrap-datetimepicker.min.js');

        if($this->value == 0){
            $this->value = "";
        }

        $html = "<script>
        $('.date-picker').datetimepicker();
        </script>";
        $html .= "<div class='input-group date'>";
        $html .= "<span class='input-group-addon'><i class='icon ion-calendar'></i></span>";
        $html .= "<input class='form-control date-picker' type='text' value='".App4U::showDate($this->value)."' name='".$this->name."' id='".$this->id."' data-date-format='dd-mm-yyyy' />";
        $html .= "</div>";

        return $html;
	}
}
