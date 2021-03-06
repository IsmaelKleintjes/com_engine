<?php defined('JPATH_PLATFORM') or die;

class JFormFieldCurrency extends JFormField
{
	protected $type = 'Currency';

	protected function getInput()
	{
		// Initialize some field attributes.
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$placeholder = $this->element['placeholder'] ? ' placeholder="' . (string) $this->element['placeholder'] . '"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		$html = '<div class="input-prepend span12">';
		$html .= '<div class="row-fluid">';
		$html .= '<span class="add-on span2">€</span>';
		$html .= '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $placeholder .  '/>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
    
}
