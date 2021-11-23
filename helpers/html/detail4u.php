<?php defined('_JEXEC') or die;

class JHtmlDetail extends JViewLegacy
{
	
	public function __construct( $view, $attr=array() )
	{
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidator');
		JHtml::_('formbehavior.chosen', 'select');

		$this->view			= $view;
		$this->form			= $view->get("Form");
		$this->item			= $view->get("Item");
		$this->fieldsets	= $this->form->getFieldsets();

		$hiddenFound = false;


		if(count($this->fieldsets)) foreach($this->fieldsets as $fieldSetName => $fieldset) {

			$fields  = $this->form->getFieldset( $fieldSetName );
			if(count($fields)) {
			    foreach($fields as $field) {
                    if(strtolower($field->type)=='editor') {
                        $editorNames[] = $field->fieldname;
                    }
                }
            }

            if($hiddenFound){
			    $this->customFieldsets[] = $fieldset;
            }

            if($fieldset->name == 'hidden'){
			    $hiddenFound = true;
            }
		}

        JModelLegacy::addIncludePath("components/com_fields/models/");
        $groupModel = JModelLegacy::getInstance('Group', 'FieldsModel');

        if(count($this->customFieldsets)){
            foreach($this->customFieldsets as $key => $customFieldset){
                $splittedName = explode('-', $customFieldset->name);

                $this->customFieldsetItems[$key] = $groupModel->getItem($splittedName[1]);
            }
        }

        $this->authorizedViewLevels = array();

        if(Input4U::get('view', 'REQUEST') == 'user'){

            $this->authorizedViewLevels = JAccess::getAuthorisedViewLevels($this->item->id);
        }
	}

	public function fieldset( $title, $hidden=false, $language=false, $table=false )
	{
		$html = "";
		$fields = $this->form->getFieldset( $title );

		if(count($fields))
		{

            foreach($fields as $field)
            {
                if(!$hidden)
                {
                    $required = $field->getAttribute('required');
                    $class = $field->getAttribute('class');
                    $fieldName = $field->getAttribute('name');

                    if($required === 'true'){
                        $class .= ' required';
                    }

                    $html .= "<div class='control-group'>";
                    $html .= "<div class='control-label'>";
                    if($language) {
                        $html .= str_replace('jform_' . $fieldName, 'lang_' . $language->lang_id . '_' . $fieldName, $field->label);
                    } else {
                        $html .= $field->label;
                    }
                    $html .= "</div>";
                    $html .= "<div class='controls'>";
                    if($language)
                    {
                        $html .= language4UHelper::getInput($table, $fieldName, $language->lang_id, $this->item->id, $field->getAttribute('type'), false, $class, array('counter' => $field->getAttribute('counter'), 'max_count' => $field->getAttribute('max_count')), $field->__get('options'), $field->getAttribute('hint'));
                    }
                    else
                    {
                        $html .= $field->input;
                        if($field->getAttribute('counter') == 1){
                            $html .= '<div id="counter_' . $field->id . '"></div>';
                            $html .= "<script>
                            var $ = jQuery.noConflict();

                            $('#" .  $field->id . "').simplyCountable({
                                maxCount: " . $field->getAttribute('max_count') . ",
                                strictMax: true,
                                countDirection: 'down',
                                counter: '#counter_" . $field->id . "'
                            });
                            </script>";
                        }
                    }
                    $html .= "</div>";
                    $html .= "</div>";
                }
                else
                {
                    $html .= $field->input;
                }
            }
		}
		
		return $html;
	}
	
	public function title( $newLabel, $editLabel=false )
	{
		$html = '<h3 class="pull-left">';
		
		if($editLabel===false || $this->item->id==0)
		{
			$html .= JText::_( $newLabel );
		}
		else
		{
			$html .= JText::_( $editLabel );
		}
		
		$html .= '</h3>';
		
		return $html;
	}
	
	public function buttons()
	{
		$html = '<div class="pull-right field-box actions">';
			$html .= '<button type="submit" class="btn-flat save-button validate">' . JText::_('JSAVE') . '</button>';
			
			if(Input::get("option") == "com_dossier")
			{
				$html .= '<span>'.JText::_("SETTINGS_OR").'</span> <a href="' . JRoute::_("index.php?option=".Input::get("option")."&view=dossiers&Itemid=117") . '" class="cancel">' . JText::_('JCANCEL') .'</a>';
			} else {
				if(substr(Input::get("view"), -1, 1) == "y"){
					$html .= '<span>'.JText::_("SETTINGS_OR").'</span> <a href="' . JRoute::_("index.php?option=".Input::get("option")."&view=" . substr(Input::get("view"), 0, -1) . "ies") . '" class="cancel">' . JText::_('JCANCEL') .'</a>';
				} else {
					$html .= '<span>'.JText::_("SETTINGS_OR").'</span> <a href="' . JRoute::_("index.php?option=".Input::get("option")."&view=" . Input::get("view") . "s") . '" class="cancel">' . JText::_('JCANCEL') .'</a>';
				}
			}
		$html .= '</div>';
		
		return $html;
	}
	
	public function header( $newLabel, $editLabel=false, $buttons=true )
	{
		$html = '<div class="row-fluid header">';
			$html .= self::title( $newLabel, $editLabel );
			$html .= '<input type="hidden" id="redirect_field" name="jform[redirect]">';
			if($buttons) {
				$html .= self::buttons();
			}
		$html .= '</div>';
		
		return $html;
	}


	public function layout($attr)
	{
        JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == '" . $this->view->get('name') . ".cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
");

		$html = "<form action='". JRoute::_("index.php?option=com_" . COMPONENT . "&id=" . $this->item->id) . "' method='post' enctype='multipart/form-data' name='adminForm' id='adminForm' class='adminForm form-validate'>";
			$html .= "<div class='row-fluid'>"; 
				$html .= "<div class='span12 form-horizontal'>";
				
				switch($attr['type'])
				{
					case "accordion":
						$html .= self::accordion($attr);
					break;	
					default:
					case "tabs":
						$html .= self::tabs($attr);
					break;	
				}
				
				$html .= "</div>";
			$html .= "</div>";
			$html .= "<div>";
				$html .= $this->fieldset("hidden", true);
				$html .= "<input type='hidden' name='task' id='task' value='" . $this->view->get('name') . ".edit' />";
				$html .= JHtml::_('form.token');
			$html .= "</div>";
		$html .= "</form>";
		
		return $html;
	}
	
	public function tabs($attr)
	{
		$html = '<ul class="nav nav-tabs" id="tabs">';

        if($attr['languages'])
        {
            foreach(language4UHelper::getLanguages() as  $i => $language)
            {
                $html .= '	<li class="'.($i==0?"active":"").'"><a href="#language-'.$language->lang_code.'"  data-toggle="tab">'.language4UHelper::getImage($language).'</a></li>';
            }
        }
		
		foreach($attr['items'] as $item)
		{
            if($item['id'])
            {
                $html .= '	<li class="'.($item['open']?"active":"").'"><a href="#'.$item['layout'].'" id="' . $item['id']  .'" data-toggle="tab">'.JText::_($item['label']).'</a></li>';
            }
            else
            {
                $html .= '	<li class="'.($item['open']?"active":"").'"><a href="#'.$item['layout'].'"  data-toggle="tab">'.JText::_($item['label']).'</a></li>';

            }
		}

		if(!empty($this->customFieldsets)) {
            foreach($this->customFieldsets as $key => $fieldset){
                $item = $this->customFieldsetItems[$key];

                if(!in_array($item->access, $this->authorizedViewLevels) && Input4U::get('view', 'REQUEST') == 'user'){
                    continue;
                }

                $html .= '	<li class=""><a href="#'.$fieldset->name.'"  data-toggle="tab">'.$fieldset->label.'</a></li>';

            }
        }


		$html .= '</ul>';
		$html .= '<div class="tab-content">';
		
		foreach($attr['items'] as $item)
		{
			$html .= '<div class="tab-pane '.($item['open']?"active":"").'" id="'.$item['layout'].'">';
			$html .= 	$this->view->loadTemplate($item['layout']);
			$html .= '</div>';
		}
		if(!empty($this->customFieldsets)) {
            foreach($this->customFieldsets as $key => $fieldset)
            {
                $item = $this->customFieldsetItems[$key];

                if(!in_array($item->access, $this->authorizedViewLevels) && Input4U::get('view', 'REQUEST') == 'user'){
                    continue;
                }

                $html .= '<div class="tab-pane" id="'.$fieldset->name.'">';
                $html .= 	$this->fieldset($fieldset->name);
                $html .= '</div>';
            }
        }

        if($attr['languages'])
        {
            foreach(language4UHelper::getLanguages() as $i => $language)
            {
                $html .= '<div class="tab-pane '.($i==0?"active":"").'" id="language-'.$language->lang_code.'">';

                if($i==0)
                {
                    foreach($this->fieldsets as $fieldset)
                    {
                        if($fieldset->translate)
                        {
                            $html .= $this->fieldset($fieldset->name);
                        }
                    }
                } else{
                    foreach($this->fieldsets as $fieldset)
                    {
                        if($fieldset->translate)
                        {
                            $html .= $this->fieldset($fieldset->name, false, $language, $fieldset->table);
                        }
                    }
                }
                $html .= '</div>';
            }
        }
		
		$html .= '</div>';
		
		return $html;
	}
	
	public function accordion($attr)
	{	
		$html = '';
		
		if($attr['']) {
			$html .= '<div class="accordion" id="accordion">';
		}
		
		foreach($attr['items'] as $item)
		{
			$html .= '<div class="accordion-">';
			$html .= '	<div class="accordion-heading">';
			$html .= '		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#'.$item['layout'].'">';
			$html .= 			JText::_($item['label']);
			$html .= '		</a>';
			$html .= '	</div>';
			$html .= '	<div id="'.$item['layout'].'" class="accordion-body collapse '.($item['open']?"in":"").'">';
			$html .= '		<div class="accordion-inner">';
			$html .= 			$this->view->loadTemplate($item['layout']);
			$html .= '		</div>';
			$html .= '	</div>';
			$html .= '</div>';
		
		}
		
		if($attr['']) {
			$html .= '</div>';
		}
		
		return $html;	
	}
	
	
	
}