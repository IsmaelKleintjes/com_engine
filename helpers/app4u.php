<?php defined('_JEXEC') or die;

/**
 * Class App4U
 *
 * @version     1.0
 * @since       21-11-2016
 */
class App4U {

    /**
     * Debugs code.
     *
     * @param   string  $var   the given reassortment when called.
     * @param   bool   $title  if called generates a title for the debug.
     *
     * @version     1.0
     * @since       21-11-2016
     */
	public static function debug( $var, $title=false ) {
        $html = "<fieldset>";
            $html .= "<legend>DEBUG: ".$title."</legend>";
            $html .= "<pre>";
                if(is_string($var)):
                    $var = str_replace( '#__', 'ENGINE_', $var );
                endif;
                $html .= print_r( $var, true );
            $html .= "</pre>";
        $html .= "</fieldset>";
        echo $html;
	}

	public static function getParam($param, $component = 'com_engine')
    {
        $params = JComponentHelper::getParams($component);

        return $params->get($param);
    }

    public function verifyRecaptcha()
    {
        if(empty(App4U::getParam('recaptcha_secret'))) {
            return true;
        }

        $response = Input4U::get('g-recaptcha-response', 'REQUEST');

        if(empty($response)){
            return false;
        }

        $data = array(
            'secret' => App4U::getParam('recaptcha_secret'),
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );

        $httpQuery  = http_build_query($data);

        if ($curl = curl_init())
        {
            curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $httpQuery);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            $out = curl_exec($curl);
            curl_close($curl);

            $json = json_decode($out, true);

            if((int)$json['success'] !== 1){
                return false;
            }

            $action = Input4U::get('g-recaptcha-action', 'REQUEST');

            if($json['action'] != $action){
                return false;
            }

            $minimalScore = App4U::getParam('recaptcha_score');

            if(empty($minimalScore)){
                $minimalScore = 0.5;
            }

            if((float)$json['score'] < (float)$minimalScore){
                return false;
            }

            return true;
        }

        return false;
    }

    public static function isLive()
    {
        $uri = JUri::getInstance();

        return (str_replace('www.', '', $uri->getHost()) == '3bunal.nl');
    }

    public static function formatSearch($column, $qn = true)
    {
        $db = JFactory::getDBO();

        return 'LOWER(REPLACE(IFNULL(' . ($qn ? $db->qn($column) : $column) . ', ' . $db->q('') . ') , ' . $db->q(' ') . ', ' . $db->q('') . '))';
    }

    public static function getCalendarDate($calDate)
    {
        $config = JFactory::getConfig();

        $date = JFactory::getDate($calDate, 'UTC');
        $date->setTimezone(new DateTimeZone($config->get('offset')));

        // Transform the date string.
        $value = $date->format('Y-m-d', true, false);

        return $value;
    }

    public static function getAllowedUsergroups()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('MAX(lft)');
        $query->from($db->qn('#__usergroups'));
        $query->where($db->qn('id') . ' IN (' . implode(',', JFactory::getUser()->groups) . ')');

        $db->setQuery($query, 0, 1);
        $maxLeft = $db->loadResult();

        $query = $db->getQuery(true);

        $query->select('id');
        $query->from($db->qn('#__usergroups'));
        $query->where($db->qn('lft') . ' <= ' . (int)$maxLeft);

        $db->setQuery($query);

        return $db->loadColumn();
    }

    public static function getAllowedViewlevels()
    {
        $usergroups = self::getAllowedUsergroups();

        $db = JFactory::getDBO();

        $query = $db->getQuery(true);

        $query->select('id');
        $query->from($db->qn('#__viewlevels'));

        foreach($usergroups as $usergroupKey => $usergroup){
            $method = 'orWhere';

            if($usergroupKey == 0){
                $method = 'where';
            }

            $query->$method('
            (' . $db->qn('rules') . ' LIKE \'%[' . $usergroup . ',%\') 
            OR
            (' . $db->qn('rules') . ' LIKE \'%,' . $usergroup . ']%\') 
            OR
            (' . $db->qn('rules') . ' LIKE \'%,' . $usergroup . ',%\') 
            OR
            (' . $db->qn('rules') . ' = \'[' . $usergroup . ']\') 
            ');
        }

        $db->setQuery($query);
        $array = $db->loadColumn();

        return array_merge(array(1), $array);
    }

    public static function addBaseUrl($url)
    {
        $juriBase = str_replace('/administrator', '', JUri::base());

        $bases = explode('/', str_replace(array('https://', 'http://'), '', rtrim($juriBase, '/')));

        $url = str_replace(array('https://', 'http://'), '', ltrim($url, '/'));

        foreach($bases as $base){
            $url = preg_replace('/^' . $base . '\//', '', $url);
        }

        return $juriBase . $url;
    }

    public static function addRootUrl($url)
    {
        $juriBase = str_replace('/administrator', '', JUri::base());

        $bases = explode('/', str_replace(array('https://', 'http://'), '', rtrim($juriBase, '/')));

        $url = str_replace(array('https://', 'http://'), '', ltrim($url, '/'));

        foreach($bases as $base){
            $url = preg_replace('/^' . $base . '\//', '', $url);
        }

        return JPATH_ROOT . '/' . $url;
    }

    /**
     * Checks if IP is ours
     *
     * @return bool
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function isMe()
	{
		if($_SERVER['REMOTE_ADDR']=='87.211.104.224' 
			|| $_SERVER['REMOTE_ADDR']=='82.204.8.74'
			|| $_SERVER['REMOTE_ADDR']=='83.86.32.73'
			|| $_SERVER['REMOTE_ADDR']=='83.84.236.26'
			|| $_SERVER['REMOTE_ADDR']=='37.17.212.73'
			|| $_SERVER['REMOTE_ADDR']=='83.86.38.139'
			|| $_SERVER['REMOTE_ADDR']=='94.209.93.248'
			|| $_SERVER['REMOTE_ADDR']=='84.104.110.252'
            || $_SERVER['REMOTE_ADDR']=='185.40.96.250'
		){ 
			return true;
		} else {
			return false;
		}
	}

    public static function isMobile()
	{
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

	public static function getUserFields($user)
    {
        $return = clone $user;

        $customFields = FieldsHelper::getFields('com_users.user', $return, true);

        foreach($customFields as $jcField){
            $return->jcfields[$jcField->name] = $jcField;
        }

        return $return;
    }

    public static function transformJcFields($object)
    {
        $jcFields = array();

        foreach($object->jcfields as $jcField){
            $jcFields[$jcField->name] = $jcField;
        }

        $object->jcfields = $jcFields;
    }

    public static function transformJcGroups($object)
    {
        $jcGroups = array();

        JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_fields/tables/');
        JModelLegacy::addIncludePath("components/com_fields/models/");
        $groupModel = JModelLegacy::getInstance('Group', 'FieldsModel');

        foreach($object->jcfields as $jcField){
            if(!strlen(trim($jcField->value))){
                continue;
            }

            if(!isset($jcGroups[$jcField->group_id])){
                $jcGroups[$jcField->group_id] = $groupModel->getItem($jcField->group_id);
            }

            $jcGroups[$jcField->group_id]->fields[$jcField->id] = $jcField;
        }

        $object->jcgroups = $jcGroups;
    }

    public static function getJcGroups($context, $includeFields = false)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("*");
        $query->from($db->qn("#__fields_groups"));
        $query->where($db->qn("context") . " = " . $db->q($context));
        $query->where($db->qn("state") . " = 1");

        $db->setQuery($query);
        $items = $db->loadObjectList();

        if(!empty($items) && $includeFields) {
            foreach ($items as $item) {
                $item->fields = self::getJcFields($context, $item->id);
            }
        }

        return $items;
    }

    public static function getJcFields($context, $groupId)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("*");
        $query->from($db->qn("#__fields"));
        $query->where($db->qn("context") . " = " . $db->q($context));
        $query->where($db->qn("group_id") . " = " . (int)$groupId);
        $query->where($db->qn("state") . " = 1");

        $db->setQuery($query);
        $items = $db->loadObjectList();

        return $items;
    }

    public static function getJcValues($fieldId, $itemIds)
    {
        if(empty($itemIds)) {
            return false;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("*");
        $query->from($db->qn("#__fields_values"));
        $query->where($db->qn("field_id") . " = " . (int)$fieldId);
        $query->where($db->qn("item_id") . " IN (".implode(', ', $itemIds).")");

        $db->setQuery($query);
        $items = $db->loadObjectList();

        $newItems = array();
        if(!empty($items)) {
            foreach ($items as $item) {
                $newItems[$item->item_id] = $item->value;
            }
        }

        return $newItems;
    }
    
    // ----------------------------------------------------------------------------------------------------------------

    public function geocode($string)
    {
        $string = str_replace (" ", "+", urlencode($string));
        $details_url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $string . '&sensor=false&key=' . self::getParam('google_maps_api_key_geocode');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);

        $geometry = $response['results'][0]['geometry']['location'];

        if($response['status'] != 'OK'){
            $geometry['lat'] = 0;
            $geometry['lng'] = 0;
        }

        return $geometry;
    }

    // ----------------------------------------------------------------------------------------------------------------

    /**
     * Places multiple zero's
     *
     * @param $value
     * @param int $totalLenght
     *
     * @return string
     *
     * @since   Engine 2.0
     */
    public function placeZeros($value, $totalLenght=2 ) {
		$curLenght = strlen($value);
		if( $curLenght != $totalLenght ) {
			$dif = $totalLenght - $curLenght;
			for($i=$curLenght;$i<=$totalLenght;$i++) {
				$zeros = "0";
			}
			$value = $zeros.$value;
		}
		return $value;
	}

    // ----------------------------------------------------------------------------------------------------------------

    /**
     * Description comes later
     *
     * Will be updated by Sander
     *
     * @param $userId
     * @return bool|void
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function login($userId)
    {
        $app =& JFactory::getApplication();
        $instance =& JFactory::getUser($userId);

        // If _getUser returned an error, then pass it back.
        if (JError::isError($instance)) {
            $app->redirect('index.php');
            return;
        }

        // If the user is blocked, redirect with an error
        if ($instance->get('block') == 1) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('E_NOLOGIN_BLOCKED'));
            $app->redirect('index.php');
            return;
        }

        // Get the user group from the ACL
        if ($instance->get('tmp_user') == 1) {
            $grp = new JObject;
            // This should be configurable at some point
            $grp->set('name', 'Registered');
        }

        //Authorise the user based on the group information
        if(!isset($options['group'])) {
            $options['group'] = 'USERS';
        }

        //Mark the user as logged in
        $instance->set( 'guest', 0);
        $instance->set('aid', 1);

        //Set the usertype based on the ACL group name
        $instance->set('usertype', $grp->name);

        // Register the needed session variables
        $session =& JFactory::getSession();
        $session->set('user', $instance);

        // Get the session object
        $table = & JTable::getInstance('session');
        $table->load( $session->getId() );

        $table->guest   = $instance->get('guest');
        $table->username  = $instance->get('username');
        $table->userid   = intval($instance->get('id'));
        $table->usertype  = $instance->get('usertype');
        $table->update();

        // Hit the user last visit field
        $instance->setLastVisit();

        return true;
    }
}
