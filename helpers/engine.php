<?php defined('_JEXEC') or die;

/**
 * Class EngineHelper
 *
 * @version     1.0
 * @since       21-11-2016
 */
class EngineHelper
{
	# ---------------------------------------------------------------------------
	#	CONTROLLER Functions
	# ---------------------------------------------------------------------------
    /**
     * Description comes later
     *
     * @param $controller
     *
     * @since   Engine 2.0
     */
    public function saveOrderAjax($controller )
	{	
		$app = JFactory::getApplication();
		$pks = $app->input->post->get('cid', array(), 'array');
		$order = $app->input->post->get('order', array(), 'array');

		// Sanitize the input
		Joomla\Utilities\ArrayHelper::toInteger($pks);
        Joomla\Utilities\ArrayHelper::toInteger($order);

		// Get the model
		$model = $controller->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

    /**
     * Description comes later
     *
     * @param $controller
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function controllerFeatured( $controller )
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$ids = $app->input->get('cid', array(), 'array');
		$values = array('featured' => 1, 'unfeatured' => 0);
		$task = $controller->getTask();
		$value =Joomla\Utilities\ArrayHelper::getValue($values, $task, 0, 'int');

		// Access checks.
		foreach ($ids as $i => $id)
		{
			if (!$user->authorise('core.edit.state', 'com_content.article.'.(int) $id)) {
				// Prune items that you can't change.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
		}

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else {
			// Get the model.
			$model = $controller->getModel();

			// Publish the items.
			if (!$model->featured($ids, $value)) {
				JError::raiseWarning(500, $model->getError());
			}
		}

		$controller->setRedirect('index.php?option=com_engine&view='.(string)$controller->view_list);
	}

	# ---------------------------------------------------------------------------
	#	MODEL Functions
	# ---------------------------------------------------------------------------
    /**
     * Adds Created_by and Modified_by data to the database before saving
     *
     * @param $data
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function dataBeforeSave($data )
	{
		$user =& JFactory::getUser();
		
		if ($data['id']>0)
		{
			$data['modified'] = date("Y-m-d H:i:s");
			$data['modified_by'] = $user->id;
		}
		else
		{
			$data['created'] = date("Y-m-d H:i:s");
			$data['created_by'] = $user->id;
			$data['created_by_alias'] = $user->name;
		}
		
		return $data;	
	}

    /**
     * Description comes later
     *
     * @param $pks
     * @param $value
     * @param $table
     *
     * @return bool
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function modelFeatured($pks, $value, $table )
	{
		$pks = (array) $pks;
        Joomla\Utilities\ArrayHelper::toInteger($pks);

		if (empty($pks)) {
			$this->setError(JText::_('COM_CONTENT_NO_ITEM_SELECTED'));
			return false;
		}

		$db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->update($table);
        $query->set("featured = " . (int)$value);
        $query->where($db->quoteName("id")." IN ('" . implode(',', $pks) . "')");

		$db->setQuery($query);
		$db->execute();

		return true;
	}	

    /**
     * Description comes later
     *
     * Will be updated
     *
     * @param $config
     * @param $search
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function searchString($config, $search)
    {
        $searchFields = array();
        foreach($this->config["fields"] as $field)
        {
            if(isset($field["search"]) && $field["search"] == true){
                if(isset($field["customsearch"])){
                    $searchFields[] = $field["customsearch"];
                } else {
                    $searchFields[] = $field["column"];
                }
            }
        }
        $searchString = 'CONCAT(';
        $i = 0;
        foreach($searchFields as $item)
        {
            if(empty($searchFields[$i+1]))
            {
                $searchString .= $item;
            } else {
                $searchString .= $item . ", ";
            }
            $i++;
        }
        $searchString .= ') LIKE "%' . (string)$search . '%"';

        return $searchString;
    }
    
    
    public static $extension = 'com_engine';


    /**
     * Adds submenu
     *
     * @param $vName
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function addSubmenu($vName)
    {
        $mcvGroup = self::getActiveMvcGroup($vName);
        if(is_array($mcvGroup))
        {
            JHtmlSidebar::addEntry( $mcvGroup['defaultTitle'], 'index.php?option='.self::$extension.'&view='.$mcvGroup['defaultController'], $mcvGroup['defaultController']);
            foreach( $mcvGroup['objects'] as $object ) {

                $active = (bool)($object['vName'] == $vName);
                if($active && $vName == 'categories')
                {
                    $crud = JFactory::getApplication()->input->get("crud");
                    if($crud == $object['crud'])
                    {
                        $active = true;
                    }
                    else
                    {
                        $active = false;
                    }

                }
                JHtmlSidebar::addEntry( $object['title'], $object['url'], $active);

            }
        }

        if($vName == 'sqlupdates') {
		    JHtmlSidebar::addEntry( 'Dashboard', 'index.php?option=com_engine&view=dashboard', false);
		    JHtmlSidebar::addEntry( 'SQL updates', 'index.php?option=com_engine&view=sqlupdates', true);
        }
    }

    /**
     * Description comes later
     *
     * @return array
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getMVCgroups()
    {
        return array(
            'clientTitle' => JText::_('ENGINE_CLIENTTITLE'),
            'defaultTitle' => JText::_('ENGINE_CLIENTTITLE'),
            'defaultController' => 'dashboard',
            'componentInfo' => array(
                'Naam' => JText::_('ENGINE_CLIENTNAME'),
                'Versie' => '1.0',
                'Auteur' => 'Edit4U Webservices BV',
                'Website' => '<a href="http://www.edit4u.nl" target="_blank">www.edit4u.nl</a>',
            ),
            array(
                'title' => "Website",
                'objects' => array(
                    array( 'title' => 'Contact aanvragen', 'icon' => 'envelope', 'vName' => 'leads', 'url'=>'index.php?option=com_engine&view=leads' ),
                    array( 'title' => 'Nieuwsbrief inschrijvingen', 'icon' => 'pencil', 'vName' => 'newsletters', 'url'=>'index.php?option=com_engine&view=newsletters' ),
                    array( 'title' => 'Download aanvragen', 'icon' => 'cloud-download', 'vName' => 'downloads', 'url'=>'index.php?option=com_engine&view=downloads' ),
                    #array( 'title' => 'Slider', 'icon' => 'picture','vName' => 'slides', 'url'=>'index.php?option=com_engine&view=slides' ),
                ),
            ),
            array(
                'title' => "Instellingen",
                'objects' => array(
                    array( 'title' => 'Berichten', 'icon' => 'envelope','vName' => 'messages', 'url'=>'index.php?option=com_engine&view=messages' ),
                    array( 'title' => 'Berichten templates', 'icon' => 'envelope','vName' => 'messagetemplates', 'url'=>'index.php?option=com_engine&view=messagetemplates' ),
                    array( 'title' => 'Berichten log', 'icon' => 'search','vName' => 'messagelogs', 'url'=>'index.php?option=com_engine&view=messagelogs' ),
                    array( 'title' => 'Features', 'icon' => 'star','vName' => 'features', 'url'=>'index.php?option=com_engine&view=features' ),
                    array( 'title' => 'Categories', 'icon' => 'list','vName' => 'categories', 'url'=>'index.php?option=com_engine&view=categories&crud=feature', 'crud' => 'feature' ),
                ),
            ),

        );
    }

    /**
     * Description comes later
     *
     * @param $vName
     *
     * @return bool|mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getActiveMvcGroup($vName)
    {
        $mvcGroups = self::getMVCgroups();

        foreach( $mvcGroups as $key => $mvcGroup )
        {
            if(is_numeric($key))
            {
                foreach($mvcGroup['objects'] as $object)
                {
                    if($vName == $object['vName'])
                    {
                        $mvcGroup['defaultTitle'] = $mvcGroups['defaultTitle'];
                        $mvcGroup['defaultController'] = $mvcGroups['defaultController'];
                        return $mvcGroup;
                    }
                }
            }
        }
        return false;
    }
    /**
     * Obtains FilterFields from the received configuration
     *
     * @param $config
     *
     * @return array
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getFilterFields($config )
    {
        $filterFields = array();
        if(is_array($config['fields']))
        {
            foreach($config['fields'] as $field)
            {
                if($field['sort'])
                {
                    $filterFields[] = $field['column'];
                }
            }
        }
        return $filterFields;
    }

    /* Obtains Sorted fields from the received configuration
     *
     *
     * @param $config
     *
     * @return array
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function getSortFields($config )
    {
        $sortFields = array();
        if(is_array($config['fields']))
        {
            foreach($config['fields'] as $field)
            {
                if($field['sort'])
                {
                    $sortFields[$field['column']] = $field['label'];
                }
            }
        }
        return $sortFields;
    }
}
