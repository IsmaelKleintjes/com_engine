<?php defined('JPATH_PLATFORM') or die;

class JFormFieldWysihtml5 extends JFormField
{
	protected $type = 'wysihtml5';
	protected static $initiated;
	 
	public function __construct()
	{
		if(!self::$initiated)
		{
			//$doc = JFactory::getDocument();
			
			//$doc->addScript(JURI::root() . 'media/system/js/bootstrap-wysihtml5.js');
			//$doc->addScript(JURI::root() . 'media/system/js/bootstrap-wysiwyg.js');
			//$doc->addStylesheet(JURI::root() . 'media/system/css/bootstrap-wysiwyg.css');
			
			$script = '
				<script>
					$ = jQuery.noConflict();
					$(document).ready(function(){
						$(".wysihtml5").wysihtml5({
							"font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
							"emphasis": true, //Italics, bold, etc. Default true
							"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
							"html": true, //Button which allows you to edit the generated HTML. Default false
							"link": true, //Button to insert a link. Default true
							"image": true, //Button to insert an image. Default true,
							"color": true //Button to change color of font
						});
					});
				</script>
			';
			
			print($script);
			self::$initiated = true;
		}
	}
	 
	protected function getInput()
	{
		$class = $this->element['class'] ? ' class="wysihtml5 ' . (string) $this->element['class'] . '"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$columns = $this->element['cols'] ? ' cols="' . (int) $this->element['cols'] . '"' : '';
		$rows = $this->element['rows'] ? ' rows="' . (int) $this->element['rows'] . '"' : '';
		$placeholder = $this->element['placeholder'];
		
		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return '<textarea '.$class.' placeholder="'. $placeholder .'" name="' . $this->name . '" id="' . $this->id . '"' . $columns . $rows . $class . $disabled . $onchange . '>'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea>';
	}
}
