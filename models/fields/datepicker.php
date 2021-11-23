<?php defined('JPATH_PLATFORM') or die;

class JFormFieldDatetimepicker extends JFormField
{
	public $type = 'datepicker';
	
	function getInput()
	{
        $doc = JFactory::getDocument();
        $doc->addScript(JPATH_COMPONENT . '/assets/js/bootstrap-datetimepicker.min.js');

		if($this->value == 0){
			$this->value = "";
		}

		$html = "<div class='input-group date'>";
			$html .= "<span class='input-group-addon'><i class='icon ion-calendar'></i></span>";
			$html .= "<input class='form-control date-picker' type='text' value='".App4U::showDate($this->value)."' name='".(string)$this->name."' id='".(int)$this->id."' data-date-format='dd-mm-yyyy' />";
		$html .= "</div>";

		return $html;
	}
}